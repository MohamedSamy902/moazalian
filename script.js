/* ── Navbar scroll ── */
const nav = document.getElementById('nav');
if (nav) {
  window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 60), {passive:true});
}

/* ── Reveal on scroll ── */
const ro = new IntersectionObserver(entries => {
  entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('show'); ro.unobserve(e.target); } });
}, {threshold:.1});
document.querySelectorAll('.reveal').forEach(el => ro.observe(el));

/* ── Active nav highlight (for anchor links if still used or to highlight current page) ── */
const currentPath = window.location.pathname.split('/').pop();
const navLinks = [...document.querySelectorAll('.nav-link')];

// Set active link based on filename
navLinks.forEach(l => {
  const href = l.getAttribute('href');
  if (href === currentPath || (currentPath === '' && href === 'index.html')) {
    l.classList.add('active-link');
  } else if (href && href.startsWith('#')) {
    // If it's a section on the same page, keep the old logic just in case
    const sections = [...document.querySelectorAll('section[id]')];
    window.addEventListener('scroll', () => {
      let cur = '';
      sections.forEach(s => { if(scrollY >= s.offsetTop - 110) cur = s.id; });
      l.classList.toggle('active-link', l.getAttribute('href') === '#'+cur);
    }, {passive:true});
  }
});

