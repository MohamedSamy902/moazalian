@extends('layouts/layoutMaster')
@section('title', 'تفاصيل الطلب - Fix-It')
@section('content')

{{-- Page Header --}}
<div class="page-header">
  <div>
    <h4><i class="ti tabler-briefcase"></i> تفاصيل الطلب <span class="text-primary">{{ $order['id'] }}</span></h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <a href="{{ route('dashboard.orders.index') }}">الطلبات</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>{{ $order['id'] }}</span>
    </div>
  </div>
  <div class="d-flex gap-2 flex-wrap ms-auto">
    <button class="btn btn-primary order-1" data-bs-toggle="modal" data-bs-target="#completeOrderModal"><i class="ti tabler-check me-1"></i> اعتماد التسليم</button>
    <button class="btn btn-label-danger order-2"><i class="ti tabler-x me-1"></i> إلغاء الطلب</button>
    <button class="btn btn-label-secondary order-3"><i class="ti tabler-printer me-1"></i> طباعة</button>
  </div>
</div>

<div class="row g-4">
  {{-- Left: Device Info + Financial --}}
  <div class="col-lg-8">

    {{-- Device & Problem --}}
    <div class="card glass-card mb-4">
      <div class="card-header border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
        <div>
          <h5 class="card-title mb-0 fw-bold">معلومات الجهاز والمشكلة</h5>
        </div>
        <span class="badge bg-label-info status-badge">قيد التنفيذ</span>
      </div>
      <div class="card-body pt-3 px-4 pb-4">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="p-3 rounded-3 border" style="background:rgba(115,103,240,0.03)">
              <small class="text-muted fw-bold d-block mb-1"><i class="ti tabler-device-laptop me-1"></i> الجهاز</small>
              <span class="fw-semibold">{{ $order['device'] }}</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-3 rounded-3 border" style="background:rgba(115,103,240,0.03)">
              <small class="text-muted fw-bold d-block mb-1"><i class="ti tabler-calendar me-1"></i> تاريخ الطلب</small>
              <span class="fw-semibold">{{ $order['date'] }}</span>
            </div>
          </div>
          <div class="col-12">
            <div class="p-3 rounded-3 border" style="background:rgba(115,103,240,0.03)">
              <small class="text-muted fw-bold d-block mb-1"><i class="ti tabler-alert-circle me-1"></i> وصف المشكلة</small>
              <p class="mb-0 text-muted">{{ $order['problem'] }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Financial Details --}}
    <div class="card glass-card">
      <div class="card-header border-0 pt-4 pb-0 px-4">
        <h5 class="card-title mb-0 fw-bold"><i class="ti tabler-receipt me-2 text-success"></i> التفاصيل المالية</h5>
      </div>
      <div class="card-body px-4 pb-4 pt-3">
        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
          <span class="text-muted">تكلفة الصيانة</span>
          <span class="fw-bold fs-6">{{ number_format($order['amount']) }} <small class="text-muted">EGP</small></span>
        </div>

        {{-- Spare Parts (Conditional) --}}
        <div class="py-3 border-bottom">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted">قطع الغيار</span>
            <span class="fw-bold text-dark">+ 450 <small class="text-muted">EGP</small></span>
          </div>
          <div class="spare-parts-list ps-3 border-start" style="border-width: 2px !important; border-color: var(--fixit-primary) !important;">
            <div class="d-flex justify-content-between small text-muted mb-1">
              <span>شاشة سامسونج الأصلية (x1)</span>
              <span>400 EGP</span>
            </div>
            <div class="d-flex justify-content-between small text-muted">
              <span>كابل توصيل (x1)</span>
              <span>50 EGP</span>
            </div>
            <div class="mt-1">
              <span class="badge bg-label-success" style="font-size: 0.6rem">ضمان 6 أشهر</span>
            </div>
          </div>
        </div>

        @if(isset($order['coupon']))
        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
          <div>
            <span class="fw-semibold text-success">كود الخصم</span>
            <span class="badge bg-label-success ms-2">{{ $order['coupon']['code'] }}</span>
          </div>
          <span class="fw-bold text-success">- {{ number_format($order['coupon']['amount']) }} EGP</span>
        </div>
        @endif

        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
          <div>
            <span class="text-muted">عمولة المنصة</span>
            <span class="badge bg-label-secondary ms-2">10%</span>
          </div>
          <span class="fw-bold text-danger">- {{ number_format($order['commission']) }} EGP</span>
        </div>

        <div class="d-flex justify-content-between align-items-center pt-3">
          <span class="h6 mb-0 fw-bold">صافي المركز</span>
          <span class="h5 mb-0 text-success fw-800">{{ number_format($order['amount'] - $order['commission']) }} EGP</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Right: People + Timeline --}}
  <div class="col-lg-4">

    {{-- Customer & Tech --}}
    <div class="card glass-card mb-4">
      <div class="card-header border-0 pt-4 pb-0 px-4">
        <h5 class="card-title mb-0 fw-bold">الأطراف المعنية</h5>
      </div>
      <div class="card-body px-4 pb-4 pt-3">

        {{-- Customer --}}
        <div class="mb-4">
          <small class="text-uppercase text-muted fw-bold small d-block mb-2">العميل</small>
          <div class="d-flex align-items-center gap-3 p-3 rounded-3 border" style="background:rgba(115,103,240,0.03)">
            <div class="avatar avatar-md">
              <span class="avatar-initial rounded-circle bg-label-primary">{{ mb_substr($order['customer']['name'], 0, 1) }}</span>
            </div>
            <div>
              <div class="fw-bold">{{ $order['customer']['name'] }}</div>
              <a href="tel:{{ $order['customer']['phone'] }}" class="text-muted small d-flex align-items-center gap-1 mt-1">
                <i class="ti tabler-phone" style="font-size:0.75rem"></i> {{ $order['customer']['phone'] }}
              </a>
            </div>
          </div>
        </div>

        {{-- Technician --}}
        <div class="mb-4">
          <small class="text-uppercase text-muted fw-bold small d-block mb-2">الفني المكلف</small>
          <div class="d-flex align-items-center gap-3 p-3 rounded-3 border" style="background:rgba(0,207,232,0.05)">
            <div class="avatar avatar-md">
              <span class="avatar-initial rounded-circle bg-label-info">{{ mb_substr($order['technician']['name'], 0, 1) }}</span>
            </div>
            <div>
              <div class="fw-bold">{{ $order['technician']['name'] }}</div>
              <a href="tel:{{ $order['technician']['phone'] }}" class="text-muted small d-flex align-items-center gap-1 mt-1">
                <i class="ti tabler-phone" style="font-size:0.75rem"></i> {{ $order['technician']['phone'] }}
              </a>
            </div>
          </div>
        </div>

        {{-- Center --}}
        <div>
          <small class="text-uppercase text-muted fw-bold small d-block mb-2">المركز المسؤول</small>
          <div class="d-flex align-items-center gap-3 p-3 rounded-3 border" style="background:rgba(255,159,67,0.05)">
            <div class="avatar avatar-md">
              <span class="avatar-initial rounded-circle bg-label-warning"><i class="ti tabler-building-store"></i></span>
            </div>
            <div class="fw-bold">{{ $order['center'] }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Timeline --}}
    <div class="card glass-card">
      <div class="card-header border-0 pt-4 pb-0 px-4">
        <h5 class="card-title mb-0 fw-bold"><i class="ti tabler-timeline me-2 text-primary"></i> تتبع الطلب</h5>
      </div>
      <div class="card-body px-4 pb-4 pt-3">
        <ul class="timeline mb-0">
          @foreach($order['timeline'] as $item)
          <li class="timeline-item {{ $item['status']=='completed' ? 'border-primary' : ($item['status']=='active' ? 'border-info' : 'border-secondary') }}">
            <span class="timeline-point {{ $item['status']=='completed' ? 'timeline-point-primary' : ($item['status']=='active' ? 'timeline-point-info' : 'timeline-point-secondary') }}"></span>
            <div class="timeline-event">
              <div class="d-flex justify-content-between align-items-start">
                <h6 class="mb-0 {{ $item['status']=='pending' ? 'text-muted' : '' }}">{{ $item['title'] }}</h6>
                @if($item['status']=='active')
                  <span class="badge bg-label-info status-badge ms-2">جارٍ</span>
                @elseif($item['status']=='completed')
                  <i class="ti tabler-circle-check text-success"></i>
                @endif
              </div>
              <small class="text-muted d-block mt-1">{{ $item['date'] }}</small>
            </div>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
{{-- Complete Order Modal --}}
<div class="modal fade" id="completeOrderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title text-white"><i class="ti tabler-circle-check me-1"></i> إغلاق واعتماد الطلب</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="completeOrderForm">
          {{-- Completion Type --}}
          <div class="row mb-4">
            <div class="col-md-12">
              <label class="form-label fw-bold d-block mb-3">حالة التنفيذ</label>
              <div class="d-flex gap-3">
                <div class="flex-grow-1">
                  <input type="radio" class="btn-check" name="completion_type" id="type_fixed" value="fixed" checked>
                  <label class="btn btn-outline-primary w-100 py-3" for="type_fixed">
                    <i class="ti tabler-tool d-block mb-1 fs-3"></i>
                    تم الإصلاح بنجاح
                  </label>
                </div>
                <div class="flex-grow-1">
                  <input type="radio" class="btn-check" name="completion_type" id="type_visit" value="visit">
                  <label class="btn btn-outline-warning w-100 py-3" for="type_visit">
                    <i class="ti tabler-eye d-block mb-1 fs-3"></i>
                    رسوم زيارة فقط
                  </label>
                </div>
              </div>
            </div>
          </div>

          {{-- Fees & Visit --}}
          <div class="row g-3 mb-4">
            <div class="col-md-6" id="maintenance_fee_wrapper">
              <label class="form-label fw-bold">تكلفة الصيانة</label>
              <div class="input-group">
                <input type="number" class="form-control maintenance-fee-input" placeholder="0.00" value="1500">
                <span class="input-group-text">EGP</span>
              </div>
            </div>
            <div class="col-md-6" id="visit_fee_wrapper" style="display:none;">
              <label class="form-label fw-bold">رسوم الزيارة</label>
              <div class="input-group">
                <input type="number" class="form-control visit-fee-input" placeholder="0.00" value="200">
                <span class="input-group-text">EGP</span>
              </div>
            </div>
          </div>

          {{-- Spare Parts Section --}}
          <div id="spare_parts_section">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="mb-0 fw-bold"><i class="ti tabler-settings-automation me-1"></i> قطع الغيار المستخدمة</h6>
              <button type="button" class="btn btn-sm btn-label-primary add-part">
                <i class="ti tabler-plus me-1"></i> إضافة قطعة
              </button>
            </div>

            <div id="parts_container">
              {{-- Part Row Template --}}
              <div class="part-row p-3 rounded-3 border mb-3 position-relative" style="background: rgba(115,103,240,0.02)">
                <div class="row g-2">
                  <div class="col-md-4">
                    <label class="form-label small fw-bold">اسم القطعة</label>
                    <input type="text" class="form-control form-control-sm" placeholder="مثال: شاشة سامسونج">
                  </div>
                  <div class="col-md-2">
                    <label class="form-label small fw-bold">الكمية</label>
                    <input type="number" class="form-control form-control-sm part-qty" value="1">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label small fw-bold">السعر</label>
                    <input type="number" class="form-control form-control-sm part-price" placeholder="0.00">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label small fw-bold">الضمان</label>
                    <select class="form-select form-select-sm">
                      <option value="none">بدون ضمان</option>
                      <option value="1m">شهر واحد</option>
                      <option value="3m">3 أشهر</option>
                      <option value="6m">6 أشهر</option>
                      <option value="1y">سنة واحدة</option>
                    </select>
                  </div>
                </div>
                <button type="button" class="btn btn-sm btn-icon btn-label-danger remove-part position-absolute top-0 end-0 m-2" style="display:none;">
                  <i class="ti tabler-x"></i>
                </button>
              </div>
            </div>
          </div>

          {{-- Notes --}}
          <div class="mb-0">
            <label class="form-label fw-bold">ملاحظات الفني</label>
            <textarea class="form-control" rows="2" placeholder="أضف أي ملاحظات إضافية هنا..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top-0 p-4 pt-0">
        <div class="d-flex w-100 justify-content-between align-items-center">
          <div class="total-summary">
            <span class="text-muted small d-block">الإجمالي النهائي</span>
            <span class="h5 mb-0 fw-800 text-primary" id="final_total">1500 EGP</span>
          </div>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="button" class="btn btn-primary px-4">إغلاق الطلب</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const $ = window.jQuery || window.$;
  if (!$) return;

  function calculateFinalTotal() {
    let total = 0;
    const type = $('input[name="completion_type"]:checked').val();

    if (type === 'visit') {
      total = parseFloat($('.visit-fee-input').val()) || 0;
    } else {
      total = parseFloat($('.maintenance-fee-input').val()) || 0;
      $('.part-row').each(function() {
        const qty = parseFloat($(this).find('.part-qty').val()) || 0;
        const price = parseFloat($(this).find('.part-price').val()) || 0;
        total += (qty * price);
      });
    }
    $('#final_total').text(total.toLocaleString() + ' EGP');
  }

  // Toggle between Fixed and Visit Only
  $(document).on('change', 'input[name="completion_type"]', function() {
    if ($(this).val() === 'visit') {
      $('#maintenance_fee_wrapper').hide();
      $('#visit_fee_wrapper').show();
      $('#spare_parts_section').fadeOut(200);
    } else {
      $('#maintenance_fee_wrapper').show();
      $('#visit_fee_wrapper').hide();
      $('#spare_parts_section').fadeIn(200);
    }
    calculateFinalTotal();
  });

  // Add Spare Part (Event Delegation)
  $(document).on('click', '.add-part', function(e) {
    e.preventDefault();
    const container = $('#parts_container');
    const firstRow = container.find('.part-row').first();
    const newRow = firstRow.clone();
    
    newRow.find('input').val('');
    newRow.find('.part-qty').val('1');
    newRow.find('.remove-part').show();
    container.append(newRow);
    calculateFinalTotal();
  });

  // Remove Spare Part
  $(document).on('click', '.remove-part', function(e) {
    e.preventDefault();
    if ($('.part-row').length > 1) {
      $(this).closest('.part-row').remove();
    } else {
      $(this).closest('.part-row').find('input').val('');
      $(this).closest('.part-row').find('.part-qty').val('1');
    }
    calculateFinalTotal();
  });

  // Listen for value changes
  $(document).on('input', '.part-qty, .part-price, .maintenance-fee-input, .visit-fee-input', function() {
    calculateFinalTotal();
  });

  // Initial calculation
  calculateFinalTotal();
});
</script>
@endsection
