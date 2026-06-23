@extends("frontend.layouts.master")

@php
    $locale      = app()->getLocale();
    $videoTitle  = $video->getTranslation('title', $locale);
    $videoDesc   = strip_tags($video->getTranslation('description', $locale));
    $videoDesc   = \Illuminate\Support\Str::limit($videoDesc, 160);

    // Resolve thumbnail for OG
    $ogImage = null;
    if ($video->thumbnail) {
        $ogImage = str_starts_with($video->thumbnail, 'http') ? $video->thumbnail : asset($video->thumbnail);
    }
    if (!$ogImage && $video->video_type == 'external' && str_contains($video->video_url, 'youtube.com/watch?v=')) {
        parse_str(parse_url($video->video_url, PHP_URL_QUERY), $_ytVars);
        $_ytId = $_ytVars['v'] ?? null;
        if ($_ytId) $ogImage = "https://img.youtube.com/vi/{$_ytId}/maxresdefault.jpg";
    }
    $ogImage = $ogImage ?? asset('front/assets/og-image.jpg');
@endphp

@php
    // Use custom SEO if set in DB, otherwise fall back to video title/desc
    $seoTitle       = $video->getTranslation('seo_title', $locale) ?: ($videoTitle . ' | معاذ عليان');
    $seoDescription = $video->getTranslation('seo_description', $locale) ?: ($videoDesc ?: 'فيديو من قناة معاذ عليان في مقارنة الأديان');
    $seoKeywords    = $video->getTranslation('seo_keywords', $locale) ?: 'معاذ عليان, مقارنة الأديان, مناظرة';
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('meta_keywords', $seoKeywords)
@section('og_title', $seoTitle)
@section('og_description', $seoDescription)
@section('og_image', $ogImage)

