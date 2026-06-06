@extends('layouts/layoutMaster')
@section('title', 'تقرير المراكز - Fix-It')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
  <div>
    <h4 class="mb-0"><i class="ti tabler-building-store me-2"></i>تقرير المراكز</h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('dashboard.home') }}">الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <a href="{{ route('dashboard.reports') }}">التقارير</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>المراكز</span>
    </div>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-sm btn-label-success"><i class="ti tabler-file-spreadsheet me-1"></i> Excel</button>
    <button class="btn btn-sm btn-label-danger"><i class="ti tabler-file-type-pdf me-1"></i> PDF</button>
  </div>
</div>

@include('dashboard.reports._nav', ['active' => 'centers'])

{{-- Stats --}}
<div class="row g-4 mb-4">
  @foreach($centersStats as $s)
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
      <label class="form-label small fw-bold text-muted mb-1">بحث عن مركز</label>
      <div class="input-group">
        <span class="input-group-text bg-transparent border-end-0"><i class="ti tabler-search text-muted"></i></span>
        <input type="text" class="form-control border-start-0 ps-0" id="center-search" placeholder="اسم المركز...">
      </div>
    </div>
    <div class="col-md-2">
      <label class="form-label small fw-bold text-muted mb-1">الفترة</label>
      <select class="form-select">
        <option>هذا الشهر</option><option>الشهر الماضي</option><option>آخر 3 أشهر</option><option>هذا العام</option>
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-label-secondary w-100" onclick="document.getElementById('center-search').value=''">
        <i class="ti tabler-refresh me-1"></i> إعادة ضبط
      </button>
    </div>
  </div>
</div>

{{-- Table --}}
<div class="card glass-card overflow-hidden">
  <div class="card-header border-bottom d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0"><i class="ti tabler-building-store me-2 text-success"></i>تفصيل أداء المراكز</h5>
  </div>
  <div class="table-responsive">
    <table class="table table-hover fixit-table border-top mb-0">
      <thead><tr><th>#</th><th>المركز</th><th>المدينة</th><th>الطلبات</th><th>الإيراد</th><th>العمولة</th><th>الفنيون</th><th>الإنجاز</th><th>التقييم</th></tr></thead>
      <tbody>
        @foreach($centersReport as $i => $c)
        <tr>
          <td><span class="badge bg-label-{{ $i===0?'warning':($i===1?'secondary':'primary') }}">{{ $i+1 }}</span></td>
          <td class="fw-semibold">{{ $c['name'] }}</td>
          <td class="text-muted small">{{ $c['city'] }}</td>
          <td><span class="badge bg-label-primary">{{ $c['orders'] }}</span></td>
          <td class="fw-bold text-success">{{ number_format($c['revenue']) }} EGP</td>
          <td class="text-warning fw-semibold">{{ number_format($c['commission']) }} EGP</td>
          <td>{{ $c['technicians'] }} فني</td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="progress flex-grow-1" style="height:6px;border-radius:6px;min-width:60px">
                <div class="progress-bar {{ $c['completion_rate']>=90?'bg-success':($c['completion_rate']>=80?'bg-warning':'bg-danger') }}"
                     style="width:{{ $c['completion_rate'] }}%"></div>
              </div>
              <small class="fw-semibold">{{ $c['completion_rate'] }}%</small>
            </div>
          </td>
          <td><i class="ti tabler-star-filled text-warning small"></i> <span class="fw-semibold">{{ $c['rating'] }}</span></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
