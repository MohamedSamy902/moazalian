@extends('layouts/layoutMaster')
@section('title', 'تقرير الحسابات المالية - Fix-It')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection
@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
  <div>
    <h4 class="mb-0"><i class="ti tabler-transfer-in me-2"></i>تقرير الحسابات المالية</h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('dashboard.home') }}">الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <a href="{{ route('dashboard.reports') }}">التقارير</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>الحسابات</span>
    </div>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-sm btn-label-success"><i class="ti tabler-file-spreadsheet me-1"></i> Excel</button>
    <button class="btn btn-sm btn-label-danger"><i class="ti tabler-file-type-pdf me-1"></i> PDF</button>
  </div>
</div>

@include('dashboard.reports._nav', ['active' => 'finance'])

{{-- Stats --}}
<div class="row g-4 mb-4">
  @foreach($finStats as $s)
  <div class="col-sm-6 col-xl-3">
    <div class="card glass-card border-0">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon gradient-{{ $s['color'] }} flex-shrink-0">
          <i class="ti {{ $s['icon'] }} text-white ti-md"></i>
        </div>
        <div><div class="card-label">{{ $s['label'] }}</div><div class="card-value mt-1">{{ $s['value'] }}</div></div>
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- Filter --}}
<div class="filter-bar mb-4">
  <div class="row g-3 align-items-end">
    <div class="col-md-3">
      <label class="form-label small fw-bold text-muted mb-1">من تاريخ</label>
      <input type="date" class="form-control" id="date-from">
    </div>
    <div class="col-md-3">
      <label class="form-label small fw-bold text-muted mb-1">إلى تاريخ</label>
      <input type="date" class="form-control" id="date-to">
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary w-100"><i class="ti tabler-search me-1"></i> بحث</button>
    </div>
    <div class="col-md-2">
      <button class="btn btn-label-secondary w-100" onclick="document.getElementById('date-from').value='';document.getElementById('date-to').value=''">
        <i class="ti tabler-refresh me-1"></i> إعادة ضبط
      </button>
    </div>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="card glass-card border-0">
      <div class="card-header border-bottom"><h5 class="card-title mb-0"><i class="ti tabler-chart-bar me-2 text-success"></i>التسويات الشهرية (كاش / فيزا / عمولة)</h5></div>
      <div class="card-body"><div id="finChart"></div></div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card glass-card border-0 h-100">
      <div class="card-header border-bottom"><h5 class="card-title mb-0"><i class="ti tabler-credit-card me-2 text-primary"></i>توزيع طرق الدفع</h5></div>
      <div class="card-body">
        @foreach($paymentMix as $p)
        <div class="mb-4">
          <div class="d-flex justify-content-between mb-1">
            <span class="fw-semibold">{{ $p['method'] }}</span>
            <span class="small text-muted">{{ $p['orders'] }} طلب</span>
          </div>
          <div class="progress" style="height:10px;border-radius:10px">
            <div class="progress-bar bg-{{ $p['color'] }}" style="width:{{ $p['pct'] }}%"></div>
          </div>
          <div class="d-flex justify-content-between mt-1">
            <span class="small text-muted">{{ $p['pct'] }}% من الإجمالي</span>
            <span class="fw-bold text-{{ $p['color'] }}">{{ number_format($p['amount']) }} EGP</span>
          </div>
        </div>
        @endforeach
        <hr>
        <div class="d-flex justify-content-between">
          <span class="fw-semibold">الإجمالي</span>
          <span class="fw-bold fs-5 text-success">42,500 EGP</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
const monthlyS = @json($monthlySettlements);
new ApexCharts(document.getElementById('finChart'), {
  series: [
    { name: 'كاش', data: monthlyS.cash },
    { name: 'فيزا', data: monthlyS.visa },
    { name: 'عمولة', data: monthlyS.commission }
  ],
  chart: { type: 'bar', height: 280, stacked: false, toolbar: { show: false }, fontFamily: 'inherit' },
  colors: ['#28c76f','#7367f0','#ff9f43'],
  xaxis: { categories: monthlyS.labels },
  yaxis: { labels: { formatter: v => v.toLocaleString('ar-EG') } },
  plotOptions: { bar: { columnWidth: '55%', borderRadius: 4 } },
  legend: { position: 'top' },
  grid: { borderColor: 'rgba(0,0,0,0.05)' }
}).render();
</script>
@endsection
