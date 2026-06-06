@extends('layouts/layoutMaster')
@section('title', 'إنشاء حملة رسائل - Fix-It')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-send me-2"></i>إنشاء حملة رسائل جديدة</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}">الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <a href="{{ route('dashboard.messaging.campaigns') }}">الحملات</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>حملة جديدة</span>
        </div>
    </div>
    <a href="{{ route('dashboard.messaging.campaigns') }}" class="btn btn-label-secondary">
        <i class="ti tabler-arrow-right me-1"></i> رجوع
    </a>
</div>

{{-- Wizard steps --}}
<div class="wizard-steps mb-4">
    <div class="wizard-step active" id="step-indicator-1"><div class="ws-num">1</div><div class="ws-label">الإعداد الأساسي</div></div>
    <div class="wizard-connector"></div>
    <div class="wizard-step" id="step-indicator-2"><div class="ws-num">2</div><div class="ws-label">الرسالة</div></div>
    <div class="wizard-connector"></div>
    <div class="wizard-step" id="step-indicator-3"><div class="ws-num">3</div><div class="ws-label">الجمهور</div></div>
    <div class="wizard-connector"></div>
    <div class="wizard-step" id="step-indicator-4"><div class="ws-num">4</div><div class="ws-label">الإرسال</div></div>
</div>

{{-- Step 1: Basic Setup --}}
<div class="wizard-panel" id="step-1">
    <div class="card glass-card border-0">
        <div class="card-header border-bottom"><h5 class="card-title mb-0"><i class="ti tabler-settings me-2 text-primary"></i>الإعداد الأساسي</h5></div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">اسم الحملة <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg" id="campaign_name" placeholder="مثال: حملة عروض الصيف 2026">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">القناة <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mt-1">
                        <label class="channel-select-btn">
                            <input type="radio" name="channel" value="whatsapp" checked>
                            <span><i class="ti tabler-brand-whatsapp fs-4 d-block mb-1"></i>واتساب</span>
                        </label>
                        <label class="channel-select-btn">
                            <input type="radio" name="channel" value="sms">
                            <span><i class="ti tabler-message fs-4 d-block mb-1"></i>SMS</span>
                        </label>
                        <label class="channel-select-btn">
                            <input type="radio" name="channel" value="both">
                            <span><i class="ti tabler-device-mobile fs-4 d-block mb-1"></i>الاثنان</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-primary btn-next" data-next="2">التالي <i class="ti tabler-arrow-left ms-1"></i></button>
    </div>
</div>

{{-- Step 2: Message --}}
<div class="wizard-panel d-none" id="step-2">
    <div class="card glass-card border-0">
        <div class="card-header border-bottom"><h5 class="card-title mb-0"><i class="ti tabler-file-text me-2 text-success"></i>محتوى الرسالة</h5></div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">اختر من القوالب</label>
                    <select class="form-select mb-3" id="template_select" onchange="loadTemplate(this)">
                        <option value="">— اختر قالب جاهز —</option>
                        @foreach($templates as $t)
                        <option value="{{ $t['content'] }}">{{ $t['name'] }}</option>
                        @endforeach
                    </select>
                    <label class="form-label fw-semibold">أو اكتب رسالة مخصصة</label>
                    <textarea id="msg_content" class="form-control" rows="7" oninput="updateMsgPreview()"
                        placeholder="اكتب رسالتك هنا...&#10;&#10;المتغيرات: {name}، {order_id}، {date}"></textarea>
                    <div class="form-text">الحد الأقصى: 160 حرف لـ SMS | <span id="char_count">0</span>/160</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">معاينة الرسالة</label>
                    <div class="msg-phone-mockup">
                        <div class="phone-header"><i class="ti tabler-brand-whatsapp"></i> واتساب</div>
                        <div class="phone-bubble" id="msg_preview">اكتب رسالتك لتظهر المعاينة هنا...</div>
                        <div class="phone-time">{{ now()->format('h:i A') }} ✓✓</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-label-secondary btn-prev" data-prev="1"><i class="ti tabler-arrow-right me-1"></i> السابق</button>
        <button class="btn btn-primary btn-next" data-next="3">التالي <i class="ti tabler-arrow-left ms-1"></i></button>
    </div>
</div>

