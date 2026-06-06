@extends('frontend.layouts.master')

@section('content')
    <section class="py-5" style="margin-top: 80px; min-height: 80vh;">
        <div class="container">

            <div class="sec-head text-center mb-5">
                <div class="sec-eyebrow"><i class="bi bi-play-circle-fill"></i> مكتبة المرئيات</div>
                <h1 class="sec-title">جميع الفيديوهات والمناظرات</h1>
                <p class="sec-sub">تصفح أحدث المناظرات، النقود، والحوارات المباشرة لمعاذ عليان</p>
            </div>

            <!-- Search & Filter Form -->
            <form action="{{ route('videos.index') }}" method="GET" class="mb-5">
                <div class="search-wrap reveal mx-auto mb-4" style="max-width: 600px; display: flex; align-items: center;">
                    <i class="bi bi-search ms-2" style="color:var(--text3)"></i>
                    <input type="text" name="search" placeholder="ابحث في الفيديوهات..." value="{{ request('search') }}" style="border:none; outline:none; background:transparent; width:100%; color:var(--text);" onkeydown="if(event.key === 'Enter'){this.form.submit()}"/>
                </div>

                <div class="d-flex gap-2 flex-wrap justify-content-center mb-4 reveal" id="vid-filters">
                    <a href="{{ route('videos.index', ['search' => request('search')]) }}" class="vid-filter-btn text-decoration-none {{ !request('category') ? 'active' : '' }}">الكل</a>
                    @foreach (\App\Enums\VideoCategory::cases() as $category)
                        <a href="{{ route('videos.index', ['category' => $category->value, 'search' => request('search')]) }}" class="vid-filter-btn text-decoration-none {{ request('category') == $category->value ? 'active' : '' }}">
                            {{ $category->label() }}
                        </a>
                    @endforeach
                </div>
            </form>

            <!-- Videos Grid -->
            <div class="row g-4">
                @forelse($videos as $video)
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('videos.show', $video->slug) }}" class="card-x d-block h-100"
                            style="text-decoration:none">
                            <div class="vid-thumb">
                                @php
                                    $thumbnail = $video->thumbnail ? asset($video->thumbnail) : null;
                                    if (
                                        !$thumbnail &&
                                        $video->video_type == 'external' &&
                                        str_contains($video->video_url, 'youtube.com/watch?v=')
                                    ) {
                                        parse_str(parse_url($video->video_url, PHP_URL_QUERY), $vars);
                                        $ytId = $vars['v'] ?? null;
                                        if ($ytId) {
                                            $thumbnail = "https://img.youtube.com/vi/{$ytId}/maxresdefault.jpg";
                                        }
                                    }
                                @endphp
                                <img src="{{ $thumbnail ?? asset('front/assets/moaz.jpg') }}"
                                    alt="{{ $video->getTranslation('title', app()->getLocale()) }}" loading="lazy"
                                    onerror="this.style.display='none';this.parentElement.style.background='linear-gradient(135deg,#0d1a2d,#1a0d2d)'">
                                <div class="yt-play">
                                    <div class="play-circle"><i class="bi bi-play-fill"></i></div>
                                </div>
                                @if ($video->duration)
                                    <span class="vid-duration">{{ $video->duration }}</span>
                                @endif
                            </div>
                            <div class="vid-body">
                                <span class="vid-cat"><i
                                        class="bi bi-play-btn me-1"></i>{{ $video->category?->label() ?? 'فيديو' }}</span>
                                <div class="vid-title">{{ $video->getTranslation('title', app()->getLocale()) }}</div>
                                <div class="vid-meta">
                                    <i class="bi bi-eye"></i> {{ number_format($video->views) }} مشاهدة
                                    <i class="bi bi-calendar3 ms-2"></i>
                                    {{ $video->published_at->translatedFormat('F Y') }}
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-camera-video text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">لا توجد فيديوهات مطابقة لبحثك</h4>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $videos->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </section>
@endsection
