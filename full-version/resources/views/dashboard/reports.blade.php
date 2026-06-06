@extends('layouts/layoutMaster')
@section('title', 'التقارير الشاملة - Fix-It')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection
@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('content')

{{-- ══ Page Header ══════════════════════════════════════════════ --}}
<div class="page-header d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="mb-0"><i class="ti tabler-chart-bar me-2"></i>التقارير الشاملة</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}">الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>التقارير</span>
        </div>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <select class="form-select form-select-sm w-auto">
            <option>هذا الشهر</option><option>الشهر الماضي</option>
            <option>آخر 3 أشهر</option><option>هذا العام</option>
        </select>
        <button class="btn btn-sm btn-label-success"><i class="ti tabler-file-spreadsheet me-1"></i> Excel</button>
        <button class="btn btn-sm btn-label-danger"><i class="ti tabler-file-type-pdf me-1"></i> PDF</button>
    </div>
</div>

{{-- ══ Global KPI Cards ═══════════════════════════════════════════ --}}
<div class="row g-4 mb-4">
    @foreach($stats as $s)
    <div class="col-sm-6 col-xl-3">
        <div class="card glass-card border-0">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon gradient-{{ $s['color'] }} flex-shrink-0">
                    <i class="ti {{ $s['icon'] }} text-white ti-md"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="card-label">{{ $s['label'] }}</div>
                    <div class="card-value mt-1">{{ $s['value'] }}</div>
                    <div class="small mt-1">
                        <span class="text-{{ $s['trend']==='up'?'success':'danger' }} fw-semibold">
                            <i class="ti tabler-trending-{{ $s['trend'] }}" style="font-size:0.7rem"></i> {{ $s['change'] }}
                        </span>
                        <span class="text-muted ms-1">مقارنة بالشهر السابق</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ══ Report Pages Navigation (بدون Tabs) ══════════════════════ --}}
<div class="mb-4">
    <h6 class="text-muted fw-semibold mb-3 small text-uppercase" style="letter-spacing:0.05em">
        <i class="ti tabler-layout-grid me-1"></i> اختر قسم التقرير
    </h6>
    <div class="row g-3">

        {{-- Overview --}}
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('dashboard.reports.overview') }}" class="text-decoration-none">
                <div class="card report-nav-card border-0 h-100" style="border-right:4px solid #7367f0 !important;">
                    <div class="card-body d-flex align-items-center gap-4 p-4">
                        <div class="report-nav-icon" style="background:rgba(115,103,240,0.12);width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="ti tabler-chart-line text-primary" style="font-size:1.6rem"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-body mb-1">النظرة العامة</div>
                            <div class="text-muted small">الإيرادات، الطلبات، والأداء العام</div>
                        </div>
                        <i class="ti tabler-arrow-left text-muted"></i>
                    </div>
                </div>
            </a>
        </div>

        {{-- Centers --}}
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('dashboard.reports.centers') }}" class="text-decoration-none">
                <div class="card report-nav-card border-0 h-100" style="border-right:4px solid #28c76f !important;">
                    <div class="card-body d-flex align-items-center gap-4 p-4">
                        <div class="report-nav-icon" style="background:rgba(40,199,111,0.12);width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="ti tabler-building-store text-success" style="font-size:1.6rem"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-body mb-1">تقرير المراكز</div>
                            <div class="text-muted small">أداء مراكز الصيانة والعمولات</div>
                        </div>
                        <i class="ti tabler-arrow-left text-muted"></i>
                    </div>
                </div>
            </a>
        </div>

        {{-- Technicians --}}
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('dashboard.reports.technicians') }}" class="text-decoration-none">
                <div class="card report-nav-card border-0 h-100" style="border-right:4px solid #00cfe8 !important;">
                    <div class="card-body d-flex align-items-center gap-4 p-4">
                        <div class="report-nav-icon" style="background:rgba(0,207,232,0.12);width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="ti tabler-tools text-info" style="font-size:1.6rem"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-body mb-1">تقرير الفنيين</div>
                            <div class="text-muted small">إنتاجية الفنيين وتقييماتهم</div>
                        </div>
                        <i class="ti tabler-arrow-left text-muted"></i>
                    </div>
                </div>
            </a>
        </div>

        {{-- HR --}}
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('dashboard.reports.hr') }}" class="text-decoration-none">
                <div class="card report-nav-card border-0 h-100" style="border-right:4px solid #ff9f43 !important;">
                    <div class="card-body d-flex align-items-center gap-4 p-4">
                        <div class="report-nav-icon" style="background:rgba(255,159,67,0.12);width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="ti tabler-users text-warning" style="font-size:1.6rem"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-body mb-1">الموارد البشرية</div>
                            <div class="text-muted small">المرتبات والمصاريف التشغيلية</div>
                        </div>
                        <i class="ti tabler-arrow-left text-muted"></i>
                    </div>
                </div>
            </a>
        </div>

        {{-- Finance --}}
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('dashboard.reports.finance') }}" class="text-decoration-none">
                <div class="card report-nav-card border-0 h-100" style="border-right:4px solid #ea5455 !important;">
                    <div class="card-body d-flex align-items-center gap-4 p-4">
                        <div class="report-nav-icon" style="background:rgba(234,84,85,0.12);width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="ti tabler-transfer-in text-danger" style="font-size:1.6rem"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-body mb-1">الحسابات المالية</div>
                            <div class="text-muted small">التسويات وطرق الدفع</div>
                        </div>
                        <i class="ti tabler-arrow-left text-muted"></i>
                    </div>
                </div>
            </a>
        </div>

        {{-- Quick Chart preview --}}
        <div class="col-md-6 col-xl-4">
            <div class="card glass-card border-0 h-100">
                <div class="card-body p-4">
                    <div class="fw-bold text-body mb-1 small">ملخص سريع — الطلبات الشهرية</div>
                    <div id="miniChart"></div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ══ Top Devices Quick View ══════════════════════════════════ --}}
<div class="card glass-card border-0">
    <div class="card-header border-bottom">
        <h6 class="card-title mb-0"><i class="ti tabler-devices me-2 text-warning"></i>أكثر الأجهزة طلباً</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($topDevices as $d)
            <div class="col-md-6 col-xl-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="flex-shrink-0 fw-bold text-muted" style="width:24px;text-align:center">{{ $loop->iteration }}</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small fw-semibold">{{ $d['name'] }}</span>
                            <span class="small text-muted">{{ $d['orders'] }} طلب ({{ $d['pct'] }}%)</span>
                        </div>
                        <div class="progress" style="height:7px;border-radius:7px">
                            <div class="progress-bar bg-{{ $d['color'] }}" style="width:{{ $d['pct'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@section('page-script')
<script>
new ApexCharts(document.getElementById('miniChart'), {
  series: [{ name: 'الطلبات', data: @json($monthly['orders']) }],
  chart: { type: 'area', height: 80, sparkline: { enabled: true }, toolbar: { show: false } },
  colors: ['#7367f0'],
  fill: { opacity: 0.2 },
  stroke: { width: 2, curve: 'smooth' },
  tooltip: { fixed: { enabled: false }, x: { show: false } }
}).render();
</script>
@endsection

@section('page-style')
<style>
.report-nav-card {
  transition: transform 0.18s ease, box-shadow 0.18s ease;
  cursor: pointer;
}
.report-nav-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important;
}
</style>
@endsection
