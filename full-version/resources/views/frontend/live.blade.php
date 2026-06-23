@extends('frontend.layouts.master')

@section('title', 'البث المباشر | معاذ عليان')
@section('meta_description', 'تابع معاذ عليان مباشرةً على يوتيوب — البث يظهر تلقائياً عند انطلاقه')

@section('content')
<main style="padding-top: 80px;">
  <!-- ══════════════════ LIVE STREAM ══════════════════ -->
  <section id="live" class="py-5">
    <div class="container">
      <div class="sec-head reveal">
        <div class="sec-eyebrow"><i class="bi bi-broadcast"></i>مباشر الآن</div>
        <h1 class="sec-title">البث المباشر</h1>
        <p class="sec-sub">تابع معاذ عليان مباشرةً على يوتيوب — البث يظهر تلقائياً عند انطلاقه</p>
      </div>

      <div class="row g-4 align-items-start">
        <div class="col-lg-8 reveal">
          <div class="live-checker">
            <div class="live-header">
              <div class="d-flex align-items-center gap-2">
                <span id="live-status-dot" class="ic ic-sm ic-red"><i class="bi bi-broadcast"></i></span>
                <div>
                  <div style="font-weight:700;font-size:.92rem">القناة الرسمية — معاذ عليان</div>
                  <div id="live-status-text" style="font-size:.75rem;color:var(--text2)">جارٍ التحقق من حالة البث...</div>
                </div>
              </div>
              <a href="https://www.youtube.com/c/moazalian" target="_blank" class="btn btn-sm btn-outline-gold d-flex align-items-center gap-1">
                <i class="bi bi-youtube"></i><span class="d-none d-sm-inline">القناة</span>
              </a>
            </div>
            <!-- Live embed -->
            <div id="live-embed-wrap" class="yt-frame">
              <iframe id="live-iframe"
                src="https://www.youtube.com/embed/live_stream?channel=UCZgb3h-QHvK8abuJzs73vag&autoplay=0&mute=0&controls=1"
                allowfullscreen allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture"
                loading="lazy" title="بث معاذ عليان المباشر"></iframe>
            </div>
          </div>
        </div>

        <!-- Side info -->
        <div class="col-lg-4 d-flex flex-column gap-3 reveal">
          <!-- Status card -->
          <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:1.2rem">
            <div class="d-flex align-items-center gap-2 mb-3">
              <span class="ic ic-sm ic-red"><i class="bi bi-wifi"></i></span>
              <span style="font-weight:600;font-size:.9rem">حالة البث الآن</span>
            </div>
            <div id="live-status-card" style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:.9rem;text-align:center">
              <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                <span id="live-indicator-icon" style="color:var(--text2);font-size:1.2rem"><i class="bi bi-hourglass-split"></i></span>
                <span id="live-status-label" style="font-weight:700;color:var(--text2)">جارٍ التحقق</span>
              </div>
              <div style="font-size:.75rem;color:var(--text2)" id="live-hint">سيظهر البث المباشر تلقائياً أعلاه عند انطلاقه</div>
            </div>
          </div>

          <!-- Subscribe -->
          <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:1.2rem">
            <div class="d-flex align-items-center gap-2 mb-2">
              <span class="ic ic-sm ic-red"><i class="bi bi-bell-fill"></i></span>
              <span style="font-weight:600;font-size:.9rem">اشترك واستقبل التنبيهات</span>
            </div>
            <p style="font-size:.8rem;color:var(--text2);margin-bottom:1rem">فعّل 🔔 التنبيهات على يوتيوب لتُعلَم فور بدء البث أو نشر فيديو جديد</p>
            <a href="https://www.youtube.com/c/moazalian?sub_confirmation=1" target="_blank" class="btn btn-gold w-100 d-flex align-items-center justify-content-center gap-2">
              <i class="bi bi-youtube"></i>اشترك في القناة
            </a>
          </div>

          <!-- Content types -->
          <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:1.2rem">
            <div class="d-flex align-items-center gap-2 mb-2">
              <span class="ic ic-sm ic-gold"><i class="bi bi-calendar-week"></i></span>
              <span style="font-weight:600;font-size:.9rem">نوع المحتوى</span>
            </div>
            <div class="d-flex flex-column gap-2">
              <div class="d-flex align-items-center gap-2" style="font-size:.8rem;color:var(--text2)">
                <span class="ic ic-sm ic-teal" style="width:28px;height:28px;font-size:.75rem"><i class="bi bi-mic"></i></span>
                حوارات ومناظرات مباشرة
              </div>
              <div class="d-flex align-items-center gap-2" style="font-size:.8rem;color:var(--text2)">
                <span class="ic ic-sm ic-blue" style="width:28px;height:28px;font-size:.75rem"><i class="bi bi-shield-check"></i></span>
                ردود على شبهات المنصرين
              </div>
              <div class="d-flex align-items-center gap-2" style="font-size:.8rem;color:var(--text2)">
                <span class="ic ic-sm ic-green" style="width:28px;height:28px;font-size:.75rem"><i class="bi bi-book"></i></span>
                قراءة في الكتاب المقدس
              </div>
              <div class="d-flex align-items-center gap-2" style="font-size:.8rem;color:var(--text2)">
                <span class="ic ic-sm ic-orange" style="width:28px;height:28px;font-size:.75rem"><i class="bi bi-chat-dots"></i></span>
                تلقي أسئلة المشاهدين
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
