@extends('frontend.layouts.master')

@section('title', ($pageSeo['title'] ?? '') ?: 'عن معاذ عليان | باحث ومناظر في مقارنة الأديان')
@section('meta_description', ($pageSeo['description'] ?? '') ?: 'إعلامي وباحث مصري مسلم في مقارنة الأديان منذ عام 2004 — معاذ عليان')
@section('meta_keywords', ($pageSeo['keywords'] ?? '') ?: 'معاذ عليان, مقارنة الأديان, مناظرة, حوار إسلامي مسيحي')

@section('content')
<main style="padding-top: 80px;">
  <!-- ══════════════════ ABOUT ══════════════════ -->
  <section id="about" class="py-5 bg2">
    <div class="container">
      <div class="sec-head reveal">
        <div class="sec-eyebrow"><i class="bi bi-person-lines-fill"></i>التعريف</div>
        <h1 class="sec-title">عن معاذ عليان</h1>
        <p class="sec-sub">إعلامي وباحث مصري مسلم في مقارنة الأديان منذ عام 2004</p>
      </div>
      <div class="row g-4 align-items-start">
        <div class="col-lg-7 reveal">
          <!-- Quote -->
          <div style="background:var(--card);border-right:4px solid var(--gold);border-radius:var(--radius);padding:1.5rem 1.8rem;margin-bottom:1.5rem;position:relative">
            <i class="bi bi-quote" style="position:absolute;top:-6px;right:14px;font-size:2.8rem;color:var(--gold);opacity:.3"></i>
            <p style="font-family:'Amiri',serif;font-size:1.15rem;line-height:2;color:var(--text);margin:0">
              دخلت مجال مقارنة الأديان عام 2004، لم يكن لديَّ حينها إلا جهاز كمبيوتر ومايك وبعض الكتب والكتيبات، وبفضل الله قدمت عدة كتب إلكترونية ومطبوعة وأسست قناة المُخلَّص.
            </p>
          </div>
          <p style="color:var(--text2);line-height:2;margin-bottom:1.5rem">
            معاذ عليان إعلامي وباحث وكاتب ومناظر مصري مسلم، متخصص في مقارنة الأديان وتحديداً في الحوار الإسلامي المسيحي، يعمل على الرد على الشبهات ونقد الكتاب المقدس وتفنيد مزاعم المشككين والمنصرين.
          </p>
          <div class="row g-3">
            <div class="col-6 col-sm-3">
              <div style="background:var(--card);border:1px solid var(--border);border-radius:10px;padding:1.1rem;text-align:center">
                <span class="ic ic-md ic-gold mx-auto mb-2"><i class="bi bi-camera-video-fill"></i></span>
                <div style="font-size:.8rem;color:var(--text);font-weight:600">بث مباشر</div>
                <div style="font-size:.7rem;color:var(--text2)">يوتيوب</div>
              </div>
            </div>
            <div class="col-6 col-sm-3">
              <div style="background:var(--card);border:1px solid var(--border);border-radius:10px;padding:1.1rem;text-align:center">
                <span class="ic ic-md ic-teal mx-auto mb-2"><i class="bi bi-mic-fill"></i></span>
                <div style="font-size:.8rem;color:var(--text);font-weight:600">مناظرات</div>
                <div style="font-size:.7rem;color:var(--text2)">مباشرة</div>
              </div>
            </div>
            <div class="col-6 col-sm-3">
              <div style="background:var(--card);border:1px solid var(--border);border-radius:10px;padding:1.1rem;text-align:center">
                <span class="ic ic-md ic-green mx-auto mb-2"><i class="bi bi-book-fill"></i></span>
                <div style="font-size:.8rem;color:var(--text);font-weight:600">تأليف</div>
                <div style="font-size:.7rem;color:var(--text2)">كتب PDF</div>
              </div>
            </div>
            <div class="col-6 col-sm-3">
              <div style="background:var(--card);border:1px solid var(--border);border-radius:10px;padding:1.1rem;text-align:center">
                <span class="ic ic-md ic-blue mx-auto mb-2"><i class="bi bi-journals"></i></span>
                <div style="font-size:.8rem;color:var(--text);font-weight:600">مراجع</div>
                <div style="font-size:.7rem;color:var(--text2)">وأدلة</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Interactive Timeline -->
        <div class="col-lg-5">
          <div style="font-size:.82rem;color:var(--gold);text-transform:uppercase;letter-spacing:.1em;margin-bottom:2rem;font-weight:700">
            <i class="bi bi-clock-history me-1"></i>المسيرة الزمنية
          </div>
          <div class="timeline-container">

            <div class="timeline-item from-right" data-year="2004">
              <div class="timeline-dot"><span style="font-size:1.1rem">🎓</span></div>
              <div class="timeline-content card-x p-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="badge-x badge-gold">٢٠٠٤</span>
                  <span style="font-size:.75rem;color:var(--text3)">بداية الرحلة</span>
                </div>
                <div style="font-weight:700;color:var(--text);margin-bottom:.3rem">بداية المسيرة</div>
                <div style="font-size:.82rem;color:var(--text2);line-height:1.7">دخل مجال مقارنة الأديان والدعوة لغير المسلمين عبر منتديات البالتوك بجهاز كمبيوتر ومايك وكتيبات</div>
              </div>
            </div>

            <div class="timeline-item from-left" data-year="2009a">
              <div class="timeline-dot"><span style="font-size:1.1rem">📖</span></div>
              <div class="timeline-content card-x p-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="badge-x badge-gold">٢٠٠٩</span>
                  <span style="font-size:.75rem;color:var(--text3)">أول إنجاز</span>
                </div>
                <div style="font-weight:700;color:var(--text);margin-bottom:.3rem">أول كتاب مطبوع</div>
                <div style="font-size:.82rem;color:var(--text2);line-height:1.7">كتاب «عبادة مريم في المسيحية» مع أخيه محمود عليان — أول توثيق علمي مطبوع للرد على مزاعم التبشير</div>
              </div>
            </div>

            <div class="timeline-item from-right" data-year="2009b">
              <div class="timeline-dot"><span style="font-size:1.1rem">📺</span></div>
              <div class="timeline-content card-x p-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="badge-x badge-gold">٢٠٠٩</span>
                  <span style="font-size:.75rem;color:var(--text3)">تأسيس</span>
                </div>
                <div style="font-weight:700;color:var(--text);margin-bottom:.3rem">تأسيس قناة المُخلَّص</div>
                <div style="font-size:.82rem;color:var(--text2);line-height:1.7">أول قناة يوتيوب متخصصة في الرد على المشككين والمنصرين ومقارنة الأديان على الإنترنت العربي</div>
              </div>
            </div>

            <div class="timeline-item from-left" data-year="2015">
              <div class="timeline-dot"><span style="font-size:1.1rem">🌍</span></div>
              <div class="timeline-content card-x p-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="badge-x badge-gold">٢٠١٥+</span>
                  <span style="font-size:.75rem;color:var(--text3)">توسع</span>
                </div>
                <div style="font-weight:700;color:var(--text);margin-bottom:.3rem">التوسع والانتشار</div>
                <div style="font-size:.82rem;color:var(--text2);line-height:1.7">عشرات المناظرات المباشرة مع قسيسين وخبراء، ومئات المقالات الأكاديمية الموثقة، وملايين المشاهدات</div>
              </div>
            </div>

            <div class="timeline-item from-right" data-year="now">
              <div class="timeline-dot timeline-dot-active"><span style="font-size:1.1rem">🟢</span></div>
              <div class="timeline-content card-x p-3" style="border-color:var(--border2)">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="badge-x badge-green">الآن</span>
                  <span class="pulse" style="display:inline-block;width:8px;height:8px;background:var(--green);border-radius:50%"></span>
                </div>
                <div style="font-weight:700;color:var(--text);margin-bottom:.3rem">قناة معاذ عليان</div>
                <div style="font-size:.82rem;color:var(--text2);line-height:1.7">حوار إسلامي مسيحي ونقد كتابي ورد شبهات وقضايا فكرية — أكثر من مليون متابع وملايين المشاهدات</div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
