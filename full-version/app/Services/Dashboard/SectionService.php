<?php

namespace App\Services\Dashboard;

use App\Models\Section;
use App\Support\CacheKeys;
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
        'seo'        => 'SEO - محركات البحث',
        'features'   => 'المميزات',
    ];

    private const PAGE_NAMES = [
        'home'       => 'الصفحة الرئيسية',
        'about'      => 'عن معاذ عليان',
        'dawah'      => 'الدعوة للإسلام',
        'live'       => 'البث المباشر',
        'references' => 'المراجع والمصادر',
        'contact'    => 'تواصل معنا',
    ];

    /**
     * Get all unique sections grouped by page, with translated names and record counts.
     */
    public function getGroupedSections(): \Illuminate\Support\Collection
    {
        $allSections = Section::select('page', 'section_name')->get();

        // Group by page+section_name composite key for counting
        $counts = $allSections->groupBy(fn ($s) => $s->page . '|' . $s->section_name)
            ->map(fn ($g) => $g->count());

        return $allSections
            ->unique(fn ($s) => $s->page . '|' . $s->section_name)
            ->map(fn ($sec) => (object) [
                'page'         => $sec->page,
                'section_name' => $sec->section_name,
                'name_ar'      => $this->getPageNameAr($sec->page) . ' — ' . $this->getSectionNameAr($sec->section_name),
                'count'        => $counts[$sec->page . '|' . $sec->section_name] ?? 0,
            ])
            ->sortBy(fn ($s) => [$s->page, $s->section_name])
            ->values();
    }

    /**
     * Retrieve all keys for a specific page + section_name for editing.
     */
    public function getSectionForEdit(string $page, string $sectionName): array
    {
        $keys = Section::where('page', $page)
            ->where('section_name', $sectionName)
            ->get();

        if ($keys->isEmpty()) {
            abort(404, 'القسم غير موجود.');
        }

        return [$keys, $this->getPageNameAr($page) . ' — ' . $this->getSectionNameAr($sectionName)];
    }

    /**
     * Persist section value changes and clear caches.
     */
    public function updateSection(string $page, string $sectionName, Request $request): void
    {
        $keys = Section::where('page', $page)
            ->where('section_name', $sectionName)
            ->get();

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

        // Invalidate homepage and all section caches
        Cache::forget(CacheKeys::HOME_DATA);
        Cache::forget(CacheKeys::sections($sectionName));
        // Also clear page-specific caches
        Cache::forget(CacheKeys::SECTIONS_HOME);
        Cache::forget(CacheKeys::SECTIONS_ABOUT);
        Cache::forget(CacheKeys::SECTIONS_DAWAH);
        Cache::forget(CacheKeys::SECTIONS_LIVE);
        // Clear shared social links cache (used by footer on all pages)
        Cache::forget('frontend_social_sections');
    }

    private function getSectionNameAr(string $name): string
    {
        return self::SECTION_NAMES[$name] ?? $name;
    }

    private function getPageNameAr(string $page): string
    {
        return self::PAGE_NAMES[$page] ?? $page;
    }
}