{{-- Step 3: Audience --}}
<div class="wizard-panel d-none" id="step-3">
    <div class="card glass-card border-0">
        <div class="card-header border-bottom"><h5 class="card-title mb-0"><i class="ti tabler-users me-2 text-warning"></i>تحديد الجمهور</h5></div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">المنطقة / المدينة</label>
                            <select class="form-select"><option value="">كل المناطق</option><option>الرياض</option><option>جدة</option><option>الدمام</option></select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">نوع الجهاز</label>
                            <select class="form-select"><option value="">كل الأجهزة</option><option>مكيفات</option><option>ثلاجات</option><option>غسالات</option></select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">حالة آخر أوردر</label>
                            <select class="form-select"><option value="">الكل</option><option>مكتمل</option><option>ملغي</option></select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">آخر طلب (قبل)</label>
                            <select class="form-select"><option value="">الكل</option><option value="7">7 أيام</option><option value="30">30 يوم</option><option value="90">3 أشهر</option><option value="180">6 أشهر</option></select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">التقييم</label>
                            <select class="form-select"><option value="">الكل</option><option value="5">5 نجوم</option><option value="4">4 نجوم فأكثر</option><option value="3">3 نجوم فأكثر</option></select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">المركز</label>
                            <select class="form-select"><option value="">كل المراكز</option><option>مركز النخبة</option><option>المركز السعودي</option></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="audience-counter-card">
                        <div class="ac-icon"><i class="ti tabler-users ti-lg"></i></div>
                        <div class="ac-number" id="audience_count">1,247</div>
                        <div class="ac-label">عميل مطابق للفلاتر</div>
                        <button class="btn btn-sm btn-label-primary mt-3" onclick="recalcAudience()">
                            <i class="ti tabler-refresh me-1"></i> تحديث العدد
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-label-secondary btn-prev" data-prev="2"><i class="ti tabler-arrow-right me-1"></i> السابق</button>
        <button class="btn btn-primary btn-next" data-next="4">التالي <i class="ti tabler-arrow-left ms-1"></i></button>
    </div>
</div>

{{-- Step 4: Send --}}
<div class="wizard-panel d-none" id="step-4">
    <div class="card glass-card border-0">
        <div class="card-header border-bottom"><h5 class="card-title mb-0"><i class="ti tabler-send me-2 text-success"></i>الإرسال</h5></div>
        <div class="card-body">
            <div class="row g-4 align-items-center">
                <div class="col-md-6">
                    <div class="send-option-card" id="send-now-card" onclick="selectSendOption('now')">
                        <i class="ti tabler-bolt-lightning ti-xl text-success mb-2"></i>
                        <h5 class="fw-bold mb-1">إرسال الآن</h5>
                        <p class="text-muted small mb-0">إرسال الحملة فوراً لجميع المستلمين</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="send-option-card" id="send-scheduled-card" onclick="selectSendOption('scheduled')">
                        <i class="ti tabler-calendar-event ti-xl text-warning mb-2"></i>
                        <h5 class="fw-bold mb-1">جدولة</h5>
                        <p class="text-muted small mb-0">تحديد وقت محدد للإرسال</p>
                    </div>
                </div>
                <div class="col-12 d-none" id="schedule_datetime_row">
                    <label class="form-label fw-semibold">موعد الإرسال</label>
                    <input type="datetime-local" class="form-control form-control-lg" id="scheduled_at">
                </div>
                <div class="col-12">
                    <div class="send-summary alert alert-info">
                        <h6 class="fw-bold mb-2"><i class="ti tabler-info-circle me-1"></i>ملخص الحملة</h6>
                        <div class="row g-2">
                            <div class="col-6"><span class="text-muted small">الحملة:</span> <span class="fw-semibold" id="summary_name">—</span></div>
                            <div class="col-6"><span class="text-muted small">القناة:</span> <span class="fw-semibold" id="summary_channel">واتساب</span></div>
                            <div class="col-6"><span class="text-muted small">المستلمون:</span> <span class="fw-semibold text-primary">1,247 عميل</span></div>
                            <div class="col-6"><span class="text-muted small">توقيت الإرسال:</span> <span class="fw-semibold" id="summary_time">فوري</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-label-secondary btn-prev" data-prev="3"><i class="ti tabler-arrow-right me-1"></i> السابق</button>
        <button class="btn btn-success btn-lg" id="btn-launch">
            <i class="ti tabler-rocket me-1"></i> إطلاق الحملة
        </button>
    </div>
</div>

