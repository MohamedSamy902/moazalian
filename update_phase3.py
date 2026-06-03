import os
import re

base_dir = '/home/sharo/Desktop/mo3az3lian'

# ----------------- 1. UPDATE about.html (Timeline) -----------------
with open(os.path.join(base_dir, 'about.html'), 'r', encoding='utf-8') as f:
    about_content = f.read()

timeline_old_start = r'<div class="d-flex flex-column gap-0".*?padding-right:1\.4rem">'
# We will just replace the whole col-lg-5 section.
col_lg_5_pattern = r'<div class="col-lg-5 reveal">.*?</div>\s*</div>\s*</div>\s*</section>'

new_timeline = """<div class="col-lg-5 reveal">
          <div style="font-size:.82rem;color:var(--gold);text-transform:uppercase;letter-spacing:.1em;margin-bottom:1.2rem;font-weight:700;text-align:center;">
            <i class="bi bi-clock-history me-1"></i>المسيرة الزمنية
          </div>
          <div class="timeline-container">
            <div class="timeline-line"></div>
            
            <div class="timeline-item from-right">
              <div class="timeline-content">
                <div class="time-year">2004</div>
                <div class="time-title">بداية المسيرة</div>
                <div class="time-desc">دخل مجال مقارنة الأديان والدعوة لغير المسلمين عبر البالتوك</div>
              </div>
            </div>
            
            <div class="timeline-item from-left">
              <div class="timeline-content">
                <div class="time-year">2009</div>
                <div class="time-title">أول كتاب مطبوع</div>
                <div class="time-desc">كتاب "عبادة مريم في المسيحية" مع أخيه محمود عليان</div>
              </div>
            </div>
            
            <div class="timeline-item from-right">
              <div class="timeline-content">
                <div class="time-year">2009</div>
                <div class="time-title">تأسيس قناة المُخلَّص</div>
                <div class="time-desc">أول قناة للرد على المشككين والمنصرين ومقارنة الأديان</div>
              </div>
            </div>
            
            <div class="timeline-item from-left">
              <div class="timeline-content">
                <div class="time-year">2015+</div>
                <div class="time-title">التوسع والانتشار</div>
                <div class="time-desc">تأسيس منصات متعددة ونشر عشرات الكتب والردود</div>
              </div>
            </div>

            <div class="timeline-item from-right">
              <div class="timeline-content">
                <div class="time-year" style="color:var(--green)">الآن</div>
                <div class="time-title">قناة معاذ عليان</div>
                <div class="time-desc">حوار إسلامي مسيحي ونقد كتابي ورد شبهات وقضايا فكرية</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>"""

about_content = re.sub(col_lg_5_pattern, new_timeline, about_content, flags=re.DOTALL)
with open(os.path.join(base_dir, 'about.html'), 'w', encoding='utf-8') as f:
    f.write(about_content)


# ----------------- 2. UPDATE references.html (Filter System) -----------------
with open(os.path.join(base_dir, 'references.html'), 'r', encoding='utf-8') as f:
    ref_content = f.read()

