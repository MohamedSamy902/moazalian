<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Support\Facades\Cache;

/**
 * Handles all static/semi-static frontend pages
 * that don't need complex data fetching logic.
 * Pages now include DB-driven SEO metadata.
 */
class StaticPageController extends Controller
{
    public function about()
    {
        $sections = $this->getSections('about');
        $pageSeo  = $this->getPageSeo('about');
        return view('frontend.about', compact('sections', 'pageSeo'));
    }

    public function dawah()
    {
        $sections = $this->getSections('dawah');
        $pageSeo  = $this->getPageSeo('dawah');
        return view('frontend.dawah', compact('sections', 'pageSeo'));
    }

    public function references()
    {
        $pageSeo = $this->getPageSeo('references');
        return view('frontend.references', compact('pageSeo'));
    }

    public function live()
    {
        $sections = $this->getSections('live');
        $pageSeo  = $this->getPageSeo('live');
        return view('frontend.live', compact('sections', 'pageSeo'));
    }

    // ─────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────

    private function getSections(string $page): \Illuminate\Support\Collection
    {
        return Cache::remember("sections_{$page}", now()->addHour(), function () use ($page) {
            return Section::where('page', $page)->get()->keyBy('key');
        });
    }

    /**
     * Returns [seo_title, seo_description, seo_keywords] for a page
     * from the DB sections (page_seo_title, page_seo_description, page_seo_keywords keys).
     * Falls back to empty strings if not set.
     */
    private function getPageSeo(string $page): array
    {
        $locale   = app()->getLocale();
        $sections = Cache::remember("sections_seo_{$page}", now()->addHour(), function () use ($page) {
            return Section::where('page', $page)
                ->where('section_name', 'seo')
                ->get()
                ->keyBy('key');
        });

        return [
            'title'       => $sections->get('page_seo_title')?->getTranslation('value', $locale) ?? '',
            'description' => $sections->get('page_seo_description')?->getTranslation('value', $locale) ?? '',
            'keywords'    => $sections->get('page_seo_keywords')?->getTranslation('value', $locale) ?? '',
        ];
    }
}
