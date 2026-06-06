@extends('layouts/layoutMaster')
@section('title', 'قوالب الرسائل - Fix-It')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-file-text me-2"></i>قوالب الرسائل</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}">الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <a href="{{ route('dashboard.messaging.overview') }}">مركز الرسائل</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>القوالب</span>
        </div>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTemplateModal">
        <i class="ti tabler-plus me-1"></i> قالب جديد
    </button>
</div>

<div class="row g-4">
    @foreach($templates as $t)
    @php
        $typeMap=['offer'=>['warning','عرض','tabler-tag'],'follow_up'=>['info','متابعة','tabler-heart'],'reminder'=>['primary','تذكير','tabler-bell'],'rating'=>['success','تقييم','tabler-star'],'general'=>['secondary','عام','tabler-speakerphone']];
        [$tc,$tl,$ti] = $typeMap[$t['type']] ?? ['secondary',$t['type'],'tabler-message'];
    @endphp
    <div class="col-xl-4 col-md-6">
        <div class="card glass-card border-0 h-100 template-card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-label-{{ $tc }}"><i class="ti {{ $ti }} me-1"></i>{{ $tl }}</span>
                    @if($t['channel']==='whatsapp') <span class="channel-badge whatsapp" style="font-size:0.7rem;padding:2px 8px"><i class="ti tabler-brand-whatsapp me-1"></i>واتساب</span>
                    @elseif($t['channel']==='sms') <span class="channel-badge sms" style="font-size:0.7rem;padding:2px 8px"><i class="ti tabler-message me-1"></i>SMS</span>
                    @else <span class="channel-badge both" style="font-size:0.7rem;padding:2px 8px"><i class="ti tabler-device-mobile me-1"></i>الاثنان</span>
                    @endif
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" {{ $t['is_active'] ? 'checked' : '' }}>
                </div>
            </div>
            <div class="card-body">
                <h6 class="fw-bold mb-2">{{ $t['name'] }}</h6>
                <div class="template-preview">{{ $t['content'] }}</div>
                <div class="mt-3 d-flex align-items-center justify-content-between">
                    <small class="text-muted"><i class="ti tabler-send me-1"></i>{{ number_format($t['sent_count']) }} مرة</small>
                    <small class="text-muted">{{ $t['created_at'] }}</small>
                </div>
            </div>
            <div class="card-footer border-top d-flex gap-2">
                <button class="btn btn-sm btn-label-primary flex-fill btn-use-template" data-id="{{ $t['id'] }}" data-name="{{ $t['name'] }}">
                    <i class="ti tabler-send me-1"></i> استخدام
                </button>
                <button class="btn btn-sm btn-label-secondary"><i class="ti tabler-edit"></i></button>
                <button class="btn btn-sm btn-label-danger"><i class="ti tabler-trash"></i></button>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Add Template Modal --}}
<div class="modal fade" id="addTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title"><i class="ti tabler-file-plus me-2 text-primary"></i>إنشاء قالب جديد</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">اسم القالب <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="مثال: عرض رمضان 2026">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">النوع</label>
                        <select class="form-select">
                            <option value="offer">عرض وخصم</option>
                            <option value="follow_up">متابعة</option>
                            <option value="reminder">تذكير</option>
                            <option value="rating">طلب تقييم</option>
                            <option value="general">عام</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">القناة</label>
                        <select class="form-select">
                            <option value="whatsapp">واتساب فقط</option>
                            <option value="sms">SMS فقط</option>
                            <option value="both">الاثنان</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">نص الرسالة <span class="text-danger">*</span></label>
                        <textarea id="template_content" class="form-control" rows="5" oninput="updatePreview()"
                            placeholder="اكتب نص الرسالة هنا...&#10;&#10;المتغيرات المتاحة:&#10;{name} — اسم العميل&#10;{order_id} — رقم الأوردر&#10;{date} — التاريخ&#10;{time} — الوقت"></textarea>
                        <div class="form-text">استخدم {name}، {order_id}، {date}، {time} كمتغيرات ديناميكية</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">معاينة</label>
                        <div id="template_preview" class="template-preview-box">ابدأ الكتابة لتظهر المعاينة...</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button class="btn btn-primary"><i class="ti tabler-device-floppy me-1"></i> حفظ القالب</button>
            </div>
        </div>
    </div>
</div>

<style>
.template-card { transition: transform 0.2s; }
.template-card:hover { transform: translateY(-3px); }
.template-preview { font-size: 0.85rem; color: #6e6b7b; background: rgba(115,103,240,0.04); border-radius:10px; padding: 12px; border: 1px solid rgba(115,103,240,0.1); min-height: 80px; white-space: pre-wrap; line-height: 1.6; }
.template-preview-box { font-size: 0.85rem; background: #f8f9fa; border-radius:10px; padding: 12px; border: 1px dashed #dee2e6; min-height:60px; white-space:pre-wrap; }
.channel-badge { display:inline-flex;align-items:center;font-weight:600;border-radius:20px; }
.channel-badge.whatsapp{background:rgba(37,211,102,0.12);color:#25d366;border:1px solid rgba(37,211,102,0.25);}
.channel-badge.sms{background:rgba(0,207,232,0.12);color:#00cfe8;border:1px solid rgba(0,207,232,0.25);}
.channel-badge.both{background:rgba(115,103,240,0.12);color:#7367f0;border:1px solid rgba(115,103,240,0.25);}
</style>
@endsection

@section('page-script')
<script>
function updatePreview() {
    const text = document.getElementById('template_content').value;
    document.getElementById('template_preview').textContent = text
        .replace(/{name}/g,'أحمد محمد')
        .replace(/{order_id}/g,'#ORD-001')
        .replace(/{date}/g,'10 مايو 2026')
        .replace(/{time}/g,'10:00 ص') || 'ابدأ الكتابة لتظهر المعاينة...';
}
document.querySelectorAll('.btn-use-template').forEach(btn => {
    btn.addEventListener('click', () => {
        window.location.href = '{{ route("dashboard.messaging.campaigns.create") }}?template=' + btn.dataset.id;
    });
});
</script>
@endsection
