@extends('layouts/layoutMaster')
@section('title', 'سجل الرسائل - Fix-It')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-list-details me-2"></i>سجل الرسائل</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}">الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <a href="{{ route('dashboard.messaging.overview') }}">مركز الرسائل</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>سجل الرسائل</span>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card glass-card border-0 mb-4">
    <div class="card-body py-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-3"><label class="form-label small fw-bold">بحث (اسم / رقم)</label><input class="form-control" placeholder="اسم العميل أو رقم الهاتف..."></div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">الحملة</label>
                <select class="form-select">
                    <option value="">الكل</option>
                    @foreach($campaigns as $c)<option>{{ $c['name'] }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">القناة</label>
                <select class="form-select"><option value="">الكل</option><option value="whatsapp">واتساب</option><option value="sms">SMS</option></select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">الحالة</label>
                <select class="form-select"><option value="">الكل</option><option value="sent">أُرسل</option><option value="delivered">وصل</option><option value="read">قُرئ</option><option value="failed">فشل</option></select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">التاريخ</label>
                <input type="date" class="form-control">
            </div>
            <div class="col-md-1"><button class="btn btn-primary w-100"><i class="ti tabler-filter"></i></button></div>
        </div>
    </div>
</div>

<div class="card glass-card overflow-hidden">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><i class="ti tabler-messages me-2 text-primary"></i>الرسائل</h5>
        <span class="badge bg-label-primary">{{ count($logs) }} رسالة</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover fixit-table border-top mb-0">
            <thead>
                <tr><th>العميل</th><th>الهاتف</th><th>الحملة</th><th>القناة</th><th>الحالة</th><th>الوقت</th></tr>
            </thead>
            <tbody>
                @foreach($logs as $l)
                @php
                    $sm=['sent'=>['info','أُرسل','tabler-send'],'delivered'=>['success','وصل','tabler-circle-check'],'read'=>['primary','قُرئ','tabler-eye'],'pending'=>['secondary','معلق','tabler-clock'],'failed'=>['danger','فشل','tabler-x']];
                    [$lc,$ll,$li] = $sm[$l['status']] ?? ['secondary',$l['status'],'tabler-circle'];
                @endphp
                <tr>
                    <td class="fw-semibold">{{ $l['customer_name'] }}</td>
                    <td><a href="tel:{{ $l['customer_phone'] }}" class="text-muted small">{{ $l['customer_phone'] }}</a></td>
                    <td class="small text-muted">{{ $l['campaign'] }}</td>
                    <td>
                        @if($l['channel']==='whatsapp')
                            <span class="channel-badge whatsapp" style="font-size:0.7rem;padding:2px 8px"><i class="ti tabler-brand-whatsapp me-1"></i>واتساب</span>
                        @else
                            <span class="channel-badge sms" style="font-size:0.7rem;padding:2px 8px"><i class="ti tabler-message me-1"></i>SMS</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-label-{{ $lc }}">
                            <i class="ti {{ $li }} me-1"></i>{{ $ll }}
                        </span>
                    </td>
                    <td class="small text-muted">{{ $l['sent_at'] }}</td>
                </tr>
                @endforeach
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
