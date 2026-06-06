@extends('layouts/layoutMaster')
@section('title', 'الموارد البشرية - Fix-It')
@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection
@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-users-group me-2"></i>الموارد البشرية</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>نظرة عامة</span>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card glass-card border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon gradient-primary flex-shrink-0"><i class="ti tabler-users text-white ti-md"></i></div>
                <div>
                    <div class="card-label">موظفون نشطون</div>
                    <div class="card-value">{{ $stats['employees_count'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card glass-card border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon gradient-success flex-shrink-0"><i class="ti tabler-cash text-white ti-md"></i></div>
                <div>
                    <div class="card-label">إجمالي المرتبات / شهر</div>
                    <div class="card-value">{{ number_format($stats['total_monthly_salary']) }} <small>EGP</small></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card glass-card border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon gradient-warning flex-shrink-0"><i class="ti tabler-clock text-white ti-md"></i></div>
                <div>
                    <div class="card-label">مرتبات معلقة</div>
                    <div class="card-value text-warning">{{ number_format($stats['pending_salaries']) }} <small>EGP</small></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card glass-card border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon gradient-danger flex-shrink-0"><i class="ti tabler-receipt text-white ti-md"></i></div>
                <div>
                    <div class="card-label">إجمالي المصاريف</div>
                    <div class="card-value text-danger">{{ number_format($stats['total_expenses']) }} <small>EGP</small></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Departments --}}
    <div class="col-lg-4">
        <div class="card glass-card border-0 h-100">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="ti tabler-building me-2 text-primary"></i>الأقسام</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($departments as $dept)
                    <li class="list-group-item d-flex align-items-center justify-content-between px-4 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="dept-dot" style="background: {{ $dept['color'] }}"></span>
                            <span class="fw-semibold">{{ $dept['name'] }}</span>
                        </div>
                        <span class="badge bg-label-secondary">{{ $dept['employees_count'] }} موظف</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- Recent employees --}}
    <div class="col-lg-8">
        <div class="card glass-card border-0 h-100">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="ti tabler-users me-2 text-success"></i>الموظفون</h5>
                <a href="{{ route('dashboard.hr.employees.index') }}" class="btn btn-sm btn-label-primary">عرض الكل</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover fixit-table border-top mb-0">
                    <thead><tr><th>الموظف</th><th>الوظيفة</th><th>نوع الراتب</th><th>الراتب</th><th></th></tr></thead>
                    <tbody>
                        @foreach($employees as $emp)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-sm"><span class="avatar-initial rounded-circle bg-label-{{ $emp['avatar_color'] }}">{{ mb_substr($emp['name'],0,1) }}</span></div>
                                    <span class="fw-semibold">{{ $emp['name'] }}</span>
                                </div>
                            </td>
                            <td class="small text-muted">{{ $emp['role'] }}</td>
                            <td>
                                @php $st = ['monthly'=>['primary','شهري'],'per_task'=>['warning','بالتاسك'],'freelance'=>['info','فريلانس']][$emp['salary_type']] @endphp
                                <span class="badge bg-label-{{ $st[0] }}">{{ $st[1] }}</span>
                            </td>
                            <td>{{ $emp['salary_type']==='monthly' ? number_format($emp['base_salary']).' EGP' : '—' }}</td>
                            <td><a href="{{ route('dashboard.hr.employees.show', $emp['id']) }}" class="btn btn-sm btn-icon btn-label-primary"><i class="ti tabler-eye"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Payroll this month --}}
    <div class="col-lg-6">
        <div class="card glass-card border-0">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="ti tabler-cash me-2 text-warning"></i>مرتبات هذا الشهر</h5>
                <a href="{{ route('dashboard.hr.payroll') }}" class="btn btn-sm btn-label-warning">إدارة</a>
            </div>
            <div class="card-body">
                @foreach($payroll as $p)
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <span class="fw-semibold">{{ $p['employee_name'] }}</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold">{{ number_format($p['net_salary']) }} EGP</span>
                        <span class="badge {{ $p['status']==='paid' ? 'bg-label-success' : 'bg-label-warning' }}">{{ $p['status']==='paid' ? 'مصروف' : 'معلق' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Expenses chart --}}
    <div class="col-lg-6">
        <div class="card glass-card border-0">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="ti tabler-chart-donut me-2 text-danger"></i>توزيع المصاريف</h5>
                <a href="{{ route('dashboard.hr.expenses') }}" class="btn btn-sm btn-label-danger">عرض الكل</a>
            </div>
            <div class="card-body"><div id="expensesDonut"></div></div>
        </div>
    </div>
</div>

<style>
.dept-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.gradient-danger { background: linear-gradient(135deg, #ea5455, #f08182); }
</style>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    new ApexCharts(document.querySelector('#expensesDonut'), {
        series: [4900, 8000, 1200, 350],
        labels: ['تسويق', 'تشغيلي', 'تقني', 'أخرى'],
        chart: { type: 'donut', height: 280 },
        colors: ['#ff9f43', '#28c76f', '#00cfe8', '#82868b'],
        legend: { position: 'bottom', fontFamily: 'inherit' },
        dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '65%' } } },
        tooltip: { y: { formatter: v => v.toLocaleString('ar-EG') + ' EGP' } }
    }).render();
});
</script>
@endsection
