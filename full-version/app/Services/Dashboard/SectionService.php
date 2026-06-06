<?php

namespace App\Services\Dashboard;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Handles all Section (CMS page content) business logic.
 * Resolves N+1 query that existed in the old SectionController.
 */
class SectionService
{
    private const SECTION_NAMES = [
        'hero'       => 'قسم الهيرو',
        'hero_stats' => 'إحصاءات الهيرو',
        'socials'    => 'روابط التواصل الاجتماعي',
        'why_moaz'   => 'لماذا معاذ؟',
        'yt_cta'     => 'دعوة قناة اليوتيوب',
        'donations'  => 'قسم التبرعات',
    ];

    /**
     * Get all unique sections with their translated names and record counts.
     * Resolves the N+1 query that was in the original SectionController by
     * loading all counts at once via groupBy instead of a loop.
     */
    public function getGroupedSections(): \Illuminate\Support\Collection
    {
        // Load all sections once, group and count in PHP — no N+1 queries
        $allSections = Section::select('page', 'section_name')->get();

        $counts = $allSections->groupBy('section_name')
            ->map(fn ($group) => $group->count());

        return $allSections
            ->unique(fn ($s) => $s->section_name)
            ->map(fn ($sec) => (object) [
                'page'         => $sec->page,
                'section_name' => $sec->section_name,
                'name_ar'      => $this->getSectionNameAr($sec->section_name),
                'count'        => $counts[$sec->section_name] ?? 0,
            ])
            ->values();
    }

    /**
     * Retrieve all keys for a specific section name for editing.
     * Returns [collection of keys, translated name].
     */
    public function getSectionForEdit(string $sectionName): array
    {
        $keys = Section::where('section_name', $sectionName)->get();

        if ($keys->isEmpty()) {
            abort(404, 'القسم غير موجود.');
        }

        return [$keys, $this->getSectionNameAr($sectionName)];
    }

    /**
     * Persist section value changes and clear the home_data cache.
     */
    public function updateSection(string $sectionName, Request $request): void
    {
        $keys = Section::where('section_name', $sectionName)->get();

        if ($keys->isEmpty()) {
            abort(404, 'القسم غير موجود.');
        }

        foreach ($keys as $keyModel) {
            $key = $keyModel->key;

            if ($keyModel->type === 'image') {
                if ($request->hasFile($key)) {
                    $path = $request->file($key)->store('sections', 'public');
                    $val  = 'storage/' . $path;
                    $keyModel->setTranslation('value', 'ar', $val);
                    $keyModel->setTranslation('value', 'en', $val);
                    $keyModel->save();
                }
            } elseif ($keyModel->type === 'url') {
                if ($request->has($key)) {
                    $val = filter_var($request->input($key), FILTER_SANITIZE_URL);
                    $keyModel->setTranslation('value', 'ar', $val);
                    $keyModel->setTranslation('value', 'en', $val);
                    $keyModel->save();
                }
            } else {
                if ($request->has("{$key}_ar")) {
                    $keyModel->setTranslation('value', 'ar', strip_tags($request->input("{$key}_ar")));
                }
                if ($request->has("{$key}_en")) {
                    $keyModel->setTranslation('value', 'en', strip_tags($request->input("{$key}_en")));
                }
                $keyModel->save();
            }
        }

        // Invalidate homepage cache
        Cache::forget('home_data');
        Cache::forget("section_{$sectionName}");
    }

    private function getSectionNameAr(string $name): string
    {
        return self::SECTION_NAMES[$name] ?? $name;
    }
}
