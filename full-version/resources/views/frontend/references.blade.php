@extends('frontend.layouts.master')

@section('title', 'المراجع والأدلة | معاذ عليان')
@section('meta_description', 'يستدل معاذ دائماً من مصادرهم الأصلية — كتب ومخطوطات وصور وفيديوهات')

@section('content')
<main style="padding-top: 80px;">
  <!-- ══════════════════ REFERENCES ══════════════════ -->
  <section id="refs" class="py-5 bg2">
    <div class="container">
      <div class="sec-head reveal">
        <div class="sec-eyebrow"><i class="bi bi-journals"></i>المصادر</div>
        <h1 class="sec-title">المراجع والأدلة</h1>
        <p class="sec-sub">يستدل معاذ دائماً من مصادرهم الأصلية — كتب ومخطوطات وصور وفيديوهات</p>
      </div>

      <!-- Search & Filter -->
      <div class="refs-search-bar reveal">
        <div class="search-wrap" style="max-width:100%;margin:0 0 1.5rem">
          <i class="bi bi-search text-gold"></i>
          <input type="text" id="refs-search" placeholder="ابحث في المراجع..." class="refs-search-input">
          <span id="refs-count" style="font-size:.78rem;color:var(--text3);white-space:nowrap"></span>
        </div>
        <div class="refs-chips d-flex gap-2 flex-wrap mb-4">
          <button class="ref-chip active" data-filter="all" id="chip-all">الكل</button>
          <button class="ref-chip" data-filter="book" id="chip-book"><i class="bi bi-book-fill me-1"></i>كتب</button>
          <button class="ref-chip" data-filter="manuscript" id="chip-manuscript"><i class="bi bi-file-earmark-text me-1"></i>مخطوطات</button>
          <button class="ref-chip" data-filter="video" id="chip-video"><i class="bi bi-camera-video-fill me-1"></i>فيديو</button>
          <button class="ref-chip" data-filter="image" id="chip-image"><i class="bi bi-images me-1"></i>صور</button>
        </div>
      </div>

      <!-- Cards Grid -->
      <div class="row g-4" id="refs-grid">

        <div class="col-md-6 col-lg-4 ref-card-wrap reveal" data-type="book" data-title="Misquoting Jesus Bart Ehrman">
          <div class="ref-card-new card-x h-100">
            <div class="ref-card-top">
              <span class="ic ic-sm ic-gold"><i class="bi bi-book-fill"></i></span>
              <span class="badge-x badge-gold ref-type-chip">كتاب</span>
            </div>
            <div class="ref-card-body">
              <div class="ref-card-title">Misquoting Jesus</div>
              <div class="ref-card-meta">Bart D. Ehrman • ٢٠٠٥</div>
              <div class="ref-card-desc">دراسة نقدية نصية من عالم مسيحي سابق تُثبت تحريف النص الكتابي — مرجع أكاديمي أساسي</div>
            </div>
            <div class="ref-card-hover-btn"><a href="#" class="btn-dl"><i class="bi bi-download me-1"></i>PDF</a></div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 ref-card-wrap reveal" data-type="book" data-title="موسوعة الكتاب المقدس Baker">
          <div class="ref-card-new card-x h-100">
            <div class="ref-card-top">
              <span class="ic ic-sm ic-blue"><i class="bi bi-book-fill"></i></span>
              <span class="badge-x badge-teal ref-type-chip">كتاب</span>
            </div>
            <div class="ref-card-body">
              <div class="ref-card-title">موسوعة الكتاب المقدس</div>
              <div class="ref-card-meta">Baker Encyclopedia • ١٩٩٧</div>
              <div class="ref-card-desc">مرجع لاهوتي مسيحي أكاديمي يُستشهد به في النقد المقارن للنصوص الكتابية</div>
            </div>
            <div class="ref-card-hover-btn"><a href="#" class="btn-dl"><i class="bi bi-download me-1"></i>PDF</a></div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 ref-card-wrap reveal" data-type="book" data-title="Orthodox Corruption Scripture Ehrman">
          <div class="ref-card-new card-x h-100">
            <div class="ref-card-top">
              <span class="ic ic-sm ic-purple"><i class="bi bi-book-fill"></i></span>
              <span class="badge-x badge-gold ref-type-chip">كتاب</span>
            </div>
            <div class="ref-card-body">
              <div class="ref-card-title">The Orthodox Corruption of Scripture</div>
              <div class="ref-card-meta">Bart Ehrman • ١٩٩٣</div>
              <div class="ref-card-desc">كتاب Ehrman في التحريفات المقصودة التي أجراها الناسخون المسيحيون الأوائل</div>
            </div>
            <div class="ref-card-hover-btn"><a href="#" class="btn-dl"><i class="bi bi-download me-1"></i>PDF</a></div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 ref-card-wrap reveal" data-type="book" data-title="Documentary Hypothesis Wellhausen التوراة">
          <div class="ref-card-new card-x h-100">
            <div class="ref-card-top">
              <span class="ic ic-sm ic-orange"><i class="bi bi-book-fill"></i></span>
              <span class="badge-x badge-gold ref-type-chip">كتاب</span>
            </div>
            <div class="ref-card-body">
              <div class="ref-card-title">The Documentary Hypothesis</div>
              <div class="ref-card-meta">Julius Wellhausen • ١٨٧٨</div>
              <div class="ref-card-desc">نظرية كتابة التوراة بأقلام متعددة في أزمنة مختلفة — أساس النقد التاريخي للعهد القديم</div>
            </div>
            <div class="ref-card-hover-btn"><a href="#" class="btn-dl"><i class="bi bi-download me-1"></i>PDF</a></div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 ref-card-wrap reveal" data-type="manuscript" data-title="Codex Sinaiticus مخطوطة سينائية">
          <div class="ref-card-new card-x h-100">
            <div class="ref-card-top">
              <span class="ic ic-sm ic-green"><i class="bi bi-file-earmark-text-fill"></i></span>
              <span class="badge-x badge-green ref-type-chip">مخطوطة</span>
            </div>
            <div class="ref-card-body">
              <div class="ref-card-title">Codex Sinaiticus</div>
              <div class="ref-card-meta">القرن الرابع الميلادي</div>
              <div class="ref-card-desc">أقدم نسخة كاملة من الكتاب المقدس — متاحة رقمياً على sinaiticus.org مع كل الاختلافات النصية</div>
            </div>
            <div class="ref-card-hover-btn">
              <a href="https://codexsinaiticus.org" target="_blank" class="btn-dl"><i class="bi bi-box-arrow-up-left me-1"></i>مشاهدة</a>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 ref-card-wrap reveal" data-type="manuscript" data-title="Codex Vaticanus مخطوطة فاتيكان">
          <div class="ref-card-new card-x h-100">
            <div class="ref-card-top">
              <span class="ic ic-sm ic-teal"><i class="bi bi-file-earmark-text-fill"></i></span>
              <span class="badge-x badge-green ref-type-chip">مخطوطة</span>
            </div>
            <div class="ref-card-body">
              <div class="ref-card-title">Codex Vaticanus</div>
              <div class="ref-card-meta">القرن الرابع الميلادي</div>
              <div class="ref-card-desc">المخطوطة الفاتيكانية — من أهم المخطوطات اليونانية للعهد الجديد المحفوظة في الفاتيكان</div>
            </div>
            <div class="ref-card-hover-btn">
              <a href="#" class="btn-dl"><i class="bi bi-box-arrow-up-left me-1"></i>مشاهدة</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</main>
@endsection

@push('scripts')
<script>
// References filter & search
const chips   = document.querySelectorAll('.ref-chip');
const cards   = document.querySelectorAll('.ref-card-wrap');
const search  = document.getElementById('refs-search');
const counter = document.getElementById('refs-count');

function filterRefs() {
  const active  = document.querySelector('.ref-chip.active')?.dataset.filter ?? 'all';
  const term    = search.value.toLowerCase().trim();
  let   visible = 0;
  cards.forEach(c => {
    const typeMatch  = active === 'all' || c.dataset.type === active;
    const titleMatch = !term || c.dataset.title?.toLowerCase().includes(term);
    const show = typeMatch && titleMatch;
    c.style.display = show ? '' : 'none';
    if (show) visible++;
  });
  counter.textContent = `(${visible} مرجع)`;
}

chips.forEach(b => b.addEventListener('click', () => {
  chips.forEach(x => x.classList.remove('active'));
  b.classList.add('active');
  filterRefs();
}));
search?.addEventListener('input', filterRefs);
filterRefs();
</script>
@endpush
