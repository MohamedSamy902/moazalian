@extends('layouts/layoutMaster')
@section('title', 'تفاصيل كود الخصم - Fix-It')
@section('content')
<div class="row">
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card glass-card">
            <div class="card-body text-center">
                <div class="badge bg-label-success mb-3 status-badge">نشط</div>
                <h2 class="mb-1 fw-bold text-primary">{{ $promo['discount'] }}</h2>
                <h4 class="mb-4">{{ $promo['code'] }}</h4>
                
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">إجمالي الاستخدامات:</span>
                    <span class="fw-bold">{{ $promo['usage_count'] }} / {{ $promo['max_uses'] }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">إجمالي التوفير:</span>
                    <span class="fw-bold text-success">{{ number_format($promo['total_saved']) }} EGP</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">تاريخ الانتهاء:</span>
                    <span class="fw-bold text-danger">{{ $promo['expires_at'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-lg-7">
        <div class="card glass-card">
            <h5 class="card-header border-bottom">سجل استخدام الكود</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>العميل</th>
                            <th>رقم الطلب</th>
                            <th>المبلغ الموفر</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promo['recent_usage'] as $usage)
                        <tr>
                            <td>{{ $usage['customer'] }}</td>
                            <td><span class="fw-bold">{{ $usage['order'] }}</span></td>
                            <td><span class="text-success">{{ number_format($usage['saved']) }} EGP</span></td>
                            <td>{{ $usage['date'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