/* ── Video filter + search ── */
let activeFilter = 'all';
function setFilter(btn, cat){
  activeFilter = cat;
  document.querySelectorAll('.vid-filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  filterVids();
}
function filterVids(){
  const searchInput = document.getElementById('vid-search');
  if (!searchInput) return;
  const q = searchInput.value.trim().toLowerCase();
  document.querySelectorAll('.vid-item').forEach(item => {
    const cat = item.dataset.cat;
    const title = item.dataset.title;
    const matchCat = activeFilter === 'all' || cat === activeFilter;
    const matchQ = !q || title.includes(q);
    item.style.display = (matchCat && matchQ) ? '' : 'none';
  });
}

/* ── Toggle video detail ── */
function toggleDetail(btn, vidId){
  const detail = document.getElementById('detail-' + vidId);
  if(!detail) return;
  const open = detail.classList.toggle('open');
  const icon = btn.querySelector('i');
  icon.className = open ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
  btn.querySelector('span') && (btn.querySelector('span').textContent = open ? 'إخفاء التفاصيل' : 'الوصف والمراجع');
  if(open) detail.scrollIntoView({behavior:'smooth', block:'nearest'});
}

/* ── References tabs ── */
function switchRef(btn, paneId){
  document.querySelectorAll('.ref-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.ref-pane').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById(paneId).classList.add('active');
}

/* ── Image modal ── */
function openImgModal(src){
  document.getElementById('imgModal').classList.add('open');
  document.getElementById('modalImg').src = src || '';
  document.body.style.overflow = 'hidden';
}
function closeImgModal(){
  document.getElementById('imgModal').classList.remove('open');
  document.body.style.overflow = '';
}

/* ── Live status check ── */
(function checkLive(){
  const statusText = document.getElementById('live-status-text');
  const statusLabel = document.getElementById('live-status-label');
  const statusIcon = document.getElementById('live-indicator-icon');
  const hint = document.getElementById('live-hint');
  const iframe = document.getElementById('live-iframe');
  
  if(iframe && statusText){
    iframe.addEventListener('load', function(){
      statusText.textContent = 'راجع البث أعلاه — يظهر المحتوى إذا كان هناك بث مباشر';
      statusLabel.textContent = 'تحقق من البث أعلاه';
      statusLabel.style.color = 'var(--gold)';
      statusIcon.innerHTML = '<i class="bi bi-info-circle" style="color:var(--gold)"></i>';
      hint.textContent = 'إذا ظهر بث، فمعاذ على الهواء الآن 🔴';
    });
  }
})();

/* ── Smooth close navbar on mobile after click ── */
document.querySelectorAll('.nav-link').forEach(l => {
  l.addEventListener('click', () => {
    const tog = document.querySelector('.navbar-toggler');
    const nm = document.getElementById('nm');
    if(nm && nm.classList.contains('show') && tog) tog.click();
  });
});

/* ── Theme Toggle ── */
const themeToggleBtn = document.getElementById('theme-toggle');
if (themeToggleBtn) {
  themeToggleBtn.addEventListener('click', () => {
    const root = document.documentElement;
    const currentTheme = root.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    root.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
  });
}

/* Apply saved theme on all pages (backup for pages without inline script) */
(function(){
  const saved = localStorage.getItem('theme');
  if(saved && document.documentElement.getAttribute('data-theme') !== saved){
    document.documentElement.setAttribute('data-theme', saved);
  }
})();



/* ── Hero Search ── */
const heroSearch = document.getElementById('hero-search');
const searchResults = document.getElementById('hero-search-results');
if (heroSearch && searchResults) {
  const searchData = [
    { title: 'الثالوث في المسيحية — حوار ساخن مع خادم أرثوذكسي', type: 'فيديو', url: 'videos.html' },
    { title: 'مسيحية تعتنق الإسلام وتكشف أسراراً خطيرة', type: 'فيديو', url: 'videos.html' },
    { title: 'من كتب التوراة؟ دراسة نقدية موثقة', type: 'مقال', url: 'articles.html' },
    { title: 'هل الكتاب المقدس كلام الله؟ — دراسة في الأصالة والتحريف', type: 'مقال', url: 'articles.html' },
    { title: 'الثالوث في التاريخ — متى نشأت هذه العقيدة؟', type: 'مقال', url: 'articles.html' },
    { title: 'رد على شبهة «الإسلام انتشر بالسيف» — وثائق وأرقام', type: 'رد سريع', url: 'articles.html' },
    { title: 'مناظرة هل صلب المسيح؟', type: 'مناظرة', url: 'debates.html' },
    { title: 'الثالوث والتوحيد — معاذ عليان vs القس إبراهيم عزيز', type: 'مناظرة', url: 'debates.html' },
    { title: 'هل الإسلام دين العنف؟ — حوار مع قسيس أمريكي', type: 'مناظرة', url: 'debates.html' },
    { title: 'عبادة مريم في المسيحية', type: 'كتاب', url: 'books.html' },
    { title: 'هل الكتاب المقدس كلام الله؟', type: 'كتاب', url: 'books.html' },
    { title: 'من كتب الأناجيل؟', type: 'كتاب', url: 'books.html' },
    { title: 'نبوءات العهد القديم ومحمد ﷺ', type: 'كتاب', url: 'books.html' },
    { title: 'الثالوث بين التاريخ واللاهوت', type: 'كتاب', url: 'books.html' }
  ];
  
  heroSearch.addEventListener('input', (e) => {
    const query = e.target.value.trim();
    if (!query) {
      searchResults.classList.add('hidden');
      return;
    }
    const filtered = searchData.filter(item => item.title.includes(query));
    
    if (filtered.length > 0) {
      searchResults.innerHTML = filtered.slice(0, 6).map(item => `
        <a href="${item.url}" class="search-result-item">
          <span class="search-result-type">${item.type}</span>
          <span>${item.title}</span>
        </a>
      `).join('');
    } else {
      searchResults.innerHTML = `<div style="padding: 15px; text-align: center; color: var(--text2); font-size: 0.85rem;">لا توجد نتائج ل— "ستجد المحتوى في الصفحات الداخلية</div>`;
    }
    searchResults.classList.remove('hidden');
  });
  
  document.addEventListener('click', (e) => {
    if(!heroSearch.contains(e.target) && !searchResults.contains(e.target)){
      searchResults.classList.add('hidden');
    }
  });
}

/* ── CountUp Animation ── */
function animateCounter(el, target, duration) {
  let start = 0;
  const increment = target / (duration / 16);
  const suffix = el.dataset.suffix || '';

  const step = () => {
    start += increment;
    if (start < target) {
      el.textContent = Math.ceil(start) + '';
      requestAnimationFrame(step);
    } else {
      el.textContent = target + suffix;
    }
  };
  requestAnimationFrame(step);
}

const counterSection = document.getElementById('counter-section');
if (counterSection) {
  let countersRun = false;
  function runCounters() {
    if (countersRun) return;
    countersRun = true;
    document.querySelectorAll('.counter-val').forEach(el => {
      animateCounter(el, parseInt(el.dataset.target), 2000);
    });
  }
  // Observer for when users scroll to see it
  const counterObserver = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting) {
      runCounters();
      counterObserver.disconnect();
    }
  }, { threshold: 0.01 });
  counterObserver.observe(counterSection);
  // Direct trigger: script runs at end of body, so DOM is ready
  // Counter section is inside hero section (always visible on load)
  setTimeout(runCounters, 600);
}

/* ── Swiper Init ── */
document.addEventListener('DOMContentLoaded', () => {
  if (typeof Swiper !== 'undefined' && document.querySelector('.mySwiper')) {
    new Swiper(".mySwiper", {
      effect: "coverflow",
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: "auto",
      coverflowEffect: {
        rotate: 30,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: true,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      dir: 'rtl'
    });
  }
});

/* ══════════════════════════════════════
   PHASE 3 — Timeline Scroll Observer
   ══════════════════════════════════════ */
const timelineItems = document.querySelectorAll('.timeline-item');
if (timelineItems.length) {
  const timelineObs = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        // Stagger effect: delay each item slightly
        const items = [...document.querySelectorAll('.timeline-item')];
        const index = items.indexOf(entry.target);
        setTimeout(() => {
          entry.target.classList.add('active');
        }, index * 150);
        timelineObs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.2 });
  timelineItems.forEach(item => timelineObs.observe(item));
}

