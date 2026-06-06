@extends('layouts/layoutMaster')
@section('title', 'تقرير الموارد البشرية - Fix-It')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
  <div>
    <h4 class="mb-0"><i class="ti tabler-users me-2"></i>تقرير الموارد البشرية</h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('dashboard.home') }}">الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <a href="{{ route('dashboard.reports') }}">التقارير</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>الموارد البشرية</span>
    </div>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-sm btn-label-success"><i class="ti tabler-file-spreadsheet me-1"></i> Excel</button>
    <button class="btn btn-sm btn-label-danger"><i class="ti tabler-file-type-pdf me-1"></i> PDF</button>
  </div>
</div>

@include('dashboard.reports._nav', ['active' => 'hr'])

{{-- Stats --}}
<div class="row g-4 mb-4">
  @foreach($hrStats as $s)
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

<div class="row g-4">
  <div class="col-lg-8">
    <div class="card glass-card overflow-hidden">
      <div class="card-header border-bottom"><h5 class="card-title mb-0"><i class="ti tabler-users me-2 text-primary"></i>كشف الموظفين والمرتبات</h5></div>
      <div class="table-responsive">
        <table class="table table-hover fixit-table border-top mb-0">
          <thead><tr><th>الموظف</th><th>الدور</th><th>القسم</th><th>نوع المرتب</th><th>المرتب</th><th>الحالة</th></tr></thead>
          <tbody>
            @foreach($employeesReport as $e)
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="avatar avatar-sm">
                    <span class="avatar-initial rounded-circle bg-label-primary">{{ mb_substr($e['name'],0,1) }}</span>
                  </div>
                  <span class="fw-semibold">{{ $e['name'] }}</span>
                </div>
              </td>
              <td class="small text-muted">{{ $e['role'] }}</td>
              <td><span class="badge bg-label-info">{{ $e['dept'] }}</span></td>
              <td>
                @php $tc = $e['salary_type']==='شهري'?'primary':($e['salary_type']==='تاسك'?'warning':'success'); @endphp
                <span class="badge bg-label-{{ $tc }}">{{ $e['salary_type'] }}</span>
              </td>
              <td class="fw-bold text-success">{{ number_format($e['salary']) }} EGP</td>
              <td>
                @php $sc = $e['status']==='نشط'?'success':'secondary'; @endphp
                <span class="badge bg-label-{{ $sc }}">{{ $e['status'] }}</span>
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot class="table-light">
            <tr>
              <td colspan="4" class="fw-bold text-muted">الإجمالي</td>
              <td class="fw-bold text-danger">{{ number_format(array_sum(array_column($employeesReport,'salary'))) }} EGP</td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card glass-card border-0">
      <div class="card-header border-bottom"><h5 class="card-title mb-0"><i class="ti tabler-chart-pie me-2 text-warning"></i>توزيع التكاليف</h5></div>
      <div class="card-body">
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1"><span class="small fw-semibold">المرتبات</span><span class="small fw-bold text-danger">28,500 EGP</span></div>
          <div class="progress" style="height:8px;border-radius:8px"><div class="progress-bar bg-danger" style="width:70%"></div></div>
        </div>
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1"><span class="small fw-semibold">المصاريف التشغيلية</span><span class="small fw-bold text-warning">8,200 EGP</span></div>
          <div class="progress" style="height:8px;border-radius:8px"><div class="progress-bar bg-warning" style="width:20%"></div></div>
        </div>
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1"><span class="small fw-semibold">مصاريف التسويق</span><span class="small fw-bold text-info">4,100 EGP</span></div>
          <div class="progress" style="height:8px;border-radius:8px"><div class="progress-bar bg-info" style="width:10%"></div></div>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
          <span class="fw-semibold">الإجمالي</span>
          <span class="fw-bold text-danger fs-5">40,800 EGP</span>
        </div>
        <div class="d-flex justify-content-between mt-1">
          <span class="small text-muted">صافي الربح</span>
          <span class="fw-bold text-success">1,700 EGP</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
