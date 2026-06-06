@extends('layouts/layoutMaster')
@section('title', 'تقرير الفنيين - Fix-It')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection
@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
  <div>
    <h4 class="mb-0"><i class="ti tabler-tools me-2"></i>تقرير الفنيين</h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('dashboard.home') }}">الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <a href="{{ route('dashboard.reports') }}">التقارير</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>الفنيون</span>
    </div>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-sm btn-label-success"><i class="ti tabler-file-spreadsheet me-1"></i> Excel</button>
    <button class="btn btn-sm btn-label-danger"><i class="ti tabler-file-type-pdf me-1"></i> PDF</button>
  </div>
</div>

@include('dashboard.reports._nav', ['active' => 'technicians'])

{{-- Stats --}}
<div class="row g-4 mb-4">
  @foreach($techStats as $s)
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
      <label class="form-label small fw-bold text-muted mb-1">بحث عن فني</label>
      <div class="input-group">
        <span class="input-group-text bg-transparent border-end-0"><i class="ti tabler-search text-muted"></i></span>
        <input type="text" class="form-control border-start-0 ps-0" id="tech-search" placeholder="اسم الفني...">
      </div>
    </div>
    <div class="col-md-2">
      <label class="form-label small fw-bold text-muted mb-1">الفترة</label>
      <select class="form-select">
        <option>هذا الشهر</option><option>الشهر الماضي</option><option>آخر 3 أشهر</option>
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-label-secondary w-100">
        <i class="ti tabler-refresh me-1"></i> إعادة ضبط
      </button>
    </div>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="card glass-card overflow-hidden">
      <div class="card-header border-bottom"><h5 class="card-title mb-0"><i class="ti tabler-tools me-2 text-primary"></i>أداء الفنيين</h5></div>
      <div class="table-responsive">
        <table class="table table-hover fixit-table border-top mb-0">
          <thead><tr><th>الفني</th><th>المركز</th><th>التخصص</th><th>الطلبات</th><th>الإيراد</th><th>التقييم</th><th>الحالة</th></tr></thead>
          <tbody>
            @foreach($techniciansReport as $t)
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="avatar avatar-sm">
                    <span class="avatar-initial rounded-circle bg-label-primary">{{ mb_substr($t['name'],0,1) }}</span>
                  </div>
                  <span class="fw-semibold">{{ $t['name'] }}</span>
                </div>
              </td>
              <td class="small text-muted">{{ $t['center'] }}</td>
              <td><span class="badge bg-label-info">{{ $t['specialty'] }}</span></td>
              <td><span class="badge bg-label-primary">{{ $t['orders'] }}</span></td>
              <td class="fw-bold text-success">{{ number_format($t['revenue']) }} EGP</td>
              <td><i class="ti tabler-star-filled text-warning small"></i> <span class="fw-semibold">{{ $t['rating'] }}</span></td>
              <td>
                @php $sc = $t['status']==='نشط'?'success':($t['status']==='في مهمة'?'warning':'secondary'); @endphp
                <span class="badge bg-label-{{ $sc }}">{{ $t['status'] }}</span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card glass-card border-0 h-100">
      <div class="card-header border-bottom"><h5 class="card-title mb-0"><i class="ti tabler-chart-donut me-2 text-info"></i>توزيع التخصصات</h5></div>
      <div class="card-body"><div id="techChart"></div></div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
new ApexCharts(document.getElementById('techChart'), {
  series: [45, 28, 22, 18, 15],
  labels: ['مكيفات', 'ثلاجات', 'غسالات', 'بوتاجاز', 'عام'],
  chart: { type: 'donut', height: 250, fontFamily: 'inherit' },
  colors: ['#7367f0','#00cfe8','#28c76f','#ff9f43','#ea5455'],
  legend: { position: 'bottom' },
  plotOptions: { pie: { donut: { size: '65%' } } }
}).render();
</script>
@endsection
