@extends('layouts/layoutMaster')
@section('title', 'المصاريف - Fix-It')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection
@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-receipt me-2"></i>إدارة المصاريف</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}">الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <a href="{{ route('dashboard.hr.overview') }}">الموارد البشرية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>المصاريف</span>
        </div>
    </div>
    <button class="btn btn-primary" id="btn-add-expense">
        <i class="ti tabler-plus me-1"></i> إضافة مصروف
    </button>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card glass-card border-0"><div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon gradient-danger flex-shrink-0"><i class="ti tabler-coins text-white ti-md"></i></div>
        <div><div class="card-label">إجمالي هذا الشهر</div><div class="card-value text-danger">{{ number_format($stats['total']) }} <small>ر.س</small></div></div>
    </div></div></div>
    <div class="col-md-3"><div class="card glass-card border-0"><div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon gradient-primary flex-shrink-0"><i class="ti tabler-list text-white ti-md"></i></div>
        <div><div class="card-label">عدد عمليات الشهر</div><div class="card-value">{{ $stats['this_month'] }}</div></div>
    </div></div></div>
    <div class="col-md-3"><div class="card glass-card border-0"><div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon gradient-warning flex-shrink-0"><i class="ti tabler-math-avg text-white ti-md"></i></div>
        <div><div class="card-label">متوسط المصروف</div><div class="card-value">{{ number_format($stats['avg']) }} <small>ر.س</small></div></div>
    </div></div></div>
    <div class="col-md-3"><div class="card glass-card border-0"><div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon gradient-info flex-shrink-0"><i class="ti tabler-database text-white ti-md"></i></div>
        <div><div class="card-label">إجمالي السجلات</div><div class="card-value">{{ $stats['count_total'] }}</div></div>
    </div></div></div>
</div>

