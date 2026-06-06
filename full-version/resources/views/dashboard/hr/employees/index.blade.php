@extends('layouts/layoutMaster')
@section('title', 'الموظفين - Fix-It')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-id-badge me-2"></i>إدارة الموظفين</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}">الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <a href="{{ route('dashboard.hr.overview') }}">الموارد البشرية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>الموظفين</span>
        </div>
    </div>
    <button class="btn btn-primary ms-auto" id="btn-add-emp">
        <i class="ti tabler-plus me-1"></i> إضافة موظف جديد
    </button>
</div>

{{-- Stats --}}
<div class="row g-4 mb-4">
    @foreach($empStats as $s)
    <div class="col-sm-6 col-xl-3">
        <div class="card glass-card border-0">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon gradient-{{ $s['color'] }} flex-shrink-0">
                    <i class="ti {{ $s['icon'] }} text-white ti-md"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="card-label">{{ $s['label'] }}</div>
                    <div class="card-value mt-1">{{ $s['value'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="card glass-card border-0 mb-4">
    <div class="card-body py-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold">بحث</label>
                <input id="filter-search" class="form-control" placeholder="الاسم، المسمى الوظيفي، الهاتف...">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">القسم</label>
                <select id="filter-dept" class="form-select">
                    <option value="">كل الأقسام</option>
                    @foreach($departments as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">نوع الراتب</label>
                <select id="filter-salary" class="form-select">
                    <option value="">الكل</option>
                    <option value="monthly">شهري</option>
                    <option value="per_task">بالمهمة</option>
                    <option value="freelance">فريلانس</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">الحالة</label>
                <select id="filter-status" class="form-select">
                    <option value="">الكل</option>
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-label-secondary w-100" id="reset-filters">
                    <i class="ti tabler-refresh"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Cards Grid --}}
<div class="row g-4" id="employees-grid">
    @forelse($employees as $employee)
        @include('dashboard.hr.employees._card', ['employee' => $employee])
    @empty
    <div class="col-12" id="empty-state">
        <div class="text-center py-5">
            <i class="ti tabler-user-off" style="font-size:3rem;color:#c4c4ff"></i>
            <h6 class="text-muted mt-3">لا يوجد موظفين</h6>
            <p class="text-muted small">ابدأ بإضافة أول موظف للنظام</p>
        </div>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-center mt-4" id="emp-pagination">
    {{ $employees->appends(request()->query())->links('pagination::bootstrap-4') }}
</div>

{{-- Modal --}}
<div class="modal fade" id="empModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" id="emp-modal-content"></div>
    </div>
</div>
@endsection

<style>
.employee-card { transition:transform 0.2s, box-shadow 0.2s; }
.employee-card:hover { transform:translateY(-4px); box-shadow:0 8px 24px rgba(115,103,240,0.15); }
.salary-display { background:rgba(115,103,240,0.06); border-radius:10px; padding:8px 16px; display:inline-block; }
</style>

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

const EMPS_STORE_URL = '{{ route('dashboard.hr.employees.store') }}';
const EMPS_BASE_URL  = '{{ url('dashboard/hr/employees') }}';
const EMPS_INDEX_URL = '{{ route('dashboard.hr.employees.index') }}';
const CSRF           = '{{ csrf_token() }}';
const DEPARTMENTS    = @json($departments->map(fn($d) => ['id' => $d->id, 'name' => $d->name]));

$(document).ready(function () {

    // ── Add ───────────────────────────────────────────────────────
    $('#btn-add-emp').on('click', function () {
        $('#emp-modal-content').html(buildEmpForm('add'));
        bsShow('empModal');
    });

    // ── Edit ──────────────────────────────────────────────────────
    $(document).on('click', '.btn-edit-emp', function () {
        const id = $(this).data('id');
        $.get(EMPS_BASE_URL + '/' + id, function (html) {
            // For edit, we'll use a separate AJAX call
        });
        // Since we don't have a GET json endpoint, open form and let user fill
        $('#emp-modal-content').html(buildEmpForm('edit', id));
        bsShow('empModal');
    });

    function buildEmpForm(mode, id = null) {
        const isEdit = mode === 'edit';
        const action = isEdit ? EMPS_BASE_URL + '/' + id : EMPS_STORE_URL;
        const method = isEdit ? 'PUT' : 'POST';
        const title  = isEdit ? '<i class="ti tabler-edit me-2 text-primary"></i>تعديل الموظف' : '<i class="ti tabler-user-plus me-2 text-primary"></i>إضافة موظف جديد';

        let deptOptions = '';
        DEPARTMENTS.forEach(d => deptOptions += `<option value="${d.id}">${d.name}</option>`);

        return `
        <div class="modal-header border-bottom">
            <h5 class="modal-title">${title}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="empForm" action="${action}" method="POST">
            <input type="hidden" name="_token" value="${CSRF}">
            <input type="hidden" name="_method" value="${method}">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">الاسم الكامل <span class="text-danger">*</span></label>
                        <input type="text" id="emp-name" name="name" class="form-control" required>
                        <div class="invalid-feedback" id="err-name"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">المسمى الوظيفي <span class="text-danger">*</span></label>
                        <input type="text" id="emp-title" name="job_title" class="form-control" required>
                        <div class="invalid-feedback" id="err-job_title"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">رقم الهاتف</label>
                        <input type="text" id="emp-phone" name="phone" class="form-control" placeholder="05xxxxxxxx">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">رقم الهوية الوطنية</label>
                        <input type="text" id="emp-nid" name="national_id" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">الأقسام</label>
                        <select name="department_ids[]" class="form-select" multiple>
                            ${deptOptions}
                        </select>
                        <div class="form-text">يمكن اختيار أكثر من قسم (Ctrl+Click)</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">نوع الراتب <span class="text-danger">*</span></label>
                        <select id="emp-salary-type" name="salary_type" class="form-select">
                            <option value="monthly">شهري</option>
                            <option value="per_task">بالمهمة</option>
                            <option value="freelance">فريلانس</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="base-salary-field">
                        <label class="form-label fw-semibold">الراتب الأساسي</label>
                        <div class="input-group">
                            <input type="number" id="emp-salary" name="base_salary" class="form-control" value="0" min="0">
                            <span class="input-group-text">ر.س</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">تاريخ التعيين</label>
                        <input type="date" name="hire_date" class="form-control" value="${new Date().toISOString().split('T')[0]}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">الحالة <span class="text-danger">*</span></label>
                        <select name="status" class="form-select">
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div id="emp-form-errors" class="alert alert-danger mt-3 d-none"></div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary" id="btn-save-emp">
                    <i class="ti tabler-device-floppy me-1"></i> حفظ
                </button>
            </div>
        </form>`;
    }

    // ── Salary Type Toggle ────────────────────────────────────────
    $(document).on('change', '#emp-salary-type', function () {
        $('#base-salary-field').toggle(this.value === 'monthly');
    });

    // ── Submit ────────────────────────────────────────────────────
    $(document).on('submit', '#empForm', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $btn  = $('#btn-save-emp');
        clearEmpErrors();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>جارٍ الحفظ...');

        $.ajax({
            url:     $form.attr('action'),
            method:  'POST',
            data:    $form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function (res) {
                if (!res.success) { showEmpError(res.message); return; }
                bsHide('empModal');
                const grid = $('#employees-grid');
                if ($form.find('[name="_method"]').val() === 'PUT') {
                    $(`#emp-card-${res.data.id}`).replaceWith(res.data.card_html);
                } else {
                    $('#empty-state').remove();
                    grid.prepend(res.data.card_html);
                }
                Swal.fire({ icon: 'success', title: res.message, timer: 2000, showConfirmButton: false });
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (field, msgs) {
                        $(`#err-${field.replace('.','_')}`).text(msgs[0]).show();
                        $(`[name="${field}"]`).addClass('is-invalid');
                    });
                } else {
                    showEmpError(xhr.responseJSON?.message || 'حدث خطأ غير متوقع');
                }
                $btn.prop('disabled', false).html('<i class="ti tabler-device-floppy me-1"></i> حفظ');
            }
        });
    });

    // ── Delete ────────────────────────────────────────────────────
    $(document).on('click', '.btn-delete-emp', function () {
        const id   = $(this).data('id');
        const name = $(this).data('name');
        Swal.fire({
            title: 'حذف الموظف؟',
            text: `سيتم حذف "${name}" (السجل محفوظ)`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ti tabler-trash"></i> نعم، احذف',
            cancelButtonText: 'إلغاء',
            customClass: { confirmButton: 'btn btn-danger ms-1', cancelButton: 'btn btn-label-secondary' },
            buttonsStyling: false
        }).then(r => {
            if (!r.isConfirmed) return;
            $.ajax({
                url:     EMPS_BASE_URL + '/' + id,
                method:  'POST',
                data:    { _method: 'DELETE', _token: CSRF },
                headers: { 'Accept': 'application/json' },
                success: function (res) {
                    if (!res.success) return;
                    $(`#emp-card-${id}`).fadeOut(300, function () { $(this).remove(); });
                    Swal.fire({ icon: 'success', title: res.message, timer: 1800, showConfirmButton: false });
                }
            });
        });
    });

    // ── Live Filters ──────────────────────────────────────────────
    let timer;
    $('#filter-search').on('input', function () {
        clearTimeout(timer);
        timer = setTimeout(applyFilters, 400);
    });
    $('#filter-dept, #filter-salary, #filter-status').on('change', applyFilters);
    $('#reset-filters').on('click', function () {
        $('#filter-search').val('');
        $('#filter-dept, #filter-salary, #filter-status').val('');
        applyFilters();
    });

    function applyFilters() {
        $.get(EMPS_INDEX_URL, {
            search:      $('#filter-search').val(),
            department_id: $('#filter-dept').val(),
            salary_type: $('#filter-salary').val(),
            status:      $('#filter-status').val(),
        }, function (html) {
            const $p = $(html);
            $('#employees-grid').html($p.find('#employees-grid').html());
            $('#emp-pagination').html($p.find('#emp-pagination').html());
        });
    }

    function clearEmpErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('').hide();
        $('#emp-form-errors').addClass('d-none').html('');
    }
    function showEmpError(msg) {
        $('#emp-form-errors').removeClass('d-none').html(`<i class="ti tabler-alert-circle me-1"></i>${msg}`);
    }
});
</script>
@endsection
