@extends('frontend.layouts.master')

@section('title', 'الدعوة للإسلام | معاذ عليان')
@section('meta_description', 'خطوات مبسطة وعملية لغير المختصين لدعوة غير المسلمين للإسلام بالحكمة والموعظة الحسنة')

@section('content')
<main style="padding-top: 80px;">
  <section id="dawah-guide" class="py-5 bg2">
    <div class="container">
      <div class="sec-head reveal">
        <div class="sec-eyebrow"><i class="bi bi-lightbulb-fill"></i>دليل عملي</div>
        <h1 class="sec-title">كيف تدعو للإسلام؟</h1>
        <p class="sec-sub">خطوات مبسطة وعملية لغير المختصين لدعوة غير المسلمين للإسلام بالحكمة والموعظة الحسنة</p>
      </div>

      <div class="row g-4 align-items-start">
        <div class="col-lg-8 reveal">
          <p style="color:var(--text2);line-height:2;margin-bottom:2rem;font-size:1.05rem">
            الدعوة إلى الله ليست حكراً على العلماء والمتخصصين فقط، بل هي واجب على كل مسلم بما يعلم. الكثير من الناس دخلوا الإسلام ليس بسبب مناظرات معقدة، بل بسبب التعامل الطيب والأخلاق الحسنة والكلمة الصادقة من زميل عمل أو جار مسلم. إليك أهم الخطوات العملية:
          </p>
          <div class="d-flex flex-column gap-4">
            <div class="card-x p-4" style="border-right:4px solid var(--gold)">
              <h4 style="color:var(--text);font-size:1.1rem;margin-bottom:.8rem">
                <i class="bi bi-1-circle-fill text-gold me-2"></i>الدعوة بالأخلاق والسلوك
              </h4>
              <p style="color:var(--text2);font-size:.9rem;line-height:1.8;margin:0">
                قبل أن تتحدث عن الإسلام، اجعل سلوكك يتحدث نيابة عنك. الأمانة، الإتقان في العمل، الابتسامة، والصدق هي أقوى رسالة دعوية يمكنك تقديمها. الناس يقرؤون أفعالك قبل أن يستمعوا لأقوالك.
              </p>
            </div>
            <div class="card-x p-4" style="border-right:4px solid var(--teal)">
              <h4 style="color:var(--text);font-size:1.1rem;margin-bottom:.8rem">
                <i class="bi bi-2-circle-fill text-teal me-2"></i>التركيز على الأصول والأساسيات
              </h4>
              <p style="color:var(--text2);font-size:.9rem;line-height:1.8;margin:0">
                عند بدء الحديث، ركز على أساس الإسلام: التوحيد الصافي (إفراد الله بالعبادة)، وأن الإسلام هو امتداد لرسالات الأنبياء جميعاً (نوح، إبراهيم، موسى، عيسى، ومحمد عليهم السلام). تجنب الخوض في التفاصيل الفقهية الفرعية في البداية.
              </p>
            </div>
            <div class="card-x p-4" style="border-right:4px solid var(--green)">
              <h4 style="color:var(--text);font-size:1.1rem;margin-bottom:.8rem">
                <i class="bi bi-3-circle-fill text-green me-2"></i>تجنب الجدال والمناظرات اللاهوتية
              </h4>
              <p style="color:var(--text2);font-size:.9rem;line-height:1.8;margin:0">
                بصفتك غير مختص، تجنب الدخول في مناقشات لاهوتية معقدة أو نقد الأديان الأخرى. إذا طُرحت عليك شبهة أو سؤال لا تعرف إجابته، قل بكل بساطة وبدون حرج: <em>"لا أعرف التفاصيل الدقيقة، لكني سأسأل المختصين وأعود إليك بالإجابة."</em>
              </p>
            </div>
            <div class="card-x p-4" style="border-right:4px solid var(--purple)">
              <h4 style="color:var(--text);font-size:1.1rem;margin-bottom:.8rem">
                <i class="bi bi-4-circle-fill text-purple me-2"></i>استخدام المواد الدعوية الجاهزة
              </h4>
              <p style="color:var(--text2);font-size:.9rem;line-height:1.8;margin:0">
                ليس عليك أن تشرح كل شيء بنفسك. يمكنك إهداء كتاب مترجم بلغة الشخص، أو إرسال مقطع فيديو قصير مبسط يشرح الإسلام. وجههم دائماً للمصادر الموثوقة والمتخصصين في الدعوة.
              </p>
            </div>
          </div>
        </div>

        <!-- Sidebar CTA -->
        <div class="col-lg-4">
          <div class="card-x p-4 sticky-top" style="top:100px;text-align:center;display:flex;flex-direction:column;gap:1.5rem">
            <div style="width:80px;height:80px;border-radius:50%;background:rgba(37,211,102,.12);display:flex;align-items:center;justify-content:center;font-size:2.2rem;margin:0 auto;color:#25d366">
              <i class="bi bi-chat-dots-fill"></i>
            </div>
            <h3 style="color:var(--text);font-size:1.3rem">هل تواجه سؤالاً صعباً؟</h3>
            <p style="color:var(--text2);font-size:.9rem;line-height:1.7">
              إذا سألك شخص غير مسلم سؤالاً لا تعرف إجابته، أو كان مهتماً ويريد التحدث مع مختص، فنحن هنا لمساعدتك.
            </p>
            <a href="https://wa.me/201000000000" target="_blank" class="btn w-100" style="background:#25d366;color:#fff;font-weight:600;padding:.8rem;border-radius:8px;border:none">
               تواصل مع المختصين <i class="bi bi-whatsapp me-1"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
