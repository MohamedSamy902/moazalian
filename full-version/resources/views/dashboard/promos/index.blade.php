@extends('layouts/layoutMaster')
@section('title', 'أكواد الخصم - Fix-It')
@section('content')

{{-- Page Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h4 class="mb-0"><i class="ti tabler-ticket"></i> إدارة الأكواد الترويجية</h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>أكواد الخصم</span>
    </div>
  </div>
  <a href="{{ route('dashboard.promos.create') }}" data-ajax-modal class="btn btn-primary ms-auto">
    <i class="ti tabler-plus me-1"></i> إنشاء كود جديد
  </a>
</div>

{{-- Quick Stats --}}
<div class="row g-3 mb-4">
  <div class="col-sm-4">
    <div class="stat-card card p-3 d-flex flex-row align-items-center gap-3">
      <div class="stat-icon gradient-success" style="width:44px;height:44px;min-width:44px;border-radius:10px">
        <i class="ti tabler-ticket text-white"></i>
      </div>
      <div>
        <div class="card-label">أكواد نشطة</div>
        <div class="card-value" style="font-size:1.3rem">{{ collect($promos)->where('status','active')->count() }}</div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="stat-card card p-3 d-flex flex-row align-items-center gap-3">
      <div class="stat-icon gradient-danger" style="width:44px;height:44px;min-width:44px;border-radius:10px">
        <i class="ti tabler-calendar-off text-white"></i>
      </div>
      <div>
        <div class="card-label">أكواد منتهية</div>
        <div class="card-value" style="font-size:1.3rem">{{ collect($promos)->where('status','expired')->count() }}</div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="stat-card card p-3 d-flex flex-row align-items-center gap-3">
      <div class="stat-icon gradient-primary" style="width:44px;height:44px;min-width:44px;border-radius:10px">
        <i class="ti tabler-percentage text-white"></i>
      </div>
      <div>
        <div class="card-label">إجمالي الأكواد</div>
        <div class="card-value" style="font-size:1.3rem">{{ count($promos) }}</div>
      </div>
    </div>
  </div>
</div>

{{-- Filter Bar --}}
<div class="card mb-4">
  <div class="card-body py-3">
    <form action="{{ route('dashboard.promos.index') }}" method="GET" class="d-flex gap-2">
      <input type="text" name="search" class="form-control" placeholder="البحث برمز الكود..." value="{{ request('search') }}">
      <button type="submit" class="btn btn-primary"><i class="ti tabler-search"></i></button>
      <a href="{{ route('dashboard.promos.index') }}" class="btn btn-label-secondary"><i class="ti tabler-refresh"></i></a>
    </form>
  </div>
</div>

{{-- Promo Cards Grid --}}
<div class="row g-4">
  @foreach($promos as $promo)
  <div class="col-md-4 fade-in-up">
    <div class="card promo-card glass-card h-100 border-0">
      <div class="card-body p-4">

        {{-- Header: Status + Discount --}}
        <div class="d-flex justify-content-between align-items-start mb-4">
          <span class="badge {{ $promo['status'] == 'active' ? 'bg-label-success' : 'bg-label-danger' }} status-badge">
            <i class="ti {{ $promo['status']=='active' ? 'tabler-circle-check' : 'tabler-circle-x' }} me-1"></i>
            {{ $promo['status'] == 'active' ? 'نشط' : 'منتهي' }}
          </span>
          <div class="text-end">
            <div class="fw-800 text-primary" style="font-size:1.8rem;line-height:1">{{ $promo['discount'] }}</div>
            <small class="text-muted">{{ $promo['type'] == 'percentage' ? 'خصم نسبة مئوية' : 'خصم مبلغ ثابت' }}</small>
          </div>
        </div>

        {{-- Code Display --}}
        <div class="promo-code-display mb-4" onclick="copyPromoCode('{{ $promo['code'] }}', this)" title="انقر للنسخ">
          <div class="promo-code-text">{{ $promo['code'] }}</div>
          <div class="copy-hint"><i class="ti tabler-copy me-1"></i> انقر للنسخ</div>
        </div>

        {{-- Meta --}}
        <div class="d-flex justify-content-between align-items-center">
          <div class="small text-muted">
            <i class="ti tabler-calendar me-1"></i>
            ينتهي في {{ $promo['expires_at'] }}
          </div>
          @if(isset($promo['uses_count']))
          <div class="small text-muted">
            <i class="ti tabler-users me-1"></i>
            {{ $promo['uses_count'] }} استخدام
          </div>
          @endif
        </div>
      </div>

      {{-- Footer Actions --}}
      <div class="card-footer bg-transparent border-top-0 pt-0 px-4 pb-4 d-flex gap-2 justify-content-end">
        <a href="{{ route('dashboard.promos.show', $promo['id']) }}" class="btn btn-sm btn-label-primary">
          <i class="ti tabler-chart-bar me-1"></i> الإحصائيات
        </a>
        <a href="{{ route('dashboard.promos.edit', $promo['id']) }}" data-ajax-modal class="btn btn-sm btn-icon btn-label-secondary" title="تعديل"><i class="ti tabler-edit"></i></a>
        <form action="{{ route('dashboard.promos.destroy', $promo['id']) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-sm btn-icon btn-label-danger" title="حذف"><i class="ti tabler-trash"></i></button>
        </form>
      </div>
    </div>
  </div>
  @endforeach
</div>

@endsection

@section('page-script')
<script>
function copyPromoCode(code, el) {
  navigator.clipboard.writeText(code).then(() => {
    const hint = el.querySelector('.copy-hint');
    hint.innerHTML = '<i class="ti tabler-check me-1"></i> تم النسخ!';
    hint.style.color = '#28c76f';
    setTimeout(() => {
      hint.innerHTML = '<i class="ti tabler-copy me-1"></i> انقر للنسخ';
      hint.style.color = '';
    }, 2000);
  });
}
</script>
@endsection

@section('page-style')
<style>
.promo-code-display { cursor: pointer; }
</style>
@endsection