@section("content")
<section class="py-5" style="margin-top: 80px; min-height: 80vh;">
    <div class="container">
        <div class="row g-5">
            
            <!-- Video Content (Main) -->
            <div class="col-lg-8">
                <!-- Video Player -->
                <div class="video-player-container mb-4" style="border-radius: 16px; overflow: hidden; box-shadow: var(--shadow); background: #000; aspect-ratio: 16/9; position: relative;">
                    @if($video->video_type == 'external' && str_contains($video->video_url, 'youtube.com/watch?v='))
                        @php
                            parse_str(parse_url($video->video_url, PHP_URL_QUERY), $vars);
                            $ytId = $vars['v'] ?? null;
                        @endphp
                        @if($ytId)
                            <iframe src="https://www.youtube.com/embed/{{ $ytId }}" style="width: 100%; height: 100%; border: none;" allowfullscreen></iframe>
                        @else
                            <a href="{{ $video->video_url }}" target="_blank" class="d-flex align-items-center justify-content-center h-100 w-100 text-white text-decoration-none">
                                <i class="bi bi-play-circle" style="font-size: 4rem;"></i>
                                <span class="ms-3 fs-4">شاهد الفيديو</span>
                            </a>
                        @endif
                    @elseif($video->video_type == 'upload')
                        <video controls style="width: 100%; height: 100%;">
                            <source src="{{ asset($video->video_url) }}" type="video/mp4">
                            متصفحك لا يدعم تشغيل الفيديو.
                        </video>
                    @else
                        <a href="{{ $video->video_url }}" target="_blank" class="d-flex align-items-center justify-content-center h-100 w-100 text-white text-decoration-none">
                            <i class="bi bi-play-circle" style="font-size: 4rem;"></i>
                            <span class="ms-3 fs-4">شاهد الفيديو عبر الرابط الخارجي</span>
                        </a>
                    @endif
                </div>

                <!-- Video Title & Meta -->
                <div class="mb-4">
                    <span class="badge" style="background: var(--gold); color: #fff; font-size: 0.9rem; margin-bottom: 10px;">{{ $video->category?->label() ?? 'فيديو' }}</span>
                    <h1 class="fw-bold mb-3" style="color: var(--text); font-size: clamp(1.5rem, 3vw, 2.2rem);">{{ $video->getTranslation('title', app()->getLocale()) }}</h1>
                    <div class="d-flex align-items-center flex-wrap gap-4 text-muted small pb-3 border-bottom" style="color: var(--text2);">
                        <span><i class="bi bi-calendar3 me-1"></i> {{ $video->published_at->translatedFormat('d F Y') }}</span>
                        <span><i class="bi bi-eye me-1"></i> {{ number_format($video->views) }} مشاهدة</span>
                        @if($video->duration)
                        <span><i class="bi bi-clock me-1"></i> {{ $video->duration }}</span>
                        @endif
                        <button class="btn btn-sm btn-light ms-auto" style="border-radius: 20px; font-weight: bold;" onclick="navigator.clipboard.writeText(window.location.href); alert('تم نسخ الرابط');">
                            <i class="bi bi-share me-1"></i> مشاركة
                        </button>
                    </div>
                </div>

                <!-- Video Description -->
                <div class="video-description mb-5" style="color: var(--text); line-height: 1.8; font-size: 1.05rem;">
                    {!! $video->getTranslation('description', app()->getLocale()) !!}
                </div>

                <!-- References Section -->
                @if($video->references->count() > 0)
                <div class="mt-4 pt-4 border-top">
                    <h4 class="fw-bold mb-4" style="color: var(--gold); display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-journal-bookmark-fill"></i> المراجع والمصادر المستخدمة في الحلقة
                    </h4>
                    
                    <div class="row g-3">
                        @foreach($video->references as $ref)
                        <div class="col-sm-6 col-md-4">
                            <div class="reference-card p-3 h-100" style="background: var(--card); border-radius: 12px; border: 1px solid var(--border); transition: transform 0.2s;">
                                @if($ref->title)
                                    <h6 class="fw-bold mb-3" style="color: var(--text); font-size: 0.95rem; line-height: 1.5;">{{ $ref->getTranslation('title', app()->getLocale()) }}</h6>
                                @endif
                                
                                @if($ref->type == 'link')
                                    <a href="{{ $ref->content }}" target="_blank" class="btn btn-sm btn-outline-primary w-100" style="border-radius: 8px;">
                                        <i class="bi bi-link-45deg me-1"></i> عرض الرابط
                                    </a>
                                @elseif($ref->type == 'upload')
                                    @if(preg_match('/\.(mp4|webm|ogg)$/i', $ref->content))
                                        <!-- Video Short Player -->
                                        <div style="border-radius: 8px; overflow: hidden; background: #000; aspect-ratio: 9/16; position: relative;">
                                            <video controls style="width: 100%; height: 100%; object-fit: cover;" preload="metadata">
                                                <source src="{{ asset($ref->content) }}" type="video/mp4">
                                            </video>
                                        </div>
                                    @elseif(preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $ref->content))
                                        <!-- Image Display -->
                                        <a href="{{ asset($ref->content) }}" target="_blank">
                                            <img src="{{ asset($ref->content) }}" class="img-fluid w-100" style="border-radius: 8px; object-fit: cover; aspect-ratio: 16/9;">
                                        </a>
                                    @else
                                        <!-- File Download -->
                                        <a href="{{ asset($ref->content) }}" target="_blank" class="btn btn-sm btn-outline-success w-100" style="border-radius: 8px;">
                                            <i class="bi bi-file-earmark-arrow-down me-1"></i> تحميل الملف
                                        </a>
                                    @endif
                                @elseif($ref->type == 'text')
                                    <p class="mb-0 small" style="color: var(--text2); line-height: 1.6;">{{ $ref->content }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar (Related) -->
            <div class="col-lg-4">
                
                <!-- Related Videos Section -->
                @if($relatedVideos->count() > 0)
                <div class="p-4" style="background: var(--card); border-radius: 16px; box-shadow: var(--shadow); border: 1px solid var(--border);">
                    <h4 class="fw-bold mb-4" style="color: var(--text); border-bottom: 2px solid var(--gold); padding-bottom: 10px; display: inline-block;">
                        <i class="bi bi-collection-play me-2" style="color: var(--teal);"></i> فيديوهات ذات صلة
                    </h4>

                    <div class="d-flex flex-column gap-3">
                        @foreach($relatedVideos as $relVideo)
                        <a href="{{ route('videos.show', $relVideo->slug) }}" class="d-flex gap-3 text-decoration-none" style="background: var(--bg); padding: 10px; border-radius: 12px; border: 1px solid var(--border); transition: transform 0.2s;">
                            <div class="flex-shrink-0 position-relative" style="width: 100px; height: 65px; border-radius: 8px; overflow: hidden; background: #000;">
                                @php
                                    $relThumb = $relVideo->thumbnail ? asset($relVideo->thumbnail) : null;
                                    if (!$relThumb && $relVideo->video_type == 'external' && str_contains($relVideo->video_url, 'youtube.com/watch?v=')) {
                                        parse_str(parse_url($relVideo->video_url, PHP_URL_QUERY), $relVars);
                                        $relYtId = $relVars['v'] ?? null;
                                        if ($relYtId) $relThumb = "https://img.youtube.com/vi/{$relYtId}/default.jpg";
                                    }
                                @endphp
                                <img src="{{ $relThumb ?? asset('front/assets/moaz.jpg') }}" alt="" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div style="overflow: hidden;">
                                <h6 class="mb-1 fw-bold text-truncate" style="color: var(--text); max-width: 100%; font-size: 0.9rem;">{{ $relVideo->getTranslation('title', app()->getLocale()) }}</h6>
                                <small style="color: var(--text3);"><i class="bi bi-eye me-1"></i> {{ number_format($relVideo->views) }} مشاهدة</small>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
                
            </div>
        </div>
    </div>
</section>
@endsection
