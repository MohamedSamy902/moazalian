<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
<title>@yield('title', 'معاذ عليان | باحث ومناظر في مقارنة الأديان')</title>
<meta name="description" content="@yield('meta_description', 'الموقع الرسمي للباحث والمناظر معاذ عليان. دراسات موثقة، ردود أكاديمية، ومناظرات مباشرة في الحوار الإسلامي المسيحي.')"/>
<meta name="keywords" content="@yield('meta_keywords', 'معاذ عليان, مقارنة الأديان, حوار إسلامي مسيحي, نقد كتابي, الإسلام, المسيحية, مناظرات')"/>
<meta name="author" content="معاذ عليان"/>
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1"/>
<meta name="theme-color" content="#121824"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<link rel="canonical" href="{{ url()->current() }}" />

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website"/>
<meta property="og:url" content="{{ url()->current() }}"/>
<meta property="og:title" content="@yield('og_title', 'معاذ عليان | الموقع الرسمي - باحث ومناظر في مقارنة الأديان')"/>
<meta property="og:description" content="@yield('og_description', 'الموقع الرسمي للباحث والمناظر معاذ عليان. نقدم لك دراسات موثقة، ردوداً أكاديمية، ومناظرات مباشرة في الحوار الإسلامي المسيحي ونقد الكتاب المقدس.')"/>
<meta property="og:image" content="@yield('og_image', asset('front/assets/og-image.jpg'))"/>
<meta property="og:site_name" content="الموقع الرسمي لمعاذ عليان"/>
<meta property="og:locale" content="{{ app()->getLocale() === 'ar' ? 'ar_AR' : 'en_US' }}"/>

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:url" content="{{ url()->current() }}"/>
<meta name="twitter:title" content="@yield('og_title', 'معاذ عليان | الموقع الرسمي - باحث ومناظر في مقارنة الأديان')"/>
<meta name="twitter:description" content="@yield('og_description', 'الموقع الرسمي للباحث والمناظر معاذ عليان. نقدم لك دراسات موثقة، ردوداً أكاديمية، ومناظرات مباشرة في الحوار الإسلامي المسيحي ونقد الكتاب المقدس.')"/>
<meta name="twitter:image" content="@yield('og_image', asset('front/assets/og-image.jpg'))"/>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;900&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('front/css/style.css') }}" />
  @stack('styles')
  <script>
    (function () {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
      } else {
        document.documentElement.setAttribute('data-theme', 'dark');
      }
    })();
  </script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body>
  @include('frontend.layouts.header')

  <main>
    @yield('content')
  </main>

  @include('frontend.layouts.footer')

  <!-- Scroll To Top Button -->
  <button id="scroll-top-btn" aria-label="العودة للأعلى" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="bi bi-arrow-up"></i>
  </button>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="{{ asset('front/js/script.js') }}"></script>
  @stack('scripts')
</body>
</html>
