@extends('frontend.layouts.master')

@section('title', 'تواصل | معاذ عليان')
@section('meta_description', 'تواصل مع معاذ عليان عبر المنصات الرسمية أو أرسل رسالتك مباشرة')

@section('content')
<main style="padding-top: 80px;">

  <!-- ══════════════════ SUPPORT ══════════════════ -->
  <section id="support" class="py-5" style="background:radial-gradient(ellipse 60% 50% at 50% 50%,rgba(196,153,58,.06) 0%,transparent 70%),var(--bg)">
    <div class="container">
      <div class="sec-head reveal">
        <div class="sec-eyebrow"><i class="bi bi-heart-fill"></i>الدعم</div>
        <h1 class="sec-title">ادعم المحتوى</h1>
        <p class="sec-sub">دعمك يُمكّن معاذ عليان من الاستمرار في البحث وإنتاج المحتوى العلمي</p>
      </div>
      <div class="row g-4 justify-content-center">
        <div class="col-md-4 reveal">
          <div class="support-card">
            <span class="ic ic-lg" style="background:rgba(0,152,210,.12);color:#009CD6;margin:0 auto">
              <i class="bi bi-paypal"></i>
            </span>
            <div class="support-title">PayPal</div>
            <div class="support-desc">تبرع مباشر وآمن لدعم محتوى القناة وموقع تنويري لرد الشبهات</div>
            <a href="https://www.paypal.com/paypalme/moazalian" target="_blank" class="btn btn-gold w-100 d-flex align-items-center justify-content-center gap-2">
              <i class="bi bi-paypal"></i>تبرع عبر PayPal
            </a>
          </div>
        </div>
        <div class="col-md-4 reveal">
          <div class="support-card" style="border-color:rgba(196,153,58,.35)">
            <span class="ic ic-lg" style="background:rgba(255,66,77,.12);color:#FF424D;margin:0 auto">
              <i class="bi bi-p-circle-fill"></i>
            </span>
            <div class="support-title">Patreon</div>
            <div class="support-desc">اشترك شهرياً واحصل على محتوى حصري ومتابعة مباشرة مع معاذ</div>
            <a href="https://www.patreon.com/moazalian" target="_blank" class="btn btn-outline-gold w-100 d-flex align-items-center justify-content-center gap-2">
              <i class="bi bi-p-circle"></i>اشترك في Patreon
            </a>
          </div>
        </div>
        <div class="col-md-4 reveal">
          <div class="support-card">
            <span class="ic ic-lg" style="background:rgba(255,0,0,.12);color:#FF0000;margin:0 auto">
              <i class="bi bi-youtube"></i>
            </span>
            <div class="support-title">عضوية يوتيوب</div>
            <div class="support-desc">انضم لعضوية القناة على يوتيوب للحصول على شارة خاصة ومميزات</div>
            <a href="https://www.youtube.com/c/moazalian/join" target="_blank" class="btn btn-outline-gold w-100 d-flex align-items-center justify-content-center gap-2">
              <i class="bi bi-star"></i>انضم للعضوية
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════ CONTACT ══════════════════ -->
  <section id="contact" class="py-5 bg2">
    <div class="container">
      <div class="sec-head reveal">
        <div class="sec-eyebrow"><i class="bi bi-envelope-fill"></i>التواصل</div>
        <h2 class="sec-title">تابع وتواصل</h2>
        <p class="sec-sub">ابقَ على تواصل عبر المنصات الرسمية أو أرسل رسالتك مباشرة</p>
      </div>
      <div class="row g-5">
        {{-- Social Platforms --}}
        <div class="col-lg-5 reveal">
          <div style="font-size:.82rem;color:var(--gold);text-transform:uppercase;letter-spacing:.1em;font-weight:700;margin-bottom:1.2rem">
            <i class="bi bi-share-fill me-1"></i>المنصات الرسمية
          </div>
          <div class="d-flex flex-column gap-2">
            <a href="https://www.youtube.com/c/moazalian" target="_blank" class="soc-btn">
              <span class="ic ic-sm" style="background:rgba(255,0,0,.15);color:#ff0000"><i class="bi bi-youtube"></i></span>
              قناة يوتيوب — معاذ عليان
            </a>
            <a href="https://www.facebook.com/moazalian" target="_blank" class="soc-btn">
              <span class="ic ic-sm" style="background:rgba(24,119,242,.15);color:#1877f2"><i class="bi bi-facebook"></i></span>
              صفحة فيسبوك
            </a>
            <a href="https://www.instagram.com/moazalian" target="_blank" class="soc-btn">
              <span class="ic ic-sm" style="background:rgba(225,48,108,.15);color:#e1306c"><i class="bi bi-instagram"></i></span>
              إنستقرام
            </a>
            <a href="https://twitter.com/habd1100" target="_blank" class="soc-btn">
              <span class="ic ic-sm" style="background:rgba(255,255,255,.08);color:var(--text)"><i class="bi bi-twitter-x"></i></span>
              تويتر / X
            </a>
            <a href="https://www.patreon.com/moazalian" target="_blank" class="soc-btn">
              <span class="ic ic-sm" style="background:rgba(255,66,77,.15);color:#FF424D"><i class="bi bi-p-circle-fill"></i></span>
              باتريون
            </a>
          </div>
        </div>

        {{-- Contact Form --}}
        <div class="col-lg-7 reveal">
          <div style="font-size:.82rem;color:var(--gold);text-transform:uppercase;letter-spacing:.1em;font-weight:700;margin-bottom:1.2rem">
            <i class="bi bi-envelope-open me-1"></i>راسلنا
          </div>

          @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
          @endif

          <form action="{{ route('contact.store') }}" method="POST" class="d-flex flex-column gap-3">
            @csrf
            <div class="row g-3">
              <div class="col-sm-6">
                <input type="text" name="name" class="form-ctrl @error('name') is-invalid @enderror"
                  placeholder="الاسم الكريم" value="{{ old('name') }}" aria-label="الاسم الكريم"/>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-sm-6">
                <input type="email" name="email" class="form-ctrl @error('email') is-invalid @enderror"
                  placeholder="البريد الإلكتروني" value="{{ old('email') }}" aria-label="البريد الإلكتروني"/>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
            <input type="text" name="subject" class="form-ctrl @error('subject') is-invalid @enderror"
              placeholder="موضوع الرسالة" value="{{ old('subject') }}" aria-label="موضوع الرسالة"/>
            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <textarea name="message" class="form-ctrl @error('message') is-invalid @enderror"
              rows="5" placeholder="رسالتك..." aria-label="الرسالة">{{ old('message') }}</textarea>
            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <button type="submit" class="btn btn-gold d-flex align-items-center justify-content-center gap-2">
              <i class="bi bi-send-fill"></i>إرسال الرسالة
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection
