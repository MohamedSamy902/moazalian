@extends('frontend.layouts.master')

@php
    $locale         = app()->getLocale();
    $artTitle       = $article->getTranslation('title', $locale);
    $artExcerpt     = strip_tags($article->getTranslation('excerpt', $locale));
    // Custom SEO with fallback to content
    $seoTitle       = $article->getTranslation('seo_title', $locale) ?: ($artTitle . ' | معاذ عليان');
    $seoDescription = $article->getTranslation('seo_description', $locale)
                      ?: \Illuminate\Support\Str::limit($artExcerpt, 160)
                      ?: 'مقال من معاذ عليان في مقارنة الأديان';
    $seoKeywords    = $article->getTranslation('seo_keywords', $locale)
                      ?: 'معاذ عليان, ' . ($article->category?->label() ?? 'مقال') . ', مقارنة الأديان';
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('meta_keywords', $seoKeywords)
@section('og_title', $seoTitle)
@section('og_description', $seoDescription)

@push('styles')
<style>
  .article-header {
    padding: 60px 0; text-align: center;
    background: linear-gradient(135deg, #0d1a2d, #1a2d0d);
    color: white; border-radius: 20px;
    margin-bottom: 40px; margin-top: 20px;
    position: relative; overflow: hidden;
  }
  .article-content { font-size: 1.15rem; line-height: 2; color: var(--text); transition: font-size 0.3s ease; }
  .article-content h2 { color: var(--gold); margin-top: 40px; margin-bottom: 20px; font-weight: 700; }
  .article-content blockquote {
    border-right: 4px solid var(--gold); padding: 20px; background: var(--bg2);
    margin: 30px 0; border-radius: 8px 0 0 8px; font-style: italic; color: var(--text2);
  }
</style>
@endpush

@section('content')
{{-- Reading progress bar is in header.blade.php --}}

<div class="container" style="margin-top: 100px;">
    <div class="row">

        {{-- Main Content --}}
        <div class="col-lg-8 mx-auto">

            {{-- Article Header --}}
            <div class="article-header">
                <div class="container">
                    <span class="badge-x badge-gold mb-3">
                        <i class="bi bi-tag-fill me-1"></i>{{ $article->category?->label() ?? 'مقال' }}
                    </span>
                    <h1 style="font-size: clamp(1.6rem, 4vw, 2.5rem); font-weight: 900; color: #fff; line-height: 1.4;">
                        {{ $article->getTranslation('title', app()->getLocale()) }}
                    </h1>
                    <div class="d-flex justify-content-center gap-4 mt-3 flex-wrap" style="font-size:.9rem; color:rgba(255,255,255,.7)">
                        <span><i class="bi bi-calendar3 me-1"></i>{{ $article->published_at?->translatedFormat('j F Y') }}</span>
                        @if($article->read_time)
                            <span><i class="bi bi-clock me-1"></i>{{ $article->read_time }} دقائق قراءة</span>
                        @endif
                        @if(isset($article->views))
                            <span><i class="bi bi-eye me-1"></i>{{ number_format($article->views) }} مشاهدة</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Article Body --}}
            <div class="article-content" id="article-text">
                {!! $article->getTranslation('body', app()->getLocale()) !!}
            </div>

            {{-- Share Bar --}}
            <div class="d-flex align-items-center gap-3 mt-5 flex-wrap">
                <span class="fw-bold" style="color: var(--text);">مشاركة المقال:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                    target="_blank" class="btn btn-sm" style="background:#1877f2; color:#fff; border-radius:8px;">
                    <i class="bi bi-facebook me-1"></i>فيسبوك
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->getTranslation('title', app()->getLocale())) }}"
                    target="_blank" class="btn btn-sm" style="background:#000; color:#fff; border-radius:8px;">
                    <i class="bi bi-twitter-x me-1"></i>تويتر
                </a>
                <a href="https://wa.me/?text={{ urlencode($article->getTranslation('title', app()->getLocale()) . ' ' . url()->current()) }}"
                    target="_blank" class="btn btn-sm" style="background:#25d366; color:#fff; border-radius:8px;">
                    <i class="bi bi-whatsapp me-1"></i>واتساب
                </a>
            </div>

            {{-- Author Box --}}
            <div class="card-x mt-5 p-4 d-flex align-items-center gap-4" style="background: var(--bg2); border: 1px solid var(--border);">
                <img src="{{ asset('front/assets/moaz.jpg') }}" alt="معاذ عليان"
                    style="border-radius: 50%; width: 80px; height: 80px; object-fit:cover; border: 3px solid var(--gold); flex-shrink:0">
                <div>
                    <h4 class="mb-1" style="font-weight: 700; color: var(--gold);">معاذ عليان</h4>
                    <p class="mb-0" style="color: var(--text2); font-size: 0.9rem;">
                        إعلامي وباحث مصري متخصص في مقارنة الأديان، لديه عشرات المناظرات والمؤلفات في نقد الكتاب المقدس والحوار الديني.
                    </p>
                </div>
            </div>

            {{-- Related Articles --}}
            @if($relatedArticles->isNotEmpty())
                <div class="mt-5">
                    <h3 class="mb-4" style="color: var(--text); font-weight: 700;">
                        <i class="bi bi-journals me-2" style="color:var(--gold)"></i>مقالات ذات صلة
                    </h3>
                    <div class="row g-3">
                        @foreach($relatedArticles as $related)
                            <div class="col-md-4">
                                <a href="{{ route('articles.show', $related->slug) }}" class="art-card d-block h-100" style="text-decoration:none">
                                    <div class="art-img" style="background: linear-gradient(135deg,#0d1a2d,#1a0d2d)">
                                        <i class="art-img-icon bi bi-journal-text"></i>
                                        <div class="art-img-overlay"></div>
                                    </div>
                                    <div class="art-body">
                                        <span class="art-cat"><i class="bi bi-tag-fill me-1"></i>{{ $related->category?->label() }}</span>
                                        <div class="art-title" style="font-size:.92rem">{{ $related->getTranslation('title', app()->getLocale()) }}</div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<div class="py-4"></div>
@endsection

@push('scripts')
<script>
// Reading Progress Bar
window.addEventListener('scroll', () => {
    const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrolled = (winScroll / height) * 100;
    const bar = document.getElementById('reading-progress-bar');
    if (bar) bar.style.width = scrolled + '%';
});
</script>
@endpush