ref_section_old = r'<div class="ref-tabs reveal">.*?</section>'
ref_section_new = """<!-- References Filter System -->
      <div class="ref-filter-container reveal mb-5">
        <div class="search-wrap mx-auto" style="max-width: 600px;">
          <i class="bi bi-search text-gold ms-2"></i>
          <input type="text" id="ref-search" placeholder="ابحث في المراجع بالاسم، الكاتب أو الوصف...">
        </div>
        <div class="ref-chips d-flex justify-content-center flex-wrap gap-2 mt-3">
          <button class="ref-chip active" data-filter="all">الكل</button>
          <button class="ref-chip" data-filter="كتاب"><i class="bi bi-book"></i> كتاب</button>
          <button class="ref-chip" data-filter="مخطوطة"><i class="bi bi-file-earmark-text"></i> مخطوطة</button>
          <button class="ref-chip" data-filter="فيديو"><i class="bi bi-camera-video"></i> فيديو</button>
          <button class="ref-chip" data-filter="صورة"><i class="bi bi-image"></i> صورة</button>
        </div>
      </div>

      <div class="row g-4" id="ref-grid">
        <!-- Reference Card 1 -->
        <div class="col-md-6 col-lg-4 ref-card-item" data-type="كتاب">
          <div class="card-x p-3 h-100 ref-card d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge-x badge-gold"><i class="bi bi-book"></i> كتاب</span>
            </div>
            <h5 class="ref-title mt-2">Misquoting Jesus</h5>
            <div style="font-size: 0.8rem; color: var(--text3);">Bart Ehrman • 2005</div>
            <hr class="my-2" style="border-color: var(--border);">
            <p class="ref-desc mb-0 flex-grow-1" style="font-size: 0.85rem; color: var(--text2);">دراسة نقدية نصية من عالم مسيحي سابق تُثبت تحريف النص الكتابي</p>
            <div class="ref-hover-action mt-3">
              <a href="#" class="btn btn-sm btn-outline-gold w-100"><i class="bi bi-download"></i> تحميل PDF</a>
            </div>
          </div>
        </div>

        <!-- Reference Card 2 -->
        <div class="col-md-6 col-lg-4 ref-card-item" data-type="مخطوطة">
          <div class="card-x p-3 h-100 ref-card d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge-x badge-teal"><i class="bi bi-file-earmark-text"></i> مخطوطة</span>
            </div>
            <h5 class="ref-title mt-2">Codex Sinaiticus</h5>
            <div style="font-size: 0.8rem; color: var(--text3);">القرن الرابع</div>
            <hr class="my-2" style="border-color: var(--border);">
            <p class="ref-desc mb-0 flex-grow-1" style="font-size: 0.85rem; color: var(--text2);">أقدم نسخة كاملة من الكتاب المقدس، متاحة رقمياً.</p>
            <div class="ref-hover-action mt-3">
              <a href="#" class="btn btn-sm btn-outline-gold w-100"><i class="bi bi-eye"></i> عرض التفاصيل</a>
            </div>
          </div>
        </div>
        
        <!-- Reference Card 3 -->
        <div class="col-md-6 col-lg-4 ref-card-item" data-type="فيديو">
          <div class="card-x p-3 h-100 ref-card d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge-x badge-red"><i class="bi bi-camera-video"></i> فيديو</span>
            </div>
            <h5 class="ref-title mt-2">قسيس يعترف بالتحريف</h5>
            <div style="font-size: 0.8rem; color: var(--text3);">تسجيل موثق • 2018</div>
            <hr class="my-2" style="border-color: var(--border);">
            <p class="ref-desc mb-0 flex-grow-1" style="font-size: 0.85rem; color: var(--text2);">مقطع توثيقي من مصدرهم يستشهد به في المناظرات</p>
            <div class="ref-hover-action mt-3">
              <a href="#" class="btn btn-sm btn-outline-gold w-100"><i class="bi bi-play-circle"></i> مشاهدة</a>
            </div>
          </div>
        </div>
        
        <!-- Reference Card 4 -->
        <div class="col-md-6 col-lg-4 ref-card-item" data-type="صورة">
          <div class="card-x p-3 h-100 ref-card d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge-x badge-blue"><i class="bi bi-image"></i> صورة</span>
            </div>
            <h5 class="ref-title mt-2">مخطوطة الفاتيكان - يوحنا 1</h5>
            <div style="font-size: 0.8rem; color: var(--text3);">مكتبة الفاتيكان</div>
            <hr class="my-2" style="border-color: var(--border);">
            <p class="ref-desc mb-0 flex-grow-1" style="font-size: 0.85rem; color: var(--text2);">صورة واضحة للنص اليوناني الأصلي</p>
            <div class="ref-hover-action mt-3">
              <a href="#" class="btn btn-sm btn-outline-gold w-100"><i class="bi bi-zoom-in"></i> تكبير الصورة</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>"""
ref_content = re.sub(ref_section_old, ref_section_new, ref_content, flags=re.DOTALL)
with open(os.path.join(base_dir, 'references.html'), 'w', encoding='utf-8') as f:
    f.write(ref_content)


# ----------------- 3. UPDATE articles.html (Smart Reader features) -----------------
with open(os.path.join(base_dir, 'articles.html'), 'r', encoding='utf-8') as f:
    art_content = f.read()

# Add Progress Bar & Article View to articles.html
progress_html = """<div id="reading-progress"></div>
<header>"""
if 'reading-progress' not in art_content:
    art_content = art_content.replace('<header>', progress_html)

