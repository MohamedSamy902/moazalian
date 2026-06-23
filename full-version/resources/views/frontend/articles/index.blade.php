@extends('frontend.layouts.master')

@section('title', 'المقالات | معاذ عليان')
@section('meta_description', 'دراسات وتحليلات مكتوبة في مقارنة الأديان والنقد الكتابي — معاذ عليان')

@section('content')
<main style="padding-top: 80px;">
  <section id="articles" class="py-5">
    <div class="container">
      <div class="sec-head reveal">
        <div class="sec-eyebrow"><i class="bi bi-newspaper"></i>القراءة</div>
        <h1 class="sec-title">المقالات والبحوث</h1>
        <p class="sec-sub">دراسات وبحوث مكتوبة في مقارنة الأديان والنقد الكتابي — {{ $articles->total() }} بحث</p>
      </div>

      {{-- Search & Filter --}}
      <form action="{{ route('articles.index') }}" method="GET" class="mb-4 reveal">
        <div class="search-wrap mx-auto" style="max-width:600px;display:flex;align-items:center">
          <i class="bi bi-search ms-2" style="color:var(--text3)"></i>
          <input type="text" name="search" placeholder="ابحث في المقالات..."
            value="{{ request('search') }}"
            style="border:none;outline:none;background:transparent;width:100%;color:var(--text)"
            onkeydown="if(event.key==='Enter'){this.form.submit()}"/>
        </div>
      </form>

      <div class="row g-4">
        @forelse($articles as $article)
        @php
          $locale      = app()->getLocale();
          $excerptData = json_decode($article->getRawOriginal('excerpt'), true) ?? [];
          $pdfPath     = $excerptData['pdf_path'] ?? null;
          $pdfUrl      = $pdfPath ? asset('storage/' . $pdfPath) : null;
          $excerpt     = $pdfPath ? '' : ($excerptData[$locale] ?? $excerptData['ar'] ?? '');
          $title       = $article->getTranslation('title', $locale);
        @endphp
        <div class="col-md-6 col-lg-4 reveal">
          <div class="art-card">
            <div class="art-img" style="background:linear-gradient(135deg,#0d1a2d,#1a0d2d)">
              @if($pdfUrl)
                <i class="art-img-icon bi bi-file-earmark-pdf-fill" style="color:var(--gold)"></i>
              @else
                <i class="art-img-icon bi bi-book-half"></i>
              @endif
              <div class="art-img-overlay"></div>
            </div>
            <div class="art-body">
              <span class="art-cat"><i class="bi bi-tag-fill me-1"></i>{{ $article->category?->label() ?? 'مقال' }}</span>
              <div class="art-title">{{ \Illuminate\Support\Str::limit($title, 80) }}</div>
              @if($excerpt)
                <div class="art-excerpt">{{ \Illuminate\Support\Str::limit($excerpt, 100) }}</div>
              @else
                <div class="art-excerpt" style="color:var(--text3);font-size:.78rem">
                  <i class="bi bi-file-earmark-pdf me-1" style="color:var(--gold)"></i>بحث بصيغة PDF
                </div>
              @endif
              <div class="art-footer">
                <div class="art-date"><i class="bi bi-calendar3"></i>{{ $article->published_at?->translatedFormat('Y') }}</div>
                @if($pdfUrl)
                  <div class="d-flex gap-1">
                    <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-sm btn-outline-gold" style="font-size:.72rem;padding:.25rem .7rem">
                      <i class="bi bi-eye me-1"></i>قراءة
                    </a>
                    <a href="{{ $pdfUrl }}" download class="btn btn-sm" style="font-size:.72rem;padding:.25rem .7rem;background:rgba(196,153,58,.1);border:1px solid var(--border);color:var(--gold);border-radius:6px">
                      <i class="bi bi-download"></i>
                    </a>
                  </div>
                @else
                  <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-outline-gold" style="font-size:.75rem;padding:.3rem .8rem">اقرأ المقال</a>
                @endif
              </div>
            </div>
          </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
          <i class="bi bi-newspaper" style="font-size:3.5rem;color:var(--text3)"></i>
          <h4 class="mt-3" style="color:var(--text2)">لا توجد مقالات حالياً</h4>
          <a href="{{ route('articles.index') }}" class="btn btn-outline-gold mt-3">عرض الكل</a>
        </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($articles->hasPages())
      <div class="mt-5 d-flex flex-column align-items-center gap-2">
        <nav aria-label="صفحات المقالات">
          <ul class="pagination">
            @if($articles->onFirstPage())
              <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right"></i></span></li>
            @else
              <li class="page-item"><a class="page-link" href="{{ $articles->previousPageUrl() }}"><i class="bi bi-chevron-right"></i></a></li>
            @endif

            @php
              $cp = $articles->currentPage();
              $lp = $articles->lastPage();
              $s  = max(1, $cp - 2);
              $e  = min($lp, $cp + 2);
            @endphp

            @if($s > 1)
              <li class="page-item"><a class="page-link" href="{{ $articles->url(1) }}">1</a></li>
              @if($s > 2)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
            @endif

            @for($p = $s; $p <= $e; $p++)
              <li class="page-item {{ $p === $cp ? 'active' : '' }}">
                <a class="page-link" href="{{ $articles->url($p) }}">{{ $p }}</a>
              </li>
            @endfor

            @if($e < $lp)
              @if($e < $lp - 1)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
              <li class="page-item"><a class="page-link" href="{{ $articles->url($lp) }}">{{ $lp }}</a></li>
            @endif

            @if($articles->hasMorePages())
              <li class="page-item"><a class="page-link" href="{{ $articles->nextPageUrl() }}"><i class="bi bi-chevron-left"></i></a></li>
            @else
              <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left"></i></span></li>
            @endif
          </ul>
        </nav>
        <div class="pagination-info">عرض {{ $articles->firstItem() }}–{{ $articles->lastItem() }} من {{ $articles->total() }} مقال</div>
      </div>
      @endif

    </div>
  </section>
</main>
@endsection
