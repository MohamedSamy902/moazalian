@extends('layouts/layoutMaster')
@section('title', 'تقرير النظرة العامة - Fix-It')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection
@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
  <div>
    <h4 class="mb-0"><i class="ti tabler-chart-line me-2"></i>تقرير النظرة العامة</h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('dashboard.home') }}">الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <a href="{{ route('dashboard.reports') }}">التقارير</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>النظرة العامة</span>
    </div>
  </div>
    <button class="btn btn-sm btn-label-primary" data-bs-toggle="offcanvas" data-bs-target="#reportFilterOffcanvas">
      <i class="ti tabler-filter me-1"></i> تصفية متقدمة
    </button>
    <div class="dropdown">
       <button class="btn btn-sm btn-label-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <i class="ti tabler-calendar-stats me-1"></i> الفترة: آخر 30 يوم
       </button>
       <ul class="dropdown-menu">
          <li><a class="dropdown-item active" href="#">آخر 30 يوم</a></li>
          <li><a class="dropdown-item" href="#">هذا الشهر</a></li>
          <li><a class="dropdown-item" href="#">الربع الأخير</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#">فترة مخصصة...</a></li>
       </ul>
    </div>
    <button class="btn btn-sm btn-label-success"><i class="ti tabler-file-spreadsheet me-1"></i> Excel</button>
  </div>
</div>

{{-- Report Navigation --}}
@include('dashboard.reports._nav', ['active' => 'overview'])

{{-- KPI Cards --}}
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

{{-- Charts --}}
<div class="row g-4">
  <div class="col-lg-8">
    <div class="card glass-card border-0 h-100">
      <div class="card-header border-bottom d-flex justify-content-between align-items-center">
         <h5 class="card-title mb-0"><i class="ti tabler-chart-line me-2 text-primary"></i>الإيراد والطلبات الشهرية</h5>
         <div class="form-check form-switch small">
            <input class="form-check-input" type="checkbox" id="comparePrevious" checked>
            <label class="form-check-label" for="comparePrevious">مقارنة بالفترة السابقة</label>
         </div>
      </div>
      <div class="card-body"><div id="revenueChart"></div></div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card glass-card border-0 h-100">
      <div class="card-header border-bottom"><h5 class="card-title mb-0"><i class="ti tabler-devices me-2 text-warning"></i>توزيع الأجهزة</h5></div>
      <div class="card-body">
        @foreach($topDevices as $d)
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1">
            <span class="small fw-semibold">{{ $d['name'] }}</span>
            <span class="small text-muted">{{ $d['orders'] }} طلب ({{ $d['pct'] }}%)</span>
          </div>
          <div class="progress" style="height:8px;border-radius:8px">
            <div class="progress-bar bg-{{ $d['color'] }}" style="width:{{ $d['pct'] }}%"></div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
  </div>
</div>

{{-- Report Filter Offcanvas --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="reportFilterOffcanvas" style="width: 400px;">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title"><i class="ti tabler-filter me-2"></i>تصفية التقارير</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <form>
       <div class="mb-3">
          <label class="form-label fw-bold">نطاق التاريخ</label>
          <input type="text" class="form-control flatpickr-range" placeholder="اختر الفترة...">
       </div>
       <div class="mb-3">
          <label class="form-label fw-bold">مركز الصيانة</label>
          <select class="form-select select2">
             <option value="">كل المراكز</option>
             <option>المركز السعودي</option>
             <option>مركز النخبة</option>
          </select>
       </div>
       <div class="mb-3">
          <label class="form-label fw-bold">المنطقة الجغرافية</label>
          <select class="form-select select2">
             <option value="">كل المناطق</option>
             <option>القاهرة</option>
             <option>الرياض</option>
             <option>جدة</option>
          </select>
       </div>
       <div class="mb-4">
          <label class="form-label fw-bold">نوع الخدمة</label>
          <div class="d-flex flex-wrap gap-2">
             <div class="form-check">
                <input class="form-check-input" type="checkbox" id="srv-ac" checked>
                <label class="form-check-label small" for="srv-ac">مكيفات</label>
             </div>
             <div class="form-check">
                <input class="form-check-input" type="checkbox" id="srv-fr" checked>
                <label class="form-check-label small" for="srv-fr">ثلاجات</label>
             </div>
             <div class="form-check">
                <input class="form-check-input" type="checkbox" id="srv-ws" checked>
                <label class="form-check-label small" for="srv-ws">غسالات</label>
             </div>
          </div>
       </div>
       <div class="d-grid gap-2 pt-3 border-top">
          <button type="button" class="btn btn-primary">تطبيق التصفية</button>
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">إلغاء</button>
       </div>
    </form>
  </div>
</div>
@endsection

@section('page-script')
<script>
const monthly = @json($monthly);
new ApexCharts(document.getElementById('revenueChart'), {
  series: [
    { name: 'الإيراد (EGP)', type: 'area', data: monthly.revenue },
    { name: 'الطلبات', type: 'bar', data: monthly.orders }
  ],
  chart: { height: 280, toolbar: { show: false }, fontFamily: 'inherit' },
  colors: ['#7367f0','#28c76f'],
  xaxis: { categories: monthly.labels },
  yaxis: [
    { labels: { formatter: v => v.toLocaleString('ar-EG') + ' EGP' } },
    { opposite: true, labels: { formatter: v => v + ' طلب' } }
  ],
  fill: { opacity: [0.15, 1] },
  stroke: { width: [2, 0], curve: 'smooth' },
  tooltip: { shared: true },
  legend: { position: 'top' },
  grid: { borderColor: 'rgba(0,0,0,0.05)' }
}).render();
</script>
@endsection
