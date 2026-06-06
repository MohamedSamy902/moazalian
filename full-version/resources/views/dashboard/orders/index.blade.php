@extends('layouts/layoutMaster')
@section('title', 'الطلبات - Fix-It')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/select2/select2.js'])
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0"><i class="ti tabler-briefcase"></i> إدارة الطلبات</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>الطلبات</span>
        </div>
    </div>
    <div class="d-flex gap-2 ms-auto align-items-center">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-primary active" id="btn-list-view" title="قائمة"><i class="ti tabler-list"></i></button>
            <button type="button" class="btn btn-outline-primary" id="btn-kanban-view" title="كانبان"><i class="ti tabler-layout-kanban"></i></button>
        </div>
        <button type="button" class="btn btn-primary" id="btn-add-order">
            <i class="ti tabler-plus me-1"></i> طلب جديد
        </button>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach($stats as $s)
    <div class="col-6 col-xl">
        <div class="card glass-card border-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="stat-icon gradient-{{ $s['color'] }} flex-shrink-0" style="width:42px;height:42px;border-radius:12px">
                    <i class="ti {{ $s['icon'] }} text-white"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="card-label" style="font-size:0.75rem">{{ $s['label'] }}</div>
                    <div class="card-value mt-1" style="font-size:1.4rem">{{ $s['value'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="filter-bar mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted mb-1">البحث</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti tabler-search text-muted"></i></span>
                <input type="text" id="filter-search" class="form-control border-start-0 ps-0" placeholder="رقم الطلب، العميل، الهاتف...">
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted mb-1">المركز</label>
            <select id="filter-center" class="form-select">
                <option value="">كل المراكز</option>
                @foreach(\App\Models\Center::active()->select('id','name')->get() as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted mb-1">الحالة</label>
            <select id="filter-status" class="form-select">
                <option value="">كل الحالات</option>
                @foreach($statuses as $s)
                <option value="{{ $s->value }}">{{ $s->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted mb-1">من تاريخ</label>
            <input type="date" id="filter-date-from" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted mb-1">إلى تاريخ</label>
            <input type="date" id="filter-date-to" class="form-control">
        </div>
        <div class="col-md-1">
            <button class="btn btn-icon btn-label-secondary w-100" title="إعادة ضبط" id="reset-filters">
                <i class="ti tabler-refresh"></i>
            </button>
        </div>
    </div>
</div>

{{-- List View --}}
<div id="list-view">
<div class="card glass-card overflow-hidden position-relative">
    <div id="table-loader" class="d-none position-absolute w-100 h-100 d-flex align-items-center justify-content-center"
         style="z-index:10; background:rgba(255,255,255,0.75); top:0; left:0;">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <div class="card-datatable table-responsive">
        <table class="table table-hover fixit-table border-top">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>الجهاز</th>
                    <th>المركز</th>
                    <th>المبلغ</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="orders-table-body">
                @forelse($orders as $order)
                @include('dashboard.orders._row', ['order' => $order])
                @empty
                <tr id="empty-row">
                    <td colspan="8" class="text-center py-5">
                        <div class="empty-state-icon mb-3"><i class="ti tabler-clipboard-off" style="font-size:2.8rem;color:#c4c4ff"></i></div>
                        <h6 class="text-muted">لا توجد طلبات</h6>
                        <p class="text-muted small mb-3">ابدأ بإضافة طلب جديد</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center border-top py-3 px-4" id="orders-pagination">
        <div class="small text-muted">
            عرض <strong>{{ $orders->firstItem() ?? 0 }}</strong> إلى <strong>{{ $orders->lastItem() ?? 0 }}</strong>
            من أصل <strong>{{ $orders->total() }}</strong> طلب
        </div>
        <nav>{{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}</nav>
    </div>
</div>
</div>

{{-- Kanban View --}}
<div id="kanban-view" class="d-none">
    <div class="row g-4 align-items-start" style="overflow-x:auto;flex-wrap:nowrap;padding-bottom:20px">
        @foreach($statuses as $status)
        @php
            $byStatus = $orders->filter(fn($o) => $o->status->value === $status->value);
            $colorMap  = ['pending'=>'warning','dispatched'=>'info','assigned'=>'primary','in_progress'=>'info','completed'=>'success','cancelled'=>'danger','reopened'=>'secondary'];
            $col       = $colorMap[$status->value] ?? 'secondary';
        @endphp
        <div class="col-12 col-md-4 col-lg-3" style="min-width:300px">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                    <span class="badge badge-dot bg-{{ $col }}"></span> {{ $status->label() }}
                </h6>
                <span class="badge bg-label-secondary rounded-pill">{{ $byStatus->count() }}</span>
            </div>
            <div class="d-flex flex-column gap-3 kanban-column" style="min-height:200px">
                @foreach($byStatus as $order)
                <div class="card glass-card shadow-sm border-0">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold text-primary">{{ $order->reference_number }}</span>
                            <span class="text-muted small">{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                        <h6 class="mb-1">{{ $order->customer_name }}</h6>
                        <p class="text-muted small mb-3"><i class="ti tabler-device-laptop me-1"></i>{{ $order->device_type_name }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-success">{{ number_format($order->amount) }} ر.س</span>
                            <a href="{{ route('dashboard.orders.show', $order->id) }}" class="btn btn-xs btn-label-primary">التفاصيل</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="orderModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" id="order-modal-content">
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>

// Bootstrap 5 safe modal helpers
var bsShow = window.bsShow || function(elOrId) {
    var el = typeof elOrId === 'string' ? document.getElementById(elOrId.replace('#','')) : elOrId;
    if (!el) return;
    if (typeof bootstrap !== 'undefined') {
        (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).show();
    } else {
        // Fallback: add show class manually
        el.classList.add('show');
        el.style.display = 'block';
    }
};
var bsHide = window.bsHide || function(elOrId) {
    var el = typeof elOrId === 'string' ? document.getElementById(elOrId.replace('#','')) : elOrId;
    if (!el) return;
    if (typeof bootstrap !== 'undefined') {
        var inst = bootstrap.Modal.getInstance(el);
        if (inst) inst.hide();
    }
};

const ORDERS_BASE_URL  = '{{ url('dashboard/orders') }}';
const CSRF             = '{{ csrf_token() }}';

$(document).ready(function () {

    // ── Add Modal ─────────────────────────────────────────────────
    $('#btn-add-order').on('click', function () {
        $('#order-modal-content').html(buildOrderForm('add'));
        bsShow('orderModal');
    });

    // ── Edit Modal ────────────────────────────────────────────────
    $(document).on('click', '.btn-edit-order', function () {
        const id = $(this).data('id');
        $.get(ORDERS_BASE_URL + '/' + id + '/edit', function (res) {
            if (!res.success) return;
            const d = res.data;
            $('#order-modal-content').html(buildOrderForm('edit', id));
            $('#o-customer-name').val(d.customer_name);
            $('#o-customer-phone').val(d.customer_phone);
            $('#o-device-type').val(d.device_type_name);
            $('#o-problem').val(d.problem_description);
            $('#o-address').val(d.address_text);
            $('#o-preferred-date').val(d.preferred_date);
            $('#o-notes').val(d.notes);
            bsShow('orderModal');
        });
    });

    function buildOrderForm(mode, id = null) {
        const isEdit = mode === 'edit';
        const action = isEdit ? ORDERS_BASE_URL + '/' + id : ORDERS_BASE_URL;
        const hidden = isEdit ? '<input type="hidden" name="_method" value="PUT">' : '';
        const title  = isEdit ? '<i class="ti tabler-edit me-2 text-primary"></i>تعديل الطلب' : '<i class="ti tabler-plus me-2 text-primary"></i>طلب جديد';
        return `
        <div class="modal-header border-bottom">
            <h5 class="modal-title">${title}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="orderForm" action="${action}" method="POST">
            <input type="hidden" name="_token" value="${CSRF}">${hidden}
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12"><small class="text-muted fw-semibold text-uppercase">بيانات العميل</small><hr class="mt-1 mb-2"></div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">اسم العميل <span class="text-danger">*</span></label>
                        <input type="text" id="o-customer-name" name="customer_name" class="form-control" required>
                        <div class="invalid-feedback" id="err-customer_name"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">هاتف العميل <span class="text-danger">*</span></label>
                        <input type="text" id="o-customer-phone" name="customer_phone" class="form-control" placeholder="05xxxxxxxx" required>
                        <div class="invalid-feedback" id="err-customer_phone"></div>
                    </div>
                    <div class="col-12"><small class="text-muted fw-semibold text-uppercase">بيانات الجهاز</small><hr class="mt-1 mb-2"></div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">نوع الجهاز <span class="text-danger">*</span></label>
                        <input type="text" id="o-device-type" name="device_type_name" class="form-control" placeholder="مكيف، ثلاجة، غسالة..." required>
                        <div class="invalid-feedback" id="err-device_type_name"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">الموديل</label>
                        <input type="text" id="o-model" name="model_name" class="form-control" placeholder="Samsung, LG...">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">وصف العطل <span class="text-danger">*</span></label>
                        <textarea id="o-problem" name="problem_description" class="form-control" rows="3" required></textarea>
                        <div class="invalid-feedback" id="err-problem_description"></div>
                    </div>
                    <div class="col-12"><small class="text-muted fw-semibold text-uppercase">بيانات الزيارة</small><hr class="mt-1 mb-2"></div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">العنوان <span class="text-danger">*</span></label>
                        <input type="text" id="o-address" name="address_text" class="form-control" placeholder="المدينة، الحي، الشارع..." required>
                        <div class="invalid-feedback" id="err-address_text"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">تاريخ الزيارة المفضل</label>
                        <input type="date" id="o-preferred-date" name="preferred_date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">الوقت المفضل</label>
                        <select name="preferred_time_slot" class="form-select">
                            <option value="">أي وقت</option>
                            <option value="morning">صباحاً</option>
                            <option value="afternoon">ظهراً</option>
                            <option value="evening">مساءً</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">ملاحظات</label>
                        <textarea id="o-notes" name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div id="order-form-errors" class="alert alert-danger mt-3 d-none"></div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary" id="btn-save-order">
                    <i class="ti tabler-device-floppy me-1"></i> حفظ
                </button>
            </div>
        </form>`;
    }

    // ── Submit ────────────────────────────────────────────────────
    $(document).on('submit', '#orderForm', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $btn  = $('#btn-save-order');
        clearOrderErrors();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>جارٍ الحفظ...');

        $.ajax({
            url:     $form.attr('action'),
            method:  'POST',
            data:    $form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function (res) {
                if (!res.success) { showOrderError(res.message); return; }
                bsHide('orderModal');
                const tbody = $('#orders-table-body');
                if ($form.find('[name="_method"]').val() === 'PUT') {
                    $(`tr[data-id="${res.data.id}"]`).replaceWith(res.data.row_html);
                } else {
                    $('#empty-row').remove();
                    tbody.prepend(res.data.row_html);
                }
                Swal.fire({ icon: 'success', title: res.message, timer: 2000, showConfirmButton: false });
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (field, msgs) {
                        $(`#err-${field.replace('.', '_')}`).text(msgs[0]).show();
                        $(`[name="${field}"]`).addClass('is-invalid');
                    });
                } else {
                    showOrderError(xhr.responseJSON?.message || 'حدث خطأ غير متوقع');
                }
                $btn.prop('disabled', false).html('<i class="ti tabler-device-floppy me-1"></i> حفظ');
            }
        });
    });

    // ── Delete ────────────────────────────────────────────────────
    $(document).on('click', '.btn-delete-order', function () {
        const id   = $(this).data('id');
        const ref  = $(this).data('ref');
        Swal.fire({
            title: 'حذف الطلب؟',
            text: `سيتم حذف الطلب ${ref} (السجل محفوظ)`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ti tabler-trash"></i> نعم، احذف',
            cancelButtonText: 'إلغاء',
            customClass: { confirmButton: 'btn btn-danger ms-1', cancelButton: 'btn btn-label-secondary' },
            buttonsStyling: false
        }).then(r => {
            if (!r.isConfirmed) return;
            $.ajax({
                url:     ORDERS_BASE_URL + '/' + id,
                method:  'POST',
                data:    { _method: 'DELETE', _token: CSRF },
                headers: { 'Accept': 'application/json' },
                success: function (res) {
                    if (!res.success) return;
                    $(`tr[data-id="${id}"]`).fadeOut(300, function () { $(this).remove(); });
                    Swal.fire({ icon: 'success', title: res.message, timer: 1800, showConfirmButton: false });
                }
            });
        });
    });

    // ── Filters ───────────────────────────────────────────────────
    let timer;
    $('#filter-search').on('input', function () {
        clearTimeout(timer);
        timer = setTimeout(applyFilters, 400);
    });
    $('#filter-center, #filter-status, #filter-date-from, #filter-date-to').on('change', applyFilters);
    $('#reset-filters').on('click', function () {
        $('#filter-search, #filter-date-from, #filter-date-to').val('');
        $('#filter-center, #filter-status').val('');
        applyFilters();
    });

    function applyFilters() {
        $('#table-loader').removeClass('d-none');
        $.get(ORDERS_BASE_URL, {
            search:    $('#filter-search').val(),
            center_id: $('#filter-center').val(),
            status:    $('#filter-status').val(),
            date_from: $('#filter-date-from').val(),
            date_to:   $('#filter-date-to').val(),
        }, function (html) {
            const $p = $(html);
            $('#orders-table-body').html($p.find('#orders-table-body').html());
            $('#orders-pagination').html($p.find('#orders-pagination').html());
            $('#table-loader').addClass('d-none');
        });
    }

    // ── View Toggle ───────────────────────────────────────────────
    $('#btn-kanban-view').on('click', function () {
        $('#list-view').addClass('d-none');
        $('#kanban-view').removeClass('d-none');
        $(this).addClass('active');
        $('#btn-list-view').removeClass('active');
    });
    $('#btn-list-view').on('click', function () {
        $('#kanban-view').addClass('d-none');
        $('#list-view').removeClass('d-none');
        $(this).addClass('active');
        $('#btn-kanban-view').removeClass('active');
    });

    function clearOrderErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('').hide();
        $('#order-form-errors').addClass('d-none').html('');
    }
    function showOrderError(msg) {
        $('#order-form-errors').removeClass('d-none').html(`<i class="ti tabler-alert-circle me-1"></i>${msg}`);
    }
});
</script>
@endsection