/* ══════════════════════════════════════
   PHASE 3 — Reading Progress Bar
   ══════════════════════════════════════ */
const readingProgress = document.getElementById('reading-progress');
if (readingProgress) {
  window.addEventListener('scroll', () => {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
    readingProgress.style.width = progress + '%';
  }, { passive: true });
}

/* ══════════════════════════════════════
   PHASE 3 — Font Size Toolbar
   ══════════════════════════════════════ */
const fontIncBtn = document.getElementById('font-increase');
const fontDecBtn = document.getElementById('font-decrease');
const bgToggleBtn = document.getElementById('bg-toggle');

if (fontIncBtn && fontDecBtn) {
  let fontSize = parseInt(localStorage.getItem('art-font-size') || '16');
  const articleSection = document.getElementById('articles');
  if (articleSection) articleSection.style.fontSize = fontSize + 'px';

  fontIncBtn.addEventListener('click', () => {
    if (fontSize < 22) {
      fontSize += 1;
      if (articleSection) articleSection.style.fontSize = fontSize + 'px';
      localStorage.setItem('art-font-size', fontSize);
    }
  });
  fontDecBtn.addEventListener('click', () => {
    if (fontSize > 14) {
      fontSize -= 1;
      if (articleSection) articleSection.style.fontSize = fontSize + 'px';
      localStorage.setItem('art-font-size', fontSize);
    }
  });
}

if (bgToggleBtn) {
  const bgStates = ['', '#FFF8E7', '#1a1a1a'];
  let bgIndex = 0;
  bgToggleBtn.addEventListener('click', () => {
    bgIndex = (bgIndex + 1) % bgStates.length;
    document.body.style.background = bgStates[bgIndex] || '';
  });
}

/* ══════════════════════════════════════
   PHASE 3 — Sharable Quote Tooltip
   ══════════════════════════════════════ */
const quoteTooltip = document.getElementById('quote-tooltip');
const shareTwitter = document.getElementById('share-twitter');
const shareFacebook = document.getElementById('share-facebook');
const shareWhatsapp = document.getElementById('share-whatsapp');

if (quoteTooltip) {
  document.addEventListener('mouseup', (e) => {
    const selection = window.getSelection();
    const selectedText = selection ? selection.toString().trim() : '';

    if (selectedText.length > 10) {
      const range = selection.getRangeAt(0);
      const rect = range.getBoundingClientRect();
      const encoded = encodeURIComponent(`"${selectedText}" — معاذ عليان`);
      const pageUrl = encodeURIComponent(window.location.href);

      shareTwitter.href = `https://twitter.com/intent/tweet?text=${encoded}&url=${pageUrl}`;
      shareFacebook.href = `https://www.facebook.com/sharer/sharer.php?u=${pageUrl}&quote=${encoded}`;
      shareWhatsapp.href = `https://wa.me/?text=${encoded}%20${pageUrl}`;

      // Position tooltip above selection
      quoteTooltip.style.display = 'flex';
      quoteTooltip.style.top = (rect.top + window.scrollY - 55) + 'px';
      quoteTooltip.style.left = Math.max(10, rect.left + rect.width / 2 - 120) + 'px';
    } else {
      if (!quoteTooltip.contains(e.target)) {
        quoteTooltip.style.display = 'none';
      }
    }
  });

  document.addEventListener('mousedown', (e) => {
    if (!quoteTooltip.contains(e.target)) {
      quoteTooltip.style.display = 'none';
    }
  });
}

/* ══════════════════════════════════════
   PHASE 3 — References AJAX Filter
   ══════════════════════════════════════ */
const refsSearch = document.getElementById('refs-search');
const refsCount = document.getElementById('refs-count');
const refsEmpty = document.getElementById('refs-empty');
const refChips = document.querySelectorAll('.ref-chip');
let activeRefFilter = 'all';

