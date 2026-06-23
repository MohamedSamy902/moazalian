@extends('frontend.layouts.master')

@section('title', 'الفيديوهات | معاذ عليان')
@section('meta_description', 'مكتبة كاملة لفيديوهات ومناظرات معاذ عليان في مقارنة الأديان')

@section('content')
<main style="padding-top: 80px;">
  <section id="videos" class="py-5 bg2">
    <div class="container">

      {{-- Header --}}
      <div class="sec-head reveal">
        <div class="sec-eyebrow"><i class="bi bi-play-btn-fill"></i>المحتوى المرئي</div>
        <h1 class="sec-title">الفيديوهات</h1>
        <p class="sec-sub">محاضرات وحلقات ومقاطع في مقارنة الأديان والرد على الشبهات</p>
      </div>

      {{-- Search + Category Filters --}}
      <form action="{{ route('videos.index') }}" method="GET" id="vid-form">
        {{-- keep sort when searching --}}
        @if(request('sort') && request('sort') !== 'newest')
          <input type="hidden" name="sort" value="{{ request('sort') }}">
        @endif

        <div class="search-wrap reveal mx-auto mb-4" style="max-width:600px;display:flex;align-items:center">
          <i class="bi bi-search ms-2" style="color:var(--text3)"></i>
          <input type="text" name="search" placeholder="ابحث في الفيديوهات..."
            value="{{ request('search') }}"
            style="border:none;outline:none;background:transparent;width:100%;color:var(--text)"
            onkeydown="if(event.key==='Enter'){this.form.submit()}"/>
        </div>

        <div class="d-flex gap-2 flex-wrap justify-content-center mb-4 reveal">
          <a href="{{ route('videos.index', array_filter(['search' => request('search'), 'sort' => request('sort')])) }}"
             class="vid-filter-btn text-decoration-none {{ !request('category') ? 'active' : '' }}">الكل</a>
          @foreach(\App\Enums\VideoCategory::cases() as $cat)
            <a href="{{ route('videos.index', array_filter(['category' => $cat->value, 'search' => request('search'), 'sort' => request('sort')])) }}"
               class="vid-filter-btn text-decoration-none {{ request('category') == $cat->value ? 'active' : '' }}">
              {{ $cat->label() }}
            </a>
          @endforeach
        </div>
      </form>

      {{-- Results Bar: count + sort --}}
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 reveal">
        <div style="font-size:.82rem;color:var(--text3)">
          <i class="bi bi-collection-play me-1" style="color:var(--gold)"></i>
          <span>{{ $videos->total() }} فيديو</span>
          @if(request('search'))
            <span class="ms-1">— نتائج "<strong style="color:var(--text)">{{ request('search') }}</strong>"</span>
          @endif
        </div>
        {{-- Sort --}}
        <div class="sort-bar">
          <label><i class="bi bi-sort-down-alt me-1"></i>الترتيب:</label>
          <select class="sort-select" id="sort-select" onchange="applySort(this.value)">
            <option value="newest"   {{ $sort === 'newest'   ? 'selected' : '' }}>الأحدث أولاً</option>
            <option value="oldest"   {{ $sort === 'oldest'   ? 'selected' : '' }}>الأقدم أولاً</option>
            <option value="popular"  {{ $sort === 'popular'  ? 'selected' : '' }}>الأكثر مشاهدة</option>
            <option value="duration" {{ $sort === 'duration' ? 'selected' : '' }}>الأطول مدة</option>
          </select>
        </div>
      </div>

      {{-- Videos Grid --}}
      <div class="row g-4">
        @forelse($videos as $video)
          @php
            $locale    = app()->getLocale();
            $titleData = json_decode($video->getRawOriginal('title'), true);
            $title     = trim($titleData[$locale] ?? $titleData['ar'] ?? $titleData['en'] ?? '');
            if (!$title) {
              $title = trim($titleData['ar'] ?? $titleData['en'] ?? '');
            }
            if (!$title) $title = 'فيديو بدون عنوان';

            // Thumbnail
            $thumb = $video->thumbnail
              ? (str_starts_with($video->thumbnail, 'http') ? $video->thumbnail : asset($video->thumbnail))
              : null;
            if (!$thumb && $video->video_url && str_contains($video->video_url, 'youtube.com/watch?v=')) {
              parse_str(parse_url($video->video_url, PHP_URL_QUERY), $qv);
              $ytId = $qv['v'] ?? null;
              if ($ytId) $thumb = "https://i.ytimg.com/vi/{$ytId}/hqdefault.jpg";
            }
          @endphp
          <div class="col-md-6 col-lg-4 reveal">
            <a href="{{ route('videos.show', $video->slug) }}" class="card-x d-block h-100" style="text-decoration:none">
              <div class="vid-thumb">
                <img src="{{ $thumb ?? asset('front/assets/moaz.jpg') }}"
                  alt="{{ $title }}" loading="lazy"
                  onerror="this.src='{{ asset('front/assets/moaz.jpg') }}'">
                <div class="yt-play"><div class="play-circle"><i class="bi bi-play-fill"></i></div></div>
                @if($video->duration)
                  <span class="vid-duration">{{ $video->duration }}</span>
                @endif
              </div>
              <div class="vid-body">
                <span class="vid-cat">
                  <i class="bi bi-tag-fill me-1"></i>{{ $video->category?->label() ?? 'فيديو' }}
                </span>
                <div class="vid-title" title="{{ $title }}">{{ \Illuminate\Support\Str::limit($title, 80) }}</div>
                <div class="vid-meta">
                  <i class="bi bi-eye"></i>
                  <span>{{ number_format($video->views) }} مشاهدة</span>
                  <i class="bi bi-calendar3 ms-2"></i>
                  <span>{{ $video->published_at?->translatedFormat('Y') ?? 'يوتيوب' }}</span>
                </div>
              </div>
            </a>
          </div>
        @empty
          <div class="col-12 text-center py-5">
            <i class="bi bi-camera-video" style="font-size:3.5rem;color:var(--text3)"></i>
            <h4 class="mt-3" style="color:var(--text2)">لا توجد فيديوهات مطابقة</h4>
            <a href="{{ route('videos.index') }}" class="btn btn-outline-gold mt-3">عرض الكل</a>
          </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($videos->hasPages())
        <div class="mt-5 d-flex flex-column align-items-center gap-2">
          <nav aria-label="التنقل بين الصفحات">
            <ul class="pagination">
              {{-- Previous --}}
              @if($videos->onFirstPage())
                <li class="page-item disabled">
                  <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                </li>
              @else
                <li class="page-item">
                  <a class="page-link" href="{{ $videos->previousPageUrl() }}" aria-label="السابق">
                    <i class="bi bi-chevron-right"></i>
                  </a>
                </li>
              @endif

              {{-- Page Numbers — smart window --}}
              @php
                $currentPage = $videos->currentPage();
                $lastPage    = $videos->lastPage();
                $window      = 2; // pages each side
                $start       = max(1, $currentPage - $window);
                $end         = min($lastPage, $currentPage + $window);
              @endphp

              @if($start > 1)
                <li class="page-item">
                  <a class="page-link" href="{{ $videos->url(1) }}">1</a>
                </li>
                @if($start > 2)
                  <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
              @endif

              @for($p = $start; $p <= $end; $p++)
                <li class="page-item {{ $p === $currentPage ? 'active' : '' }}">
                  <a class="page-link" href="{{ $videos->url($p) }}">{{ $p }}</a>
                </li>
              @endfor

              @if($end < $lastPage)
                @if($end < $lastPage - 1)
                  <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                <li class="page-item">
                  <a class="page-link" href="{{ $videos->url($lastPage) }}">{{ $lastPage }}</a>
                </li>
              @endif

              {{-- Next --}}
              @if($videos->hasMorePages())
                <li class="page-item">
                  <a class="page-link" href="{{ $videos->nextPageUrl() }}" aria-label="التالي">
                    <i class="bi bi-chevron-left"></i>
                  </a>
                </li>
              @else
                <li class="page-item disabled">
                  <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                </li>
              @endif
            </ul>
          </nav>
          <div class="pagination-info">
            عرض {{ $videos->firstItem() }}–{{ $videos->lastItem() }} من أصل {{ $videos->total() }} فيديو
          </div>
        </div>
      @endif

    </div>
  </section>
</main>
@endsection

@push('scripts')
<script>
function applySort(val) {
  const url = new URL(window.location.href);
  url.searchParams.set('sort', val);
  url.searchParams.delete('page'); // reset to page 1
  window.location.href = url.toString();
}
</script>
@endpush
