@extends('layouts/layoutMaster')
@section('title', 'الحملات - Fix-It')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-speakerphone me-2"></i>الحملات</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}">الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <a href="{{ route('dashboard.messaging.overview') }}">مركز الرسائل</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>الحملات</span>
        </div>
    </div>
    <a href="{{ route('dashboard.messaging.campaigns.create') }}" class="btn btn-primary">
        <i class="ti tabler-plus me-1"></i> حملة جديدة
    </a>
</div>

<div class="card glass-card overflow-hidden">
    <div class="card-header border-bottom d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0"><i class="ti tabler-list me-2 text-primary"></i>جميع الحملات</h5>
        <select class="form-select form-select-sm w-auto">
            <option value="">كل الحالات</option>
            <option>مكتملة</option><option>يُرسل</option><option>مجدولة</option><option>مسودة</option>
        </select>
    </div>
    <div class="table-responsive">
        <table class="table table-hover fixit-table border-top mb-0">
            <thead>
                <tr>
                    <th>الحملة</th>
                    <th>القناة</th>
                    <th>المستلمون</th>
                    <th>وصل</th>
                    <th>قُرئ</th>
                    <th>نسبة التسليم</th>
                    <th>الحالة</th>
                    <th>الوقت</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaigns as $c)
                @php
                    $sm=['completed'=>['success','مكتملة'],'scheduled'=>['warning','مجدولة'],'sending'=>['info','يُرسل'],'draft'=>['secondary','مسودة'],'failed'=>['danger','فشلت']];
                    [$sc,$sl] = $sm[$c['status']] ?? ['secondary',$c['status']];
                    $rate = $c['sent_count'] > 0 ? round($c['delivered_count']/$c['sent_count']*100) : 0;
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $c['name'] }}</div>
                        <div class="small text-muted">{{ $c['template_name'] }}</div>
                    </td>
                    <td>
                        @if($c['channel']==='whatsapp') <span class="channel-badge whatsapp"><i class="ti tabler-brand-whatsapp me-1"></i>واتساب</span>
                        @elseif($c['channel']==='sms') <span class="channel-badge sms"><i class="ti tabler-message me-1"></i>SMS</span>
                        @else <span class="channel-badge both"><i class="ti tabler-device-mobile me-1"></i>الاثنان</span>
                        @endif
                    </td>
                    <td class="fw-semibold">{{ number_format($c['recipients_count']) }}</td>
                    <td>{{ number_format($c['delivered_count']) }}</td>
                    <td>{{ number_format($c['read_count']) }}</td>
                    <td>
                        @if($c['sent_count'] > 0)
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-fill" style="height:6px;">
                                <div class="progress-bar bg-{{ $rate>=80?'success':($rate>=50?'warning':'danger') }}"
                                     style="width:{{ $rate }}%"></div>
                            </div>
                            <small class="fw-semibold">{{ $rate }}%</small>
                        </div>
                        @else <span class="text-muted small">—</span>@endif
                    </td>
                    <td>
                        <span class="badge bg-label-{{ $sc }}">
                            @if($c['status']==='sending')<span class="spinner-border spinner-border-sm me-1" style="width:0.6rem;height:0.6rem"></span>@endif
                            {{ $sl }}
                        </span>
                    </td>
                    <td class="small text-muted">
                        @if($c['sent_at']) {{ $c['sent_at'] }}
                        @elseif($c['scheduled_at']) <span class="text-warning"><i class="ti tabler-calendar me-1"></i>{{ $c['scheduled_at'] }}</span>
                        @else <span class="text-muted">—</span>@endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('dashboard.messaging.campaigns.show', $c['id']) }}" class="btn btn-sm btn-icon btn-label-primary"><i class="ti tabler-eye"></i></a>
                            @if($c['status']==='draft')
                            <button class="btn btn-sm btn-icon btn-label-success" title="إطلاق"><i class="ti tabler-rocket"></i></button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
.channel-badge{display:inline-flex;align-items:center;font-size:0.78rem;font-weight:600;padding:3px 10px;border-radius:20px;}
.channel-badge.whatsapp{background:rgba(37,211,102,0.12);color:#25d366;border:1px solid rgba(37,211,102,0.25);}
.channel-badge.sms{background:rgba(0,207,232,0.12);color:#00cfe8;border:1px solid rgba(0,207,232,0.25);}
.channel-badge.both{background:rgba(115,103,240,0.12);color:#7367f0;border:1px solid rgba(115,103,240,0.25);}
</style>
@endsection