function filterRefs() {
  const query = refsSearch ? refsSearch.value.trim().toLowerCase() : '';
  const cards = document.querySelectorAll('.ref-card-wrap');
  let visible = 0;

  cards.forEach(card => {
    const type = card.dataset.type;
    const title = (card.dataset.title || '').toLowerCase();
    const desc = (card.querySelector('.ref-card-desc')?.textContent || '').toLowerCase();

    const matchType = activeRefFilter === 'all' || type === activeRefFilter;
    const matchQuery = !query || title.includes(query) || desc.includes(query);

    if (matchType && matchQuery) {
      card.classList.remove('hidden-ref');
      visible++;
    } else {
      card.classList.add('hidden-ref');
    }
  });

  if (refsCount) {
    refsCount.textContent = visible > 0 ? `${visible} مرجع` : '';
  }
  if (refsEmpty) {
    refsEmpty.style.display = visible === 0 ? 'block' : 'none';
  }
}

if (refsSearch) {
  refsSearch.addEventListener('input', filterRefs);
}

refChips.forEach(chip => {
  chip.addEventListener('click', () => {
    refChips.forEach(c => c.classList.remove('active'));
    chip.classList.add('active');
    activeRefFilter = chip.dataset.filter;
    filterRefs();
  });
});

// Initialize count
if (document.querySelector('.ref-card-wrap')) {
  const total = document.querySelectorAll('.ref-card-wrap').length;
  if (refsCount) refsCount.textContent = `${total} مرجع`;
}

/* ══════════════════════════════════════
   IMPROVEMENTS — All Priority Fixes
   ══════════════════════════════════════ */

/* ── Reading Progress Bar ── */
(function() {
  const bar = document.getElementById('reading-progress-bar');
  if (!bar) return;
  window.addEventListener('scroll', () => {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    bar.style.width = (docHeight > 0 ? (scrollTop / docHeight) * 100 : 0) + '%';
  }, { passive: true });
})();

/* ── Scroll To Top Button ── */
(function() {
  const btn = document.getElementById('scroll-top-btn');
  if (!btn) return;
  function updateScrollBtn() {
    if (window.scrollY > 200) {
      btn.style.opacity = '1';
      btn.style.transform = 'translateY(0) scale(1)';
      btn.style.pointerEvents = 'auto';
    } else {
      btn.style.opacity = '0';
      btn.style.transform = 'translateY(20px) scale(0.8)';
      btn.style.pointerEvents = 'none';
    }
  }
  window.addEventListener('scroll', updateScrollBtn, { passive: true });
})();

/* ── Newsletter Validation ── */
function submitNewsletter() {
  const emailInput = document.getElementById('newsletter-email');
  const msgDiv = document.getElementById('newsletter-msg');
  const btn = document.getElementById('newsletter-btn');
  if (!emailInput || !msgDiv) return;

  const email = emailInput.value.trim();
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  msgDiv.style.display = 'block';
  msgDiv.className = '';

  if (!email) {
    msgDiv.textContent = '✖ يرجى إدخال بريدك الإلكتروني';
    msgDiv.classList.add('error');
    return;
  }
  if (!emailRegex.test(email)) {
    msgDiv.textContent = '✖ صيغة البريد غير صحيحة';
    msgDiv.classList.add('error');
    return;
  }

  // Simulate success (replace with real API call)
  btn.disabled = true;
  btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
  setTimeout(() => {
    msgDiv.textContent = '✔ تم الاشتراك بنجاح! ستصلك التحديثات قريباً';
    msgDiv.classList.add('success');
    emailInput.value = '';
    btn.innerHTML = '<i class="bi bi-check-lg"></i>';
    btn.disabled = false;
  }, 1200);
}

/* ── Ticker: Duplicate content for seamless loop ── */
(function() {
  const ticker = document.getElementById('news-ticker');
  if (!ticker) return;
  // Clone content for infinite scroll
  ticker.innerHTML += ticker.innerHTML;
})();

/* ── Hero Typewriter Animation ── */
(function() {
  const el = document.getElementById('hero-typewriter');
  if (!el) return;
  const roles = [
    'مقارنة الأديان',
    'نقد الكتاب المقدس',
    'الحوار الإسلامي المسيحي',
    'رد الشبهات',
    'تحليل النصوص الأصلية',
  ];
  let roleIndex = 0;
  let charIndex = 0;
  let deleting = false;

  function type() {
    const current = roles[roleIndex];
    if (!deleting) {
      el.textContent = current.slice(0, charIndex + 1);
      charIndex++;
      if (charIndex === current.length) {
        deleting = true;
        setTimeout(type, 2000);
        return;
      }
      setTimeout(type, 75);
    } else {
      el.textContent = current.slice(0, charIndex - 1);
      charIndex--;
      if (charIndex === 0) {
        deleting = false;
        roleIndex = (roleIndex + 1) % roles.length;
        setTimeout(type, 400);
        return;
      }
      setTimeout(type, 40);
    }
  }
  setTimeout(type, 1200);
})();
