@extends('layouts/layoutMaster')
@section('title', $campaign['name'] . ' - Fix-It')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-speakerphone me-2"></i>{{ $campaign['name'] }}</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}">الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <a href="{{ route('dashboard.messaging.campaigns') }}">الحملات</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>{{ $campaign['name'] }}</span>
        </div>
    </div>
    <a href="{{ route('dashboard.messaging.campaigns') }}" class="btn btn-label-secondary">
        <i class="ti tabler-arrow-right me-1"></i> رجوع
    </a>
</div>

@php
    $sm=['completed'=>['success','مكتملة'],'scheduled'=>['warning','مجدولة'],'sending'=>['info','يُرسل'],'draft'=>['secondary','مسودة'],'failed'=>['danger','فشلت']];
    [$sc,$sl] = $sm[$campaign['status']] ?? ['secondary',$campaign['status']];
    $delivRate = $campaign['sent_count']>0 ? round($campaign['delivered_count']/$campaign['sent_count']*100) : 0;
    $readRate  = $campaign['delivered_count']>0 ? round($campaign['read_count']/$campaign['delivered_count']*100) : 0;
@endphp

{{-- Stats row --}}
<div class="row g-4 mb-4">
    <div class="col-xl col-sm-6"><div class="card glass-card border-0"><div class="card-body text-center">
        <div class="fs-2 fw-bold text-primary">{{ number_format($campaign['recipients_count']) }}</div>
        <div class="small text-muted mt-1">المستهدفون</div>
    </div></div></div>
    <div class="col-xl col-sm-6"><div class="card glass-card border-0"><div class="card-body text-center">
        <div class="fs-2 fw-bold text-info">{{ number_format($campaign['sent_count']) }}</div>
        <div class="small text-muted mt-1">أُرسل</div>
    </div></div></div>
    <div class="col-xl col-sm-6"><div class="card glass-card border-0"><div class="card-body text-center">
        <div class="fs-2 fw-bold text-success">{{ number_format($campaign['delivered_count']) }}</div>
        <div class="small text-muted mt-1">وصل ({{ $delivRate }}%)</div>
    </div></div></div>
    <div class="col-xl col-sm-6"><div class="card glass-card border-0"><div class="card-body text-center">
        <div class="fs-2 fw-bold text-warning">{{ number_format($campaign['read_count']) }}</div>
        <div class="small text-muted mt-1">قُرئ ({{ $readRate }}%)</div>
    </div></div></div>
    <div class="col-xl col-sm-6"><div class="card glass-card border-0"><div class="card-body text-center">
        <div class="fs-2 fw-bold text-danger">{{ number_format($campaign['failed_count']) }}</div>
        <div class="small text-muted mt-1">فشل</div>
    </div></div></div>
</div>

{{-- Progress bars --}}
<div class="card glass-card border-0 mb-4">
    <div class="card-body">
        <div class="mb-3">
            <div class="d-flex justify-content-between mb-1">
                <span class="small fw-semibold">نسبة التسليم</span>
                <span class="small fw-bold text-success">{{ $delivRate }}%</span>
            </div>
            <div class="progress" style="height:10px;border-radius:10px">
                <div class="progress-bar bg-success" style="width:{{ $delivRate }}%"></div>
            </div>
        </div>
        <div>
            <div class="d-flex justify-content-between mb-1">
                <span class="small fw-semibold">نسبة القراءة</span>
                <span class="small fw-bold text-warning">{{ $readRate }}%</span>
            </div>
            <div class="progress" style="height:10px;border-radius:10px">
                <div class="progress-bar bg-warning" style="width:{{ $readRate }}%"></div>
            </div>
        </div>
    </div>
</div>

{{-- Log table --}}
<div class="card glass-card overflow-hidden">
    <div class="card-header border-bottom d-flex justify-content-between">
        <h5 class="card-title mb-0"><i class="ti tabler-list-details me-2 text-primary"></i>سجل الإرسال</h5>
        <span class="badge bg-label-primary">{{ count($logs) }} رسالة</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover fixit-table border-top mb-0">
            <thead><tr><th>العميل</th><th>الهاتف</th><th>القناة</th><th>الحالة</th><th>الوقت</th></tr></thead>
            <tbody>
                @forelse($logs as $l)
                @php
                    $lsm=['sent'=>['info','أُرسل'],'delivered'=>['success','وصل'],'read'=>['primary','قُرئ'],'pending'=>['secondary','معلق'],'failed'=>['danger','فشل']];
                    [$lc,$ll] = $lsm[$l['status']] ?? ['secondary',$l['status']];
                @endphp
                <tr>
                    <td class="fw-semibold">{{ $l['customer_name'] }}</td>
                    <td class="text-muted small">{{ $l['customer_phone'] }}</td>
                    <td>
                        @if($l['channel']==='whatsapp') <span class="channel-badge whatsapp" style="font-size:0.7rem;padding:2px 8px"><i class="ti tabler-brand-whatsapp me-1"></i>واتساب</span>
                        @else <span class="channel-badge sms" style="font-size:0.7rem;padding:2px 8px"><i class="ti tabler-message me-1"></i>SMS</span>@endif
                    </td>
                    <td><span class="badge bg-label-{{ $lc }}">{{ $ll }}</span></td>
                    <td class="small text-muted">{{ $l['sent_at'] }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">لا توجد سجلات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.channel-badge{display:inline-flex;align-items:center;font-weight:600;border-radius:20px;}
.channel-badge.whatsapp{background:rgba(37,211,102,0.12);color:#25d366;border:1px solid rgba(37,211,102,0.25);}
.channel-badge.sms{background:rgba(0,207,232,0.12);color:#00cfe8;border:1px solid rgba(0,207,232,0.25);}
</style>
@endsection
