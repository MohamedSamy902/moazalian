<?php
namespace App\Services\Core;

class SeoService
{
    public function build(array $data): array
    {
        $defaultTitle = app(SettingService::class)->get('default_meta_title', 'أكاديمية معاذ عليان');
        $defaultDesc = app(SettingService::class)->get('default_meta_desc', 'الموقع الرسمي للباحث والمناظر معاذ عليان');
        
        $title = isset($data['title']) ? $data['title'] . ' | ' . $defaultTitle : $defaultTitle;
        $description = $data['description'] ?? $defaultDesc;

        return [
            'title'           => $title,
            'description'     => $description,
            'canonical'       => $data['canonical'] ?? url()->current(),
            'og_title'        => $title,
            'og_description'  => $description,
            'og_image'        => $data['image'] ?? asset('assets/og-image.jpg'),
            'og_type'         => $data['og_type'] ?? 'website',
            'og_url'          => $data['canonical'] ?? url()->current(),
            'twitter_card'    => 'summary_large_image',
            'twitter_title'   => $title,
            'twitter_desc'    => $description,
            'twitter_image'   => $data['image'] ?? asset('assets/og-image.jpg'),
            'robots'          => $data['robots'] ?? 'index, follow, max-image-preview:large',
            'schema'          => $data['schema'] ?? null,
        ];
    }
}