<style>
.wizard-steps { display:flex; align-items:center; gap:0; }
.wizard-step { display:flex; flex-direction:column; align-items:center; gap:6px; }
.ws-num { width:36px;height:36px;border-radius:50%;border:2px solid #d9d9d9;display:flex;align-items:center;justify-content:center;font-weight:700;color:#8592a3;font-size:0.9rem;transition:all 0.3s; }
.wizard-step.active .ws-num,.wizard-step.done .ws-num { border-color:#7367f0;background:#7367f0;color:#fff; }
.ws-label { font-size:0.75rem;color:#8592a3;font-weight:600; }
.wizard-step.active .ws-label { color:#7367f0; }
.wizard-connector { flex:1;height:2px;background:#e9ecef;margin:0 8px;margin-bottom:18px; }
.channel-select-btn input { display:none; }
.channel-select-btn span { display:flex;flex-direction:column;align-items:center;padding:12px 20px;border-radius:12px;border:2px solid #d9d9d9;cursor:pointer;transition:all 0.2s;color:#6e6b7b;font-weight:600;font-size:0.85rem;min-width:80px; }
.channel-select-btn input:checked + span { border-color:#7367f0;background:rgba(115,103,240,0.1);color:#7367f0; }
.msg-phone-mockup { background:#e5ddd5;border-radius:16px;padding:16px;min-height:220px;position:relative; }
.phone-header { background:#128c7e;color:#fff;border-radius:10px 10px 0 0;padding:10px 14px;margin:-16px -16px 12px;font-size:0.85rem;font-weight:600; }
.phone-bubble { background:#fff;border-radius:0 12px 12px 12px;padding:12px 14px;font-size:0.85rem;line-height:1.6;max-width:90%;box-shadow:0 1px 3px rgba(0,0,0,0.1);white-space:pre-wrap; }
.phone-time { text-align:right;font-size:0.7rem;color:#667781;margin-top:4px; }
.audience-counter-card { text-align:center;background:rgba(115,103,240,0.06);border:2px solid rgba(115,103,240,0.15);border-radius:16px;padding:30px 20px; }
.ac-icon { font-size:2rem;color:#7367f0;margin-bottom:8px; }
.ac-number { font-size:2.5rem;font-weight:800;color:#7367f0;line-height:1; }
.ac-label { font-size:0.8rem;color:#8592a3;margin-top:4px; }
.send-option-card { border:2px solid #e9ecef;border-radius:16px;padding:30px;text-align:center;cursor:pointer;transition:all 0.2s; }
.send-option-card:hover,.send-option-card.selected { border-color:#7367f0;background:rgba(115,103,240,0.06); }
</style>
@endsection

@section('page-script')
<script>
let currentStep = 1;

function goToStep(n) {
    document.querySelectorAll('.wizard-panel').forEach(p => p.classList.add('d-none'));
    document.getElementById('step-' + n).classList.remove('d-none');
    document.querySelectorAll('[id^="step-indicator-"]').forEach((el,i) => {
        el.classList.toggle('active', i+1 === n);
        el.classList.toggle('done', i+1 < n);
    });
    currentStep = n;
    document.getElementById('summary_name').textContent = document.getElementById('campaign_name')?.value || '—';
}

document.querySelectorAll('.btn-next').forEach(btn => {
    btn.addEventListener('click', () => goToStep(parseInt(btn.dataset.next)));
});
document.querySelectorAll('.btn-prev').forEach(btn => {
    btn.addEventListener('click', () => goToStep(parseInt(btn.dataset.prev)));
});

function loadTemplate(sel) {
    if (sel.value) { document.getElementById('msg_content').value = sel.value; updateMsgPreview(); }
}

function updateMsgPreview() {
    const txt = document.getElementById('msg_content').value;
    document.getElementById('char_count').textContent = txt.length;
    document.getElementById('msg_preview').textContent = txt.replace(/{name}/g,'أحمد محمد').replace(/{order_id}/g,'#ORD-001').replace(/{date}/g,'10 مايو').replace(/{time}/g,'10:00 ص') || 'اكتب رسالتك...';
}

function selectSendOption(opt) {
    document.querySelectorAll('.send-option-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('send-' + opt + '-card').classList.add('selected');
    document.getElementById('schedule_datetime_row').classList.toggle('d-none', opt !== 'scheduled');
    document.getElementById('summary_time').textContent = opt === 'now' ? 'فوري' : 'مجدول';
}

function recalcAudience() {
    const el = document.getElementById('audience_count');
    el.textContent = '...';
    setTimeout(() => { el.textContent = (Math.floor(Math.random()*800)+400).toLocaleString('ar-EG'); }, 800);
}

selectSendOption('now');

document.getElementById('btn-launch')?.addEventListener('click', () => {
    Swal.fire({
        title: 'إطلاق الحملة؟',
        html: `سيتم إرسال الرسائل فوراً لـ <strong>1,247</strong> عميل.`,
        icon: 'question', showCancelButton: true,
        confirmButtonText: '<i class="ti tabler-rocket me-1"></i> نعم، أطلق!',
        cancelButtonText: 'مراجعة', confirmButtonColor: '#28c76f', reverseButtons: true,
    }).then(r => {
        if (r.isConfirmed) {
            Swal.fire({ icon:'success', title:'🚀 انطلقت!', text:'جاري إرسال الرسائل للعملاء.', timer:2500, showConfirmButton:false })
                .then(() => window.location.href = '{{ route("dashboard.messaging.campaigns") }}');
        }
    });
});
</script>
@endsection