# Add sample article viewer hidden inside main
article_viewer = """
  <!-- ══════════════════ SMART ARTICLE READER ══════════════════ -->
  <div class="container py-5" id="article-reader" style="display:none; max-width: 800px; position: relative;">
    <div class="article-toolbar sticky-top">
      <button class="btn btn-sm btn-outline-gold" onclick="closeArticle()"><i class="bi bi-arrow-right"></i> عودة</button>
      <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-gold" onclick="changeFontSize(1)" aria-label="تكبير الخط">A+</button>
        <button class="btn btn-sm btn-outline-gold" onclick="changeFontSize(-1)" aria-label="تصغير الخط">A-</button>
        <button class="btn btn-sm btn-outline-gold" onclick="toggleArticleBg()" aria-label="تغيير لون الخلفية"><i class="bi bi-palette"></i></button>
      </div>
    </div>
    
    <div class="article-content mt-4" id="article-content-box">
      <h1 class="sec-title mb-3" style="font-family: 'Cairo', sans-serif;">هل الكتاب المقدس كلام الله؟ — دراسة في الأصالة والتحريف</h1>
      <div class="d-flex gap-3 mb-4 text-muted" style="font-size: 0.85rem;">
        <span><i class="bi bi-calendar3"></i> يناير 2025</span>
        <span class="read-time-est" data-words="1200"><i class="bi bi-clock"></i> <span>جاري الحساب...</span></span>
      </div>
      <div class="article-text" style="line-height: 2.2; font-size: 16px;">
        <p>يستعرض هذا المقال الأدلة الأكاديمية من المصادر المسيحية ذاتها التي تُثبت أن الكتاب المقدس الحالي مر بتحريفات جوهرية على مدى قرون. من أهم الشواهد التي يجب التوقف عندها هو ما ذكره علماء النقد النصي مثل بارت إيرمان.</p>
        <p>النصوص الأصلية فُقدت تماماً، وما لدينا اليوم هو نسخ من نسخ من نسخ تم التلاعب ببعضها لأغراض لاهوتية. إن مسألة الموثوقية التاريخية للنص تشكل تحدياً كبيراً أمام العقيدة التي تعتمد على العصمة اللفظية.</p>
        <p>نحن نعلم من خلال مقارنة المخطوطات المبكرة بالمتأخرة أن هناك إضافات متعمدة مثل قصة المرأة الزانية في إنجيل يوحنا ونهاية إنجيل مرقس. هذه الأمثلة ليست مجرد أخطاء نساخ بسيطة، بل هي تعديلات مقصودة.</p>
        <p>إن إدراك هذه الحقائق يُعيد تشكيل النظرة تجاه قدسية النصوص ويفتح الباب أمام حوار أكثر موضوعية ومبني على أسس علمية.</p>
      </div>
    </div>
  </div>
  
  <div id="quote-tooltip" class="quote-tooltip hidden">
    <button onclick="shareQuote('twitter')"><i class="bi bi-twitter-x"></i></button>
    <button onclick="shareQuote('facebook')"><i class="bi bi-facebook"></i></button>
    <button onclick="shareQuote('whatsapp')"><i class="bi bi-whatsapp"></i></button>
  </div>
"""
if 'article-reader' not in art_content:
    art_content = art_content.replace('<section id="articles" class="py-5">', article_viewer + '\n  <section id="articles" class="py-5">')
    
    # We will modify the first "اقرأ المقال" button to call openArticle()
    art_content = art_content.replace('href="#" class="btn btn-sm btn-outline-gold" style="font-size:.75rem;padding:.3rem .8rem">اقرأ المقال</a>', 'href="#" onclick="openArticle(event)" class="btn btn-sm btn-outline-gold" style="font-size:.75rem;padding:.3rem .8rem">اقرأ المقال</a>', 1)

with open(os.path.join(base_dir, 'articles.html'), 'w', encoding='utf-8') as f:
    f.write(art_content)


