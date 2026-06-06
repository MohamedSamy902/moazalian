@extends('layouts/layoutMaster')
@section('title', 'مركز الرسائل - Fix-It')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-message-dots me-2"></i>مركز الرسائل</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}">الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>مركز الرسائل</span>
        </div>
    </div>
    <a href="{{ route('dashboard.messaging.campaigns.create') }}" class="btn btn-primary">
        <i class="ti tabler-send me-1"></i> حملة جديدة
    </a>
</div>

{{-- Stats --}}
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-sm-6"><div class="card glass-card border-0 h-100"><div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon gradient-primary flex-shrink-0"><i class="ti tabler-send text-white ti-md"></i></div>
        <div><div class="card-label">إجمالي المُرسل</div><div class="card-value">{{ number_format($stats['total_sent']) }}</div></div>
    </div></div></div>
    <div class="col-xl-3 col-sm-6"><div class="card glass-card border-0 h-100"><div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon gradient-success flex-shrink-0"><i class="ti tabler-circle-check text-white ti-md"></i></div>
        <div><div class="card-label">وصلت</div><div class="card-value text-success">{{ number_format($stats['total_delivered']) }}</div></div>
    </div></div></div>
    <div class="col-xl-3 col-sm-6"><div class="card glass-card border-0 h-100"><div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon gradient-info flex-shrink-0"><i class="ti tabler-eye text-white ti-md"></i></div>
        <div><div class="card-label">قُرئت</div><div class="card-value text-info">{{ number_format($stats['total_read']) }}</div></div>
    </div></div></div>
    <div class="col-xl-3 col-sm-6"><div class="card glass-card border-0 h-100"><div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon gradient-warning flex-shrink-0"><i class="ti tabler-speakerphone text-white ti-md"></i></div>
        <div><div class="card-label">حملات نشطة</div><div class="card-value text-warning">{{ $stats['active_campaigns'] }}</div></div>
    </div></div></div>
</div>

{{-- Recent Campaigns --}}
<div class="card glass-card overflow-hidden mb-4">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><i class="ti tabler-speakerphone me-2 text-primary"></i>آخر الحملات</h5>
        <a href="{{ route('dashboard.messaging.campaigns') }}" class="btn btn-sm btn-label-primary">عرض الكل</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover fixit-table border-top mb-0">
            <thead><tr><th>الحملة</th><th>القناة</th><th>المستلمون</th><th>المُرسل</th><th>وصل</th><th>قُرئ</th><th>الحالة</th><th></th></tr></thead>
            <tbody>
                @foreach($campaigns as $c)
                @php
                    $statusMap=['completed'=>['success','مكتملة'],'scheduled'=>['warning','مجدولة'],'sending'=>['info','يُرسل'],'draft'=>['secondary','مسودة'],'failed'=>['danger','فشلت']];
                    [$sc,$sl] = $statusMap[$c['status']] ?? ['secondary',$c['status']];
                    $deliveryRate = $c['sent_count'] > 0 ? round($c['delivered_count']/$c['sent_count']*100) : 0;
                @endphp
                <tr>
                    <td class="fw-semibold">{{ $c['name'] }}</td>
                    <td>
                        @if($c['channel']==='whatsapp') <span class="channel-badge whatsapp"><i class="ti tabler-brand-whatsapp me-1"></i>واتساب</span>
                        @elseif($c['channel']==='sms') <span class="channel-badge sms"><i class="ti tabler-message me-1"></i>SMS</span>
                        @else <span class="channel-badge both"><i class="ti tabler-device-mobile me-1"></i>الاثنان</span>
                        @endif
                    </td>
                    <td>{{ number_format($c['recipients_count']) }}</td>
                    <td>{{ number_format($c['sent_count']) }}</td>
                    <td>{{ number_format($c['delivered_count']) }} <small class="text-muted">({{ $deliveryRate }}%)</small></td>
                    <td>{{ number_format($c['read_count']) }}</td>
                    <td><span class="badge bg-label-{{ $sc }}">{{ $sl }}</span></td>
                    <td><a href="{{ route('dashboard.messaging.campaigns.show', $c['id']) }}" class="btn btn-sm btn-icon btn-label-primary"><i class="ti tabler-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
.channel-badge { display:inline-flex;align-items:center;font-size:0.78rem;font-weight:600;padding:3px 10px;border-radius:20px; }
.channel-badge.whatsapp { background:rgba(37,211,102,0.12);color:#25d366;border:1px solid rgba(37,211,102,0.25); }
.channel-badge.sms { background:rgba(0,207,232,0.12);color:#00cfe8;border:1px solid rgba(0,207,232,0.25); }
.channel-badge.both { background:rgba(115,103,240,0.12);color:#7367f0;border:1px solid rgba(115,103,240,0.25); }
.gradient-info { background: linear-gradient(135deg,#00cfe8,#1de9b6); }
</style>
@endsection
