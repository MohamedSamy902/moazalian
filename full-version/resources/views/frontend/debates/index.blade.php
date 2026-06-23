{{--
  ╔══════════════════════════════════════════════════════════╗
  ║  [DISABLED] صفحة المناظرات — معطلة مؤقتاً               ║
  ║  المناظرات متوفرة ضمن الفيديوهات على /videos             ║
  ║  لإعادة التفعيل: uncomment routes في frontend.php        ║
  ╚══════════════════════════════════════════════════════════╝
--}}
@extends('frontend.layouts.master')

@section('title', 'المناظرات | معاذ عليان')
@section('meta_description', 'حوارات علمية موثقة مع خصوم فكريين من مختلف الأطياف — معاذ عليان')

@section('content')
<main style="padding-top: 80px;">
  <!-- ══════════════════ DEBATES ══════════════════ -->
  <section id="debates" class="py-5">
    <div class="container">
      <div class="sec-head reveal">
        <div class="sec-eyebrow"><i class="bi bi-people-fill"></i>الحوارات</div>
        <h1 class="sec-title">المناظرات</h1>
        <p class="sec-sub">حوارات علمية موثقة مع خصوم فكريين من مختلف الأطياف</p>
      </div>

      {{-- Search --}}
      <form action="{{ route('debates.index') }}" method="GET" class="mb-4 reveal">
        <div class="search-wrap mx-auto" style="max-width:600px;display:flex;align-items:center">
          <i class="bi bi-search ms-2" style="color:var(--text3)"></i>
          <input type="text" name="search" placeholder="ابحث في المناظرات..."
            value="{{ request('search') }}"
            style="border:none;outline:none;background:transparent;width:100%;color:var(--text)"
            onkeydown="if(event.key==='Enter'){this.form.submit()}"/>
        </div>
      </form>

      <div class="d-flex flex-column gap-3">
        @forelse($debates as $index => $debate)
        <div class="debate-item reveal">
          <div class="debate-n">{{ str_pad($debates->firstItem() + $loop->index, 2, '0', STR_PAD_LEFT) }}</div>
          <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
              @if($debate->is_featured)
                <span class="badge-x badge-red"><i class="bi bi-star-fill"></i>مميزة</span>
              @else
                <span class="badge-x badge-teal"><i class="bi bi-arrow-left-right"></i>حوار علمي</span>
              @endif
            </div>
            <div class="debate-title">{{ $debate->getTranslation('title', app()->getLocale()) }}</div>
            <div class="debate-meta">
              @if($debate->created_at)
                <span class="badge-x badge-gold" style="font-size:.68rem"><i class="bi bi-calendar3"></i>{{ $debate->created_at->format('Y') }}</span>
              @endif
              @if($debate->duration)
                <span class="badge-x badge-green" style="font-size:.68rem"><i class="bi bi-clock"></i>{{ $debate->duration }}</span>
              @endif
              @if($debate->youtube_url)
                <span class="badge-x badge-green" style="font-size:.68rem"><i class="bi bi-camera-video-fill"></i>فيديو مسجل</span>
              @endif
            </div>
          </div>
          @if($debate->youtube_url)
          <a href="{{ $debate->youtube_url }}" target="_blank" class="btn btn-sm btn-outline-gold align-self-center d-flex align-items-center gap-1">
            <i class="bi bi-play-fill"></i><span class="d-none d-sm-inline">شاهد</span>
          </a>
          @endif
        </div>
        @empty
        <div class="text-center py-5">
          <i class="bi bi-mic-mute" style="font-size:3.5rem;color:var(--text3)"></i>
          <h4 class="mt-3" style="color:var(--text2)">لا توجد مناظرات حالياً</h4>
        </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($debates->hasPages())
      <div class="d-flex justify-content-center mt-5">
        {{ $debates->links('pagination::bootstrap-5') }}
      </div>
      @endif
    </div>
  </section>
</main>
@endsection
