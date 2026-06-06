<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
<meta name="robots" content="{{ $seo['robots'] }}">
<link rel="canonical" href="{{ $seo['canonical'] }}">

<!-- Open Graph -->
<meta property="og:title" content="{{ $seo['og_title'] }}">
<meta property="og:description" content="{{ $seo['og_description'] }}">
<meta property="og:image" content="{{ $seo['og_image'] }}">
<meta property="og:url" content="{{ $seo['og_url'] }}">
<meta property="og:type" content="{{ $seo['og_type'] }}">

<!-- Twitter -->
<meta name="twitter:card" content="{{ $seo['twitter_card'] }}">
<meta name="twitter:title" content="{{ $seo['twitter_title'] }}">
<meta name="twitter:description" content="{{ $seo['twitter_desc'] }}">
<meta name="twitter:image" content="{{ $seo['twitter_image'] }}">

<!-- Hreflang for Mcamara -->
@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
@endforeach

@if($seo['schema'])
    <script type="application/ld+json">
        {!! json_encode($seo['schema']) !!}
    </script>
@endif