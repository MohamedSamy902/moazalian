@extends('frontend.layouts.master')

@section('title', 'الدورات التعليمية | معاذ عليان')
@section('meta_description', 'دورات متخصصة في مقارنة الأديان والحوار الإسلامي المسيحي — معاذ عليان')

@section('content')
<main style="padding-top: 80px;">
  <!-- ══════════════════ COURSES ══════════════════ -->
  <section id="courses" class="py-5">
    <div class="container">
      <div class="sec-head reveal">
        <div class="sec-eyebrow"><i class="bi bi-mortarboard-fill"></i>التعلم</div>
        <h1 class="sec-title">الدورات التعليمية</h1>
        <p class="sec-sub">دورات متخصصة في مقارنة الأديان والحوار الإسلامي المسيحي</p>
      </div>

      {{-- Search --}}
      <form action="{{ route('courses.index') }}" method="GET" class="mb-4 reveal">
        <div class="search-wrap mx-auto" style="max-width:600px;display:flex;align-items:center">
          <i class="bi bi-search ms-2" style="color:var(--text3)"></i>
          <input type="text" name="search" placeholder="ابحث في الدورات..."
            value="{{ request('search') }}"
            style="border:none;outline:none;background:transparent;width:100%;color:var(--text)"
            onkeydown="if(event.key==='Enter'){this.form.submit()}"/>
        </div>
      </form>

      @php
        $coverGradients = [
          'linear-gradient(135deg,#1e3a5f,#0d2237)',
          'linear-gradient(135deg,#450a0a,#7f1d1d)',
          'linear-gradient(135deg,#164e63,#083344)',
          'linear-gradient(135deg,#14532d,#064e3b)',
          'linear-gradient(135deg,#3b0764,#1e1b4b)',
          'linear-gradient(135deg,#78350f,#451a03)',
        ];
        $coverIcons = ['bi-mortarboard-fill','bi-book','bi-file-earmark-text','bi-shield-check','bi-stars','bi-diagram-3'];
      @endphp

      <div class="row g-4">
        @forelse($courses as $index => $course)
        <div class="col-md-6 col-lg-4 reveal">
          <div class="card-x h-100 d-flex flex-column" style="overflow:hidden;border:1px solid var(--border);transition:transform .3s ease">
            {{-- Cover --}}
            <div style="height:180px;background:{{ $coverGradients[$index % count($coverGradients)] }};position:relative">
              @if($course->thumbnail)
                <img src="{{ asset($course->thumbnail) }}"
                  style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0"
                  alt="{{ $course->getTranslation('title', app()->getLocale()) }}">
              @else
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:3rem;color:rgba(255,255,255,.2)">
                  <i class="bi {{ $coverIcons[$index % count($coverIcons)] }}"></i>
                </div>
              @endif
              @if($course->is_featured ?? false)
                <span class="badge-x badge-teal" style="position:absolute;top:15px;left:15px">مميز</span>
              @endif
            </div>
            {{-- Body --}}
            <div class="p-4 d-flex flex-column flex-grow-1">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-warning" style="font-size:.8rem;font-weight:700">
                  <i class="bi bi-diagram-3 me-1"></i>دورة متخصصة
                </span>
                <span style="font-size:.75rem;color:var(--text3)">
                  <i class="bi bi-person-video3 me-1"></i>
                  {{ $course->lessons_count ?? 0 }} درس
                </span>
              </div>
              <h3 style="font-size:1.2rem;color:var(--text);font-weight:800;margin-bottom:.8rem">
                {{ $course->getTranslation('title', app()->getLocale()) }}
              </h3>
              <p style="color:var(--text2);font-size:.85rem;line-height:1.7;flex-grow:1">
                {{ \Illuminate\Support\Str::limit($course->getTranslation('description', app()->getLocale()), 100) }}
              </p>
              <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center" style="border-color:var(--border) !important">
                <div class="d-flex align-items-center gap-2">
                  <i class="bi bi-star-fill text-warning" style="font-size:.8rem"></i>
                  <span style="font-size:.85rem;color:var(--text2);font-weight:700">مجاني</span>
                </div>
                <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-sm btn-outline-gold px-3 rounded-pill">
                  ابدأ الآن
                </a>
              </div>
            </div>
          </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
          <i class="bi bi-mortarboard" style="font-size:3.5rem;color:var(--text3)"></i>
          <h4 class="mt-3" style="color:var(--text2)">لا توجد دورات حالياً</h4>
        </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($courses->hasPages())
      <div class="d-flex justify-content-center mt-5">
        {{ $courses->links('pagination::bootstrap-5') }}
      </div>
      @endif
    </div>
  </section>
</main>
@endsection
