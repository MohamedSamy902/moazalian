<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Section;

class SectionController extends Controller
{
    public function index()
    {
        // Get unique section names and their translation names
        $sections = Section::select('page', 'section_name')
            ->distinct()
            ->get()
            ->map(function ($sec) {
                return (object)[
                    'page' => $sec->page,
                    'section_name' => $sec->section_name,
                    'name_ar' => $this->getSectionNameAr($sec->section_name),
                    'count' => Section::where('section_name', $sec->section_name)->count()
                ];
            });
            
        return view('dashboard.sections.index', compact('sections'));
    }

    public function edit($section_name)
    {
        $keys = Section::where('section_name', $section_name)->get();
        if ($keys->isEmpty()) abort(404);

        $name_ar = $this->getSectionNameAr($section_name);
        return view('dashboard.sections.edit', compact('keys', 'section_name', 'name_ar'));
    }

    public function update(Request $request, $section_name)
    {
        $keys = Section::where('section_name', $section_name)->get();
        if ($keys->isEmpty()) abort(404);

        foreach ($keys as $keyModel) {
            $key = $keyModel->key;

            if ($keyModel->type === 'image') {
                if ($request->hasFile($key)) {
                    $path = $request->file($key)->store('sections', 'public');
                    $val = 'storage/' . $path;
                    $keyModel->setTranslation('value', 'ar', $val);
                    $keyModel->setTranslation('value', 'en', $val);
                    $keyModel->save();
                }
            } elseif ($keyModel->type === 'url') {
                if ($request->has($key)) {
                    $val = $request->input($key);
                    $keyModel->setTranslation('value', 'ar', $val);
                    $keyModel->setTranslation('value', 'en', $val);
                    $keyModel->save();
                }
            } else {
                if ($request->has("{$key}_ar")) {
                    $keyModel->setTranslation('value', 'ar', $request->input("{$key}_ar"));
                }
                if ($request->has("{$key}_en")) {
                    $keyModel->setTranslation('value', 'en', $request->input("{$key}_en"));
                }
                $keyModel->save();
            }
        }

        \Illuminate\Support\Facades\Cache::forget('home_data');
        return redirect()->route('admin.sections.index')->with('success', __('Section updated successfully'));
    }

    private function getSectionNameAr($name)
    {
        $map = [
            'hero' => __('Hero Section'),
            'hero_stats' => __('Hero Stats'),
            'socials' => __('Social Links'),
            'why_moaz' => __('Why Moaz?'),
            'yt_cta' => __('YouTube CTA'),
            'donations' => __('Donations Section')
        ];
        return $map[$name] ?? $name;
    }
}