# ----------------- 4. UPDATE style.css (Phase 3 Styles) -----------------
css_append3 = """
/* ═══════════════ PHASE 3 STYLES ═══════════════ */

/* Reading Progress Bar */
#reading-progress {
  position: fixed; top: 0; left: 0; height: 3px; background: var(--gold);
  width: 0%; z-index: 9999; transition: width 0.1s ease;
}

/* Floating Toolbar */
.article-toolbar {
  display: flex; justify-content: space-between; align-items: center;
  background: var(--card2); border: 1px solid var(--border);
  padding: 10px 15px; border-radius: 12px; box-shadow: var(--shadow);
  margin-bottom: 20px; transition: background 0.3s, color 0.3s;
}

/* Article Background Modes */
.article-bg-warm { background: #FFF8E7 !important; color: #333 !important; }
.article-bg-warm .sec-title, .article-bg-warm .article-text { color: #333 !important; }
.article-bg-warm .article-toolbar { background: #FFF; border-color: #e5dfd5; }

/* Quote Tooltip */
.quote-tooltip {
  position: absolute; background: #222; border-radius: 8px;
  display: flex; gap: 8px; padding: 5px 10px; z-index: 1000;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3); transform: translate(-50%, -100%);
  transition: opacity 0.2s ease;
}
.quote-tooltip.hidden { opacity: 0; pointer-events: none; }
.quote-tooltip button {
  background: none; border: none; color: #fff; font-size: 1.1rem;
  cursor: pointer; padding: 5px; transition: color 0.2s;
}
.quote-tooltip button:hover { color: var(--gold); }
.quote-tooltip::after {
  content: ''; position: absolute; top: 100%; left: 50%;
  margin-left: -6px; border-width: 6px; border-style: solid;
  border-color: #222 transparent transparent transparent;
}

/* Timeline Interactive */
.timeline-container { position: relative; max-width: 100%; margin: 0 auto; padding: 20px 0; }
.timeline-line {
  position: absolute; top: 0; bottom: 0; width: 4px;
  background: var(--gold); left: 50%; transform: translateX(-50%);
}
.timeline-item {
  position: relative; width: 50%; padding: 20px 40px;
  opacity: 0; transition: all 0.6s ease;
}
.timeline-item.from-right { left: 50%; transform: translateX(40px); }
.timeline-item.from-left { left: 0; text-align: left; transform: translateX(-40px); }
.timeline-item.active { opacity: 1; transform: translateX(0); }

.timeline-content {
  background: var(--card); border: 1px solid var(--border);
  border-radius: var(--radius); padding: 20px;
  position: relative; box-shadow: var(--shadow);
}
.timeline-item.from-right .timeline-content::before {
  content: " "; position: absolute; top: 20px; left: -15px;
  border-width: 15px 15px 15px 0; border-style: solid;
  border-color: transparent var(--card) transparent transparent;
}
.timeline-item.from-left .timeline-content::before {
  content: " "; position: absolute; top: 20px; right: -15px;
  border-width: 15px 0 15px 15px; border-style: solid;
  border-color: transparent transparent transparent var(--card);
}
.time-year { font-weight: 700; color: var(--gold); font-size: 1.2rem; margin-bottom: 5px; }
.time-title { font-weight: 600; font-size: 1.05rem; color: var(--text); }
.time-desc { font-size: 0.85rem; color: var(--text2); margin-top: 5px; line-height: 1.6; }

@media(max-width: 767px){
  .timeline-line { left: 20px; }
  .timeline-item { width: 100%; padding-left: 50px; padding-right: 15px; text-align: right; }
  .timeline-item.from-right, .timeline-item.from-left { left: 0; transform: translateX(30px); }
  .timeline-item.from-left .timeline-content::before, .timeline-item.from-right .timeline-content::before {
    left: -15px; right: auto; border-width: 15px 15px 15px 0;
    border-color: transparent var(--card) transparent transparent;
  }
}

/* Reference Chips & Cards */
.ref-chip {
  background: var(--card2); border: 1px solid var(--border); color: var(--text2);
  padding: 6px 16px; border-radius: 30px; font-size: 0.85rem; cursor: pointer; transition: all 0.2s;
}
.ref-chip.active, .ref-chip:hover {
  background: rgba(196,153,58,0.15); color: var(--gold); border-color: var(--gold);
}
.ref-card { transition: all 0.3s; position: relative; overflow: hidden; }
.ref-card:hover { transform: translateY(-5px); border-color: var(--gold); }
.ref-hover-action {
  opacity: 0; transform: translateY(10px); transition: all 0.3s;
}
.ref-card:hover .ref-hover-action { opacity: 1; transform: translateY(0); }
.badge-blue { background: rgba(14,165,233,.15); color: var(--teal); border: 1px solid rgba(14,165,233,.3); }
"""

with open(os.path.join(base_dir, 'style.css'), 'r', encoding='utf-8') as f:
    css_content = f.read()
if 'Reading Progress Bar' not in css_content:
    with open(os.path.join(base_dir, 'style.css'), 'a', encoding='utf-8') as f:
        f.write(css_append3)

