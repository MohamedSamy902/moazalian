@extends('layouts/layoutMaster')
@section('title', 'المناطق - Fix-It')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/select2/select2.js'])
@endsection

@section('content')
{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h4 class="mb-0"><i class="ti tabler-location"></i> إدارة المناطق</h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>المواقع</span>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>المناطق</span>
    </div>
  </div>
  <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addAreaModal">
    <i class="ti tabler-plus me-1"></i> إضافة منطقة جديدة
  </button>
</div>

{{-- Filter Bar --}}
<div class="filter-bar mb-4">
  <div class="row g-3 align-items-end">
    <div class="col-md-3">
      <label class="form-label small fw-bold text-muted mb-1">بحث</label>
      <div class="input-group">
        <span class="input-group-text bg-transparent border-end-0"><i class="ti tabler-search text-muted"></i></span>
        <input type="text" class="form-control border-start-0 ps-0 ajax-filter" placeholder="اسم المنطقة...">
      </div>
    </div>
    <div class="col-md-3">
      <label class="form-label small fw-bold text-muted mb-1">المدينة</label>
      <select class="form-select select2 ajax-filter">
        <option value="">كل المدن</option>
        <option>الرياض</option>
        <option>جدة</option>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label small fw-bold text-muted mb-1">الحي</label>
      <select class="form-select select2 ajax-filter">
        <option value="">كل الأحياء</option>
        <option>حي العليا</option>
        <option>حي الروضة</option>
      </select>
    </div>
    <div class="col-md-3">
      <button class="btn btn-label-secondary w-100" id="reset-filters">
        <i class="ti tabler-refresh me-1"></i> إعادة ضبط
      </button>
    </div>
  </div>
</div>

{{-- Table --}}
<div class="card glass-card overflow-hidden position-relative">
  <div id="table-loader" class="d-none position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="z-index:10; background:rgba(255,255,255,0.75); top:0; left:0;">
    <div class="spinner-border text-primary" role="status"></div>
  </div>
  <div class="card-datatable table-responsive">
    <table class="table table-hover fixit-table border-top">
      <thead>
        <tr>
          <th>المنطقة</th>
          <th>الحي</th>
          <th>المدينة</th>
          <th>الحالة</th>
          <th class="text-center">الإجراءات</th>
        </tr>
      </thead>
      <tbody>
        @foreach($areas as $area)
        @php
          $badgeClass = $area['status'] == 'active' ? 'bg-label-success' : 'bg-label-danger';
          $statusName = $area['status'] == 'active' ? 'نشط' : 'غير نشط';
        @endphp
        <tr>
          <td>
            <div class="d-flex align-items-center gap-3">
              <div class="avatar avatar-sm">
                <span class="avatar-initial rounded-circle bg-label-warning"><i class="ti tabler-point"></i></span>
              </div>
              <span class="fw-bold">{{ $area['name'] }}</span>
            </div>
          </td>
          <td><span class="badge bg-label-info">{{ $area['district'] }}</span></td>
          <td><span class="badge bg-label-primary">{{ $area['city'] }}</span></td>
          <td>
            <span class="badge {{ $badgeClass }} status-badge">
              <i class="ti {{ $area['status'] == 'active' ? 'tabler-check' : 'tabler-x' }} me-1"></i>{{ $statusName }}
            </span>
          </td>
          <td class="text-center">
            <div class="d-inline-flex gap-1">
              <button class="btn btn-sm btn-icon btn-label-secondary" title="تعديل"><i class="ti tabler-edit"></i></button>
              <button class="btn btn-sm btn-icon btn-label-danger delete-record" title="حذف"><i class="ti tabler-trash"></i></button>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

{{-- Add Area Modal --}}
<div class="modal fade" id="addAreaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title text-white"><i class="ti tabler-plus me-1"></i> إضافة منطقة جديدة</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="addAreaForm">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">المدينة</label>
              <select class="form-select select2-modal" required>
                <option value="">اختر المدينة...</option>
                <option>الرياض</option>
                <option>جدة</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">الحي</label>
              <select class="form-select select2-modal" required>
                <option value="">اختر الحي...</option>
                <option>حي العليا</option>
                <option>حي الروضة</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-bold">اسم المنطقة (بالعربية)</label>
              <input type="text" class="form-control" placeholder="مثال: مربع 1" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-bold">اسم المنطقة (بالإنجليزية)</label>
              <input type="text" class="form-control" placeholder="Example: Block 1" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-bold">الحالة</label>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" checked id="areaStatus">
                <label class="form-check-label" for="areaStatus">نشط</label>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top-0 p-4 pt-0">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="button" class="btn btn-primary px-4">حفظ المنطقة</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script type="module">
$(document).ready(function() {
  $('.select2').select2({ dir: 'rtl' });
  $('.select2-modal').select2({ dropdownParent: $('#addAreaModal'), dir: 'rtl' });

  $('.ajax-filter').on('change keyup', function() {
    $('#table-loader').removeClass('d-none');
    setTimeout(() => $('#table-loader').addClass('d-none'), 600);
  });

  $('#reset-filters').on('click', function() {
    $('.ajax-filter').val('');
    $('.select2').val(null).trigger('change');
  });
});
</script>
@endsection
