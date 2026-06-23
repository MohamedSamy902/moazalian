@extends('frontend.layouts.master')

@section('title', 'الكتب PDF | مكتبة معاذ عليان')
@section('meta_description', 'مؤلفات معاذ عليان مجانية للتحميل في مقارنة الأديان ونقد الكتاب المقدس')

@section('content')
<main style="padding-top: 80px;">
  <section id="books" class="py-5 bg2">
    <div class="container">
      <div class="sec-head reveal">
        <div class="sec-eyebrow"><i class="bi bi-file-earmark-pdf-fill"></i>المكتبة</div>
        <h1 class="sec-title">الكتب PDF</h1>
        <p class="sec-sub">مؤلفات معاذ عليان مجانية للتحميل — {{ $books->total() }} كتاب</p>
      </div>

      {{-- Search --}}
      <form action="{{ route('books.index') }}" method="GET" class="mb-4 reveal">
        <div class="search-wrap mx-auto" style="max-width:600px;display:flex;align-items:center">
          <i class="bi bi-search ms-2" style="color:var(--text3)"></i>
          <input type="text" name="search" placeholder="ابحث في الكتب..."
            value="{{ request('search') }}"
            style="border:none;outline:none;background:transparent;width:100%;color:var(--text)"
            onkeydown="if(event.key==='Enter'){this.form.submit()}"/>
        </div>
      </form>

      @php
        $gradients = [
          'linear-gradient(145deg,#1a2035,#0d1525)',
          'linear-gradient(145deg,#201a35,#150d25)',
          'linear-gradient(145deg,#1a2820,#0d1e15)',
          'linear-gradient(145deg,#281a1a,#1e0d0d)',
          'linear-gradient(145deg,#1a1a28,#0d0d1e)',
          'linear-gradient(145deg,#28201a,#1e150d)',
        ];
        $icons = ['bi-book-half','bi-journal-text','bi-file-earmark-richtext','bi-book-fill','bi-journals','bi-file-earmark-text'];
      @endphp

      <div class="row g-4">
        @forelse($books as $index => $book)
        @php
          $locale  = app()->getLocale();
          $pdfUrl  = null;
          if ($book->pdf_path) {
            $pdfUrl = str_starts_with($book->pdf_path, 'http')
              ? $book->pdf_path
              : asset('storage/' . $book->pdf_path);
          }
          $grad = $gradients[$index % count($gradients)];
          $icon = $icons[$index % count($icons)];
        @endphp
        <div class="col-sm-6 col-lg-3 reveal">
          <div class="book-card">
            <div class="book-ribbon">PDF</div>
            <div class="book-cover" style="background:{{ $grad }}">
              @if($book->cover_image)
                <img src="{{ asset($book->cover_image) }}"
                  style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;border-radius:inherit"
                  alt="{{ $book->getTranslation('title', $locale) }}">
              @else
                <i class="bi {{ $icon }} book-cover-icon"></i>
              @endif
            </div>
            <div class="book-body">
              <div class="book-title">{{ $book->getTranslation('title', $locale) }}</div>
              @if($book->getTranslation('author', $locale))
                <div class="book-author"><i class="bi bi-person me-1"></i>{{ $book->getTranslation('author', $locale) }}</div>
              @endif
              @if($book->year || $book->category)
                <div class="book-meta">{{ $book->year ? $book->year . ' — ' : '' }}{{ $book->category?->label() }}</div>
              @endif
              @if($pdfUrl)
                <div class="d-flex gap-1 mt-auto">
                  <a href="{{ $pdfUrl }}" target="_blank" class="btn-dl flex-fill text-center">
                    <i class="bi bi-eye"></i> قراءة
                  </a>
                  <a href="{{ $pdfUrl }}" download class="btn-dl" style="background:rgba(196,153,58,.08);padding:.45rem .7rem">
                    <i class="bi bi-download"></i>
                  </a>
                </div>
              @else
                <span class="btn-dl" style="opacity:.5;cursor:not-allowed">
                  <i class="bi bi-hourglass"></i>قريباً
                </span>
              @endif
            </div>
          </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
          <i class="bi bi-book" style="font-size:3.5rem;color:var(--text3)"></i>
          <h4 class="mt-3" style="color:var(--text2)">لا توجد كتب حالياً</h4>
        </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($books->hasPages())
      <div class="mt-5 d-flex flex-column align-items-center gap-2">
        <nav aria-label="صفحات الكتب">
          <ul class="pagination">
            @if($books->onFirstPage())
              <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right"></i></span></li>
            @else
              <li class="page-item"><a class="page-link" href="{{ $books->previousPageUrl() }}"><i class="bi bi-chevron-right"></i></a></li>
            @endif

            @php
              $cp = $books->currentPage();
              $lp = $books->lastPage();
              $s  = max(1, $cp - 2);
              $e  = min($lp, $cp + 2);
            @endphp

            @if($s > 1)
              <li class="page-item"><a class="page-link" href="{{ $books->url(1) }}">1</a></li>
              @if($s > 2)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
            @endif

            @for($p = $s; $p <= $e; $p++)
              <li class="page-item {{ $p === $cp ? 'active' : '' }}">
                <a class="page-link" href="{{ $books->url($p) }}">{{ $p }}</a>
              </li>
            @endfor

            @if($e < $lp)
              @if($e < $lp - 1)<li class="page-item disabled"><span class="page-link">...</span></li>@endif
              <li class="page-item"><a class="page-link" href="{{ $books->url($lp) }}">{{ $lp }}</a></li>
            @endif

            @if($books->hasMorePages())
              <li class="page-item"><a class="page-link" href="{{ $books->nextPageUrl() }}"><i class="bi bi-chevron-left"></i></a></li>
            @else
              <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left"></i></span></li>
            @endif
          </ul>
        </nav>
        <div class="pagination-info">عرض {{ $books->firstItem() }}–{{ $books->lastItem() }} من {{ $books->total() }} كتاب</div>
      </div>
      @endif

    </div>
  </section>
</main>
@endsection
