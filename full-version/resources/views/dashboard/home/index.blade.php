@extends('layouts/layoutMaster')
@section('title', 'الرئيسية - Fix-It')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('content')
{{-- Page Header --}}
<div class="page-header mb-5">
  <div>
    <h4><i class="ti tabler-layout-dashboard"></i> لوحة التحكم</h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="#"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>نظرة عامة على النظام</span>
    </div>
  </div>
</div>

{{-- Stats Row --}}
<div class="row g-4 mb-4">
  <div class="col-lg-3 col-sm-6 fade-in-up">
    <div class="stat-card card p-4">
      <div class="d-flex align-items-center">
        <div class="stat-icon gradient-primary me-3">
          <i class="ti tabler-briefcase text-white" style="font-size:1.4rem"></i>
        </div>
        <div class="flex-grow-1">
          <div class="card-label">إجمالي الطلبات</div>
          <div class="card-value">{{ number_format($stats['total_orders']) }}</div>
        </div>
      </div>
      <div class="stat-trend text-success mt-2">
        <i class="ti tabler-trending-up"></i> +12% من الشهر الماضي
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-sm-6 fade-in-up">
    <div class="stat-card card p-4">
      <div class="d-flex align-items-center">
        <div class="stat-icon gradient-success me-3">
          <i class="ti tabler-coins text-white" style="font-size:1.4rem"></i>
        </div>
        <div class="flex-grow-1">
          <div class="card-label">إجمالي الأرباح</div>
          <div class="card-value">{{ number_format($stats['total_earnings']) }} <small class="text-muted fs-6 fw-400">EGP</small></div>
        </div>
      </div>
      <div class="stat-trend text-success mt-2">
        <i class="ti tabler-trending-up"></i> +8% زيادة هذا الشهر
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-sm-6 fade-in-up">
    <div class="stat-card card p-4">
      <div class="d-flex align-items-center">
        <div class="stat-icon gradient-warning me-3">
          <i class="ti tabler-building-store text-white" style="font-size:1.4rem"></i>
        </div>
        <div class="flex-grow-1">
          <div class="card-label">مراكز الصيانة</div>
          <div class="card-value">{{ $stats['active_centers'] }}</div>
        </div>
      </div>
      <div class="stat-trend text-muted mt-2">
        <i class="ti tabler-plus"></i> 15 مركز جديد هذا الأسبوع
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-sm-6 fade-in-up">
    <div class="stat-card card p-4">
      <div class="d-flex align-items-center">
        <div class="stat-icon gradient-info me-3">
          <i class="ti tabler-tools text-white" style="font-size:1.4rem"></i>
        </div>
        <div class="flex-grow-1">
          <div class="card-label">الفنيين النشطين</div>
          <div class="card-value">{{ $stats['registered_technicians'] }}</div>
        </div>
      </div>
      <div class="stat-trend text-info mt-2">
        <i class="ti tabler-map-pin"></i> تغطية 12 منطقة
      </div>
    </div>
  </div>
</div>

{{-- Charts + Recent Orders --}}
<div class="row g-4">
  {{-- Chart --}}
  <div class="col-lg-8">
    <div class="card glass-card h-100">
      <div class="card-header d-flex justify-content-between align-items-center border-0 pt-4 pb-0 px-4">
        <div>
          <h5 class="card-title mb-0 fw-bold">تحليل نمو الطلبات</h5>
          <small class="text-muted">آخر 7 أيام</small>
        </div>
        <div class="d-flex gap-2">
          <span class="badge bg-label-primary px-3 py-2">إجمالي: {{ array_sum($chartData['data']) }} طلب</span>
        </div>
      </div>
      <div class="card-body pt-3 px-2">
        <div id="ordersTrendChart"></div>
      </div>
    </div>
  </div>

  {{-- Recent Orders --}}
  <div class="col-lg-4">
    <div class="card glass-card h-100">
      <div class="card-header d-flex justify-content-between align-items-center border-0 pt-4 pb-0 px-4">
        <h5 class="card-title mb-0 fw-bold">أحدث الطلبات</h5>
        <a href="{{ route('dashboard.orders.index') }}" class="btn btn-sm btn-label-primary">عرض الكل</a>
      </div>
      <div class="card-body p-0 pt-2">
        <ul class="list-group list-group-flush px-2">
          @foreach($recentOrders as $order)
          @php
            $badgeClass = ['pending'=>'bg-label-warning','completed'=>'bg-label-success','in_progress'=>'bg-label-info'][$order['status']] ?? 'bg-label-secondary';
            $statusName = ['pending'=>'قيد الانتظار','completed'=>'مكتمل','in_progress'=>'قيد التنفيذ'][$order['status']] ?? '-';
            $colors     = ['bg-label-primary','bg-label-info','bg-label-success','bg-label-warning','bg-label-danger'];
            $color      = $colors[array_search($order, $recentOrders) % count($colors)];
          @endphp
          <li class="list-group-item d-flex align-items-center gap-3 px-3 py-3 border-0" style="border-bottom: 1px solid rgba(0,0,0,0.05) !important;">
            <div class="avatar avatar-sm flex-shrink-0">
              <span class="avatar-initial rounded-circle {{ $color }}">{{ mb_substr($order['customer'], 0, 1) }}</span>
            </div>
            <div class="flex-grow-1 overflow-hidden">
              <div class="fw-semibold text-truncate">{{ $order['customer'] }}</div>
              <small class="text-muted text-truncate d-block">{{ $order['device'] }}</small>
            </div>
            <span class="badge {{ $badgeClass }} status-badge flex-shrink-0">{{ $statusName }}</span>
          </li>
          @endforeach
        </ul>
      </div>
      <div class="card-footer border-0 text-center pb-3">
        <a href="{{ route('dashboard.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
          <i class="ti tabler-external-link me-1"></i> عرض جميع الطلبات
        </a>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
  const textColor  = isDark ? '#b0b0c0' : '#6f6b7d';
  const gridColor  = isDark ? 'rgba(255,255,255,0.06)' : '#f1f1f1';

  const chartOptions = {
    series: [{ name: 'الطلبات', data: {!! json_encode($chartData['data']) !!} }],
    chart: {
      height: 300,
      type: 'area',
      toolbar: { show: false },
      zoom:    { enabled: false },
      animations: { enabled: true, easing: 'easeinout', speed: 600 }
    },
    dataLabels: { enabled: false },
    stroke:  { curve: 'smooth', width: 3 },
    colors:  ['#7367f0'],
    markers: { size: 5, colors: ['#7367f0'], strokeColors: '#fff', strokeWidth: 2, hover: { size: 7 } },
    fill: {
      type: 'gradient',
      gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 90, 100] }
    },
    xaxis: {
      categories: {!! json_encode($chartData['labels']) !!},
      axisBorder: { show: false },
      axisTicks:  { show: false },
      labels:     { style: { colors: textColor, fontSize: '12px', fontFamily: 'Cairo' } }
    },
    yaxis: { labels: { show: false } },
    grid: {
      borderColor: gridColor,
      strokeDashArray: 5,
      xaxis: { lines: { show: true } },
      yaxis: { lines: { show: false } },
      padding: { top: 0, right: 0, bottom: 0, left: 10 }
    },
    tooltip: {
      theme: isDark ? 'dark' : 'light',
      style: { fontFamily: 'Cairo' },
      y: { formatter: (val) => val + ' طلب' }
    }
  };

  const chart = new ApexCharts(document.querySelector('#ordersTrendChart'), chartOptions);
  chart.render();
});
</script>
@endsection
