@extends('layouts/layoutMaster')
@section('title', 'الماليات والعمولات - Fix-It')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-report-money"></i> التسويات المالية والعمولات</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>الماليات</span>
        </div>
    </div>
    <button class="btn btn-label-primary ms-auto">
        <i class="ti tabler-download me-1"></i> تحميل تقرير PDF
    </button>
</div>

<!-- بطاقات الإحصائيات -->
<div class="row mb-4">
    <div class="col-lg-3 col-sm-6 mb-4 mb-lg-0">
        <div class="card glass-card border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="stat-icon gradient-primary me-3">
                        <i class="ti tabler-wallet text-white ti-md"></i>
                    </div>
                    <div>
                        <div class="card-label">إجمالي الإيرادات</div>
                        <div class="card-value">{{ number_format($stats['total_revenue']) }} <small>EGP</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-4 mb-lg-0">
        <div class="card glass-card border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="stat-icon gradient-success me-3">
                        <i class="ti tabler-chart-pie text-white ti-md"></i>
                    </div>
                    <div>
                        <div class="card-label">عمولة المنصة</div>
                        <div class="card-value">{{ number_format($stats['platform_commission']) }} <small>EGP</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-4 mb-sm-0">
        <div class="card glass-card border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="stat-icon gradient-warning me-3">
                        <i class="ti tabler-hourglass text-white ti-md"></i>
                    </div>
                    <div>
                        <div class="card-label">تسويات معلقة</div>
                        <div class="card-value text-warning">{{ number_format($stats['pending_settlements']) }} <small>EGP</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card glass-card border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="stat-icon gradient-info me-3">
                        <i class="ti tabler-check text-white ti-md"></i>
                    </div>
                    <div>
                        <div class="card-label">تسويات مدفوعة</div>
                        <div class="card-value text-success">{{ number_format($stats['paid_settlements']) }} <small>EGP</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- رسم بياني -->
    <div class="col-md-12 mb-4">
        <div class="card glass-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">تحليل الإيرادات والعمولات</h5>
                <select class="form-select form-select-sm w-auto">
                    <option>أخر 30 يوم</option>
                    <option>أخر 6 أشهر</option>
                </select>
            </div>
            <div class="card-body">
                <div id="financialChart"></div>
            </div>
        </div>
    </div>

    <!-- جدول العمليات -->
    <div class="col-md-12">
        <div class="card glass-card overflow-hidden">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">أخر التحويلات والتسويات</h5>
            </div>
            <div class="card-datatable table-responsive">
                <table class="table table-hover fixit-table border-top">
                    <thead>
                        <tr>
                            <th>رقم العملية</th>
                            <th>المركز / الجهة</th>
                            <th>المبلغ الإجمالي</th>
                            <th>عمولة المنصة</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $trx)
                        <tr>
                            <td><span class="fw-bold">{{ $trx['id'] }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xs me-2">
                                        <span class="avatar-initial rounded-circle bg-label-secondary">{{ mb_substr($trx['center'], 0, 1) }}</span>
                                    </div>
                                    <span>{{ $trx['center'] }}</span>
                                </div>
                            </td>
                            <td>{{ number_format($trx['amount']) }} EGP</td>
                            <td><span class="text-primary fw-bold">+{{ number_format($trx['commission']) }} EGP</span></td>
                            <td>{{ $trx['date'] }}</td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'paid' => 'bg-label-success',
                                        'pending' => 'bg-label-warning',
                                        'cancelled' => 'bg-label-danger'
                                    ][$trx['status']];
                                    $statusName = [
                                        'paid' => 'مدفوع',
                                        'pending' => 'قيد المعالجة',
                                        'cancelled' => 'ملغي'
                                    ][$trx['status']];
                                @endphp
                                <span class="badge {{ $badgeClass }} status-badge">{{ $statusName }}</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-label-primary"><i class="ti tabler-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-label-secondary"><i class="ti tabler-printer"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const options = {
        series: [{
            name: 'إجمالي الإيرادات',
            data: [3100, 4000, 2800, 5100, 4200, 10900, 10000]
        }, {
            name: 'عمولة المنصة',
            data: [310, 400, 280, 510, 420, 1090, 1000]
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: { show: false }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' },
        colors: ['#7367f0', '#28c76f'],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        },
        tooltip: {
            y: { formatter: (val) => val + " EGP" }
        }
    };

    const chart = new ApexCharts(document.querySelector("#financialChart"), options);
    chart.render();
});
</script>
@endsection