{{-- Filters --}}
<div class="card glass-card border-0 mb-4">
    <div class="card-body py-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">التصنيف</label>
                <select id="filter-cat" class="form-select">
                    <option value="">كل التصنيفات</option>
                    @foreach($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->getTranslation('name', 'ar') }} ({{ $c->expenses_count }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">من تاريخ</label>
                <input type="date" id="filter-from" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">إلى تاريخ</label>
                <input type="date" id="filter-to" class="form-control">
            </div>
            <div class="col-md-2">
                <button class="btn btn-label-secondary w-100" id="reset-filters">
                    <i class="ti tabler-refresh me-1"></i> إعادة ضبط
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Chart --}}
    <div class="col-lg-4">
        <div class="card glass-card border-0 h-100">
            <div class="card-header border-bottom"><h5 class="card-title mb-0"><i class="ti tabler-chart-donut me-2 text-primary"></i>توزيع حسب التصنيف</h5></div>
            <div class="card-body">
                <div id="expenseChart"></div>
                <div class="mt-3">
                    @foreach($byCategory as $item)
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <span class="small fw-semibold">{{ $item->category?->getTranslation('name','ar') ?? 'أخرى' }}</span>
                        <span class="fw-bold">{{ number_format($item->total) }} ر.س</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="col-lg-8">
        <div class="card glass-card overflow-hidden">
            <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0"><i class="ti tabler-list-details me-2 text-danger"></i>قائمة المصاريف</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover fixit-table border-top mb-0">
                    <thead>
                        <tr>
                            <th>التصنيف</th>
                            <th>الوصف</th>
                            <th>المبلغ</th>
                            <th>التاريخ</th>
                            <th>أضاف بواسطة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="expenses-table-body">
                        @forelse($expenses as $expense)
                        @include('dashboard.hr._expense_row', ['expense' => $expense])
                        @empty
                        <tr id="empty-expense">
                            <td colspan="6" class="text-center py-4">
                                <i class="ti tabler-receipt-off" style="font-size:2rem;color:#c4c4ff"></i>
                                <p class="text-muted mt-2 mb-0">لا توجد مصاريف مسجلة</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2" id="expense-pagination">
                {{ $expenses->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

{{-- Add Expense Modal --}}
<div class="modal fade" id="expenseModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title"><i class="ti tabler-plus me-2 text-danger"></i>إضافة مصروف جديد</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="expenseForm" action="{{ route('dashboard.hr.expenses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">التصنيف <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">اختر التصنيف...</option>
                                @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->getTranslation('name','ar') }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="err-category_id"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">المبلغ (ر.س) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" min="0.01" required>
                            <div class="invalid-feedback" id="err-amount"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">التاريخ <span class="text-danger">*</span></label>
                            <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            <div class="invalid-feedback" id="err-expense_date"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">وصف المصروف</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="تفاصيل إضافية..."></textarea>
                        </div>
                    </div>
                    <div id="expense-form-errors" class="alert alert-danger mt-3 d-none"></div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger" id="btn-save-expense">
                        <i class="ti tabler-device-floppy me-1"></i> حفظ المصروف
                    </button>
                </div>
            </form>
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

const EXPENSES_STORE = '{{ route('dashboard.hr.expenses.store') }}';
const EXPENSES_BASE  = '{{ url('dashboard/hr/expenses') }}';
const EXPENSES_INDEX = '{{ route('dashboard.hr.expenses') }}';
const CSRF           = '{{ csrf_token() }}';
const CHART_DATA     = @json($byCategory->map(fn($b) => ['label' => $b->category?->getTranslation('name','ar') ?? 'أخرى', 'value' => (float)$b->total]));

$(document).ready(function () {

    // ── Chart ─────────────────────────────────────────────────────
    if (document.querySelector('#expenseChart') && CHART_DATA.length) {
        new ApexCharts(document.querySelector('#expenseChart'), {
            series: CHART_DATA.map(d => d.value),
            labels: CHART_DATA.map(d => d.label),
            chart:  { type: 'donut', height: 220 },
            colors: ['#ff9f43','#28c76f','#00cfe8','#7367f0','#ea5455','#82868b'],
            legend: { show: false },
            dataLabels: { enabled: false },
        }).render();
    }

    // ── Add Modal ─────────────────────────────────────────────────
    $('#btn-add-expense').on('click', function () {
        bsShow('expenseModal');
    });

    // ── Submit ────────────────────────────────────────────────────
    $(document).on('submit', '#expenseForm', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $btn  = $('#btn-save-expense');
        clearExpenseErrors();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>جارٍ الحفظ...');

        $.ajax({
            url:     $form.attr('action'),
            method:  'POST',
            data:    $form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function (res) {
                if (!res.success) { showExpenseError(res.message); return; }
                bsHide('expenseModal');
                $form[0].reset();
                $('#empty-expense').remove();
                $('#expenses-table-body').prepend(res.data.row_html);
                Swal.fire({ icon: 'success', title: res.message, timer: 2000, showConfirmButton: false });
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (field, msgs) {
                        $(`#err-${field.replace('.','_')}`).text(msgs[0]).show();
                        $(`[name="${field}"]`).addClass('is-invalid');
                    });
                } else {
                    showExpenseError(xhr.responseJSON?.message || 'حدث خطأ');
                }
                $btn.prop('disabled', false).html('<i class="ti tabler-device-floppy me-1"></i> حفظ المصروف');
            }
        });
    });

    // ── Delete ────────────────────────────────────────────────────
    $(document).on('click', '.btn-delete-expense', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'حذف المصروف؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ti tabler-trash"></i> نعم، احذف',
            cancelButtonText: 'إلغاء',
            customClass: { confirmButton: 'btn btn-danger ms-1', cancelButton: 'btn btn-label-secondary' },
            buttonsStyling: false
        }).then(r => {
            if (!r.isConfirmed) return;
            $.ajax({
                url:     EXPENSES_BASE + '/' + id,
                method:  'POST',
                data:    { _method: 'DELETE', _token: CSRF },
                headers: { 'Accept': 'application/json' },
                success: function (res) {
                    $(`tr[data-id="${id}"]`).fadeOut(300, function () { $(this).remove(); });
                    Swal.fire({ icon: 'success', title: res.message, timer: 1800, showConfirmButton: false });
                }
            });
        });
    });

    // ── Filters ───────────────────────────────────────────────────
    $('#filter-cat, #filter-from, #filter-to').on('change', applyFilters);
    $('#reset-filters').on('click', function () {
        $('#filter-cat').val('');
        $('#filter-from, #filter-to').val('');
        applyFilters();
    });

    function applyFilters() {
        $.get(EXPENSES_INDEX, {
            category_id: $('#filter-cat').val(),
            date_from:   $('#filter-from').val(),
            date_to:     $('#filter-to').val(),
        }, function (html) {
            const $p = $(html);
            $('#expenses-table-body').html($p.find('#expenses-table-body').html());
            $('#expense-pagination').html($p.find('#expense-pagination').html());
        });
    }

    function clearExpenseErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('').hide();
        $('#expense-form-errors').addClass('d-none').html('');
    }
    function showExpenseError(msg) {
        $('#expense-form-errors').removeClass('d-none').html(`<i class="ti tabler-alert-circle me-1"></i>${msg}`);
    }
});
</script>
@endsection
