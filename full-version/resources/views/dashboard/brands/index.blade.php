@extends('layouts/layoutMaster')
@section('title', 'العلامات التجارية - Fix-It')
@section('content')

{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h4 class="mb-0"><i class="ti tabler-tags"></i> العلامات التجارية</h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>البرندات</span>
    </div>
  </div>
  <a href="{{ route('dashboard.brands.create') }}" data-ajax-modal class="btn btn-primary ms-auto">
    <i class="ti tabler-plus me-1"></i> إضافة براند
  </a>
</div>

{{-- Quick stats & Search --}}
<div class="d-flex align-items-center justify-content-between gap-3 mb-4 mt-3">
  <div class="d-flex align-items-center gap-3">
    <div class="badge bg-label-primary px-3 py-2">
      <i class="ti tabler-tags me-1"></i> {{ count($brands) }} علامة تجارية
    </div>
    <div class="badge bg-label-info px-3 py-2">
      <i class="ti tabler-device-laptop me-1"></i> {{ collect($brands)->sum('devices_count') }} جهاز مشمول
    </div>
  </div>
  <div class="search-group">
    <input type="text" id="brandSearch" class="form-control" placeholder="بحث عن علامة تجارية..." onkeyup="filterBrands()">
  </div>
</div>

{{-- Brand Cards Grid --}}
<div class="row g-4" id="brandsContainer">
  @foreach($brands as $brand)
  <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 fade-in-up brand-item">
    <div class="card brand-card glass-card h-100 border-0 shadow-sm">
      <div class="card-body text-center d-flex flex-column align-items-center justify-content-between py-4 px-3">
        <div class="brand-logo-wrapper mb-3 d-flex align-items-center justify-content-center" style="height:60px; width:100%;">
          @if(isset($brand['logo']) && $brand['logo'] != '#')
            <img src="{{ $brand['logo'] }}" alt="{{ $brand['name'] }}" style="max-width:100%; max-height:60px; object-fit:contain;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
            <div class="avatar avatar-md d-none flex-shrink-0">
               <span class="avatar-initial rounded bg-label-primary"><i class="ti tabler-brand-abstract ti-md"></i></span>
            </div>
          @else
            <div class="avatar avatar-md flex-shrink-0">
               <span class="avatar-initial rounded bg-label-primary"><i class="ti tabler-brand-abstract ti-md"></i></span>
            </div>
          @endif
        </div>
        <div class="flex-grow-1 d-flex flex-column align-items-center">
          <h6 class="mb-1 fw-bold text-dark brand-name">{{ $brand['name'] }}</h6>
          <span class="badge bg-label-info mb-3">{{ $brand['devices_count'] }} جهاز</span>
        </div>
        <div class="d-flex gap-2 w-100">
          <a href="{{ route('dashboard.brands.edit', $brand['id']) }}" data-ajax-modal class="btn btn-sm btn-label-secondary flex-grow-1" title="تعديل">
            <i class="ti tabler-edit"></i>
          </a>
          <form action="{{ route('dashboard.brands.destroy', $brand['id']) }}" method="POST" class="delete-form flex-grow-1">
            @csrf @method('DELETE')
            <button type="button" class="btn btn-sm btn-label-danger w-100" onclick="confirmDelete(this)" title="حذف">
              <i class="ti tabler-trash"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endforeach

  {{-- Add Card --}}
  <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
    <a href="{{ route('dashboard.brands.create') }}" data-ajax-modal class="text-decoration-none">
      <div class="card glass-card brand-card h-100 border-2 border-dashed d-flex align-items-center justify-content-center" style="min-height:170px; cursor:pointer; border-color:rgba(115,103,240,0.3) !important;">
        <div class="text-center">
          <i class="ti tabler-plus text-primary" style="font-size:2rem"></i>
          <div class="text-primary fw-semibold mt-2 small">إضافة براند</div>
        </div>
      </div>
    </a>
  </div>
</div>

<script>
  function filterBrands() {
    let input = document.getElementById('brandSearch').value.toLowerCase();
    let cards = document.getElementsByClassName('brand-item');
    for (let card of cards) {
      let name = card.querySelector('.brand-name').innerText.toLowerCase();
      card.style.display = name.includes(input) ? "" : "none";
    }
  }

  function confirmDelete(button) {
    Swal.fire({
      title: 'هل أنت متأكد؟',
      text: "لن تتمكن من التراجع عن هذا!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'نعم، احذفه!',
      cancelButtonText: 'إلغاء'
    }).then((result) => {
      if (result.isConfirmed) {
        button.closest('.delete-form').submit();
      }
    });
  }
</script>
@endsection