# ----------------- 5. UPDATE script.js (Phase 3 Logic) -----------------
js_append3 = """
/* ── Articles Reading Progress & Features ── */
window.addEventListener('scroll', () => {
  const progress = document.getElementById('reading-progress');
  if(progress) {
    const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrolled = (winScroll / height) * 100;
    progress.style.width = scrolled + "%";
  }
});

function openArticle(e) {
  e.preventDefault();
  document.getElementById('articles').style.display = 'none';
  const reader = document.getElementById('article-reader');
  reader.style.display = 'block';
  reader.scrollIntoView({ behavior: 'smooth' });
  
  // Calculate read time
  const timeEst = document.querySelector('.read-time-est');
  if(timeEst && timeEst.dataset.words) {
    const words = parseInt(timeEst.dataset.words);
    const mins = Math.max(1, Math.ceil(words / 200));
    timeEst.querySelector('span').textContent = mins + ' دقائق قراءة';
  }
}
function closeArticle() {
  document.getElementById('article-reader').style.display = 'none';
  document.getElementById('articles').style.display = 'block';
  document.getElementById('articles').scrollIntoView({ behavior: 'smooth' });
}

let articleFontSize = 16;
function changeFontSize(step) {
  const textEl = document.querySelector('.article-text');
  if(textEl) {
    articleFontSize += (step * 2);
    if(articleFontSize < 14) articleFontSize = 14;
    if(articleFontSize > 26) articleFontSize = 26;
    textEl.style.fontSize = articleFontSize + 'px';
  }
}

function toggleArticleBg() {
  const box = document.getElementById('article-content-box');
  if(box) {
    box.classList.toggle('article-bg-warm');
  }
}

/* ── Text Selection Tooltip ── */
const tooltip = document.getElementById('quote-tooltip');
if(tooltip) {
  document.addEventListener('selectionchange', () => {
    const selection = window.getSelection();
    if(selection.rangeCount > 0 && selection.toString().trim().length > 0) {
      const range = selection.getRangeAt(0);
      const rect = range.getBoundingClientRect();
      tooltip.style.top = (window.scrollY + rect.top - 10) + 'px';
      tooltip.style.left = (window.scrollX + rect.left + rect.width/2) + 'px';
      tooltip.classList.remove('hidden');
    } else {
      tooltip.classList.add('hidden');
    }
  });
}
function shareQuote(platform) {
  const text = window.getSelection().toString().trim();
  const url = encodeURIComponent(window.location.href);
  const encodedText = encodeURIComponent(text);
  let shareUrl = '';
  if(platform === 'twitter') shareUrl = `https://twitter.com/intent/tweet?text=${encodedText}&url=${url}`;
  if(platform === 'facebook') shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${encodedText}`;
  if(platform === 'whatsapp') shareUrl = `https://api.whatsapp.com/send?text=${encodedText} - ${url}`;
  window.open(shareUrl, '_blank', 'width=600,height=400');
}

/* ── Timeline Observer ── */
const timelineItems = document.querySelectorAll('.timeline-item');
if(timelineItems.length > 0) {
  const tlObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if(entry.isIntersecting) {
        entry.target.classList.add('active');
        tlObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.3 });
  timelineItems.forEach(item => tlObserver.observe(item));
}

/* ── References Filter System ── */
const refSearch = document.getElementById('ref-search');
const refChips = document.querySelectorAll('.ref-chip');
const refCards = document.querySelectorAll('.ref-card-item');

function filterReferences() {
  if(!refSearch) return;
  const query = refSearch.value.trim().toLowerCase();
  const activeChip = document.querySelector('.ref-chip.active').dataset.filter;
  
  refCards.forEach(card => {
    const title = card.querySelector('.ref-title').textContent.toLowerCase();
    const type = card.dataset.type;
    const desc = card.querySelector('.ref-desc').textContent.toLowerCase();
    
    const matchChip = (activeChip === 'all' || type === activeChip);
    const matchSearch = (!query || title.includes(query) || desc.includes(query));
    
    if(matchChip && matchSearch) {
      card.style.display = 'block';
      setTimeout(() => card.style.opacity = '1', 50);
    } else {
      card.style.opacity = '0';
      setTimeout(() => card.style.display = 'none', 300);
    }
  });
}

if(refSearch) {
  refSearch.addEventListener('input', filterReferences);
  refChips.forEach(chip => {
    chip.addEventListener('click', () => {
      refChips.forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      filterReferences();
    });
  });
}
"""

with open(os.path.join(base_dir, 'script.js'), 'r', encoding='utf-8') as f:
    js_content = f.read()
if 'Smart Article Reader' not in js_content:
    with open(os.path.join(base_dir, 'script.js'), 'a', encoding='utf-8') as f:
        f.write(js_append3)

print("Phase 3 update script configured.")
