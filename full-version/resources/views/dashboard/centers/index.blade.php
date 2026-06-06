@extends('layouts/layoutMaster')
@section('title', 'مراكز الصيانة - Fix-It')

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
        <h4 class="mb-0"><i class="ti tabler-building-store"></i> إدارة مراكز الصيانة</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>مراكز الصيانة</span>
        </div>
    </div>
    <button type="button" class="btn btn-primary" id="btn-add-center">
        <i class="ti tabler-plus me-1"></i> إضافة مركز صيانة
    </button>
</div>

{{-- Stats --}}
<div class="row g-4 mb-4">
    @foreach($stats as $s)
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
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="ti tabler-search text-muted"></i></span>
                    <input type="text" id="filter-search" class="form-control border-start-0 ps-0" placeholder="اسم المركز، المالك، الهاتف...">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">الحالة</label>
                <select id="filter-status" class="form-select">
                    <option value="">جميع المراكز</option>
                    <option value="active">نشط</option>
                    <option value="suspended">موقوف</option>
                    <option value="pending">قيد المراجعة</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-label-secondary w-100" id="reset-filters">
                    <i class="ti tabler-refresh me-1"></i> إعادة ضبط
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card glass-card overflow-hidden position-relative">
    <div id="table-loader" class="d-none position-absolute w-100 h-100 d-flex align-items-center justify-content-center"
         style="z-index:10; background:rgba(255,255,255,0.75); top:0; left:0;">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <div class="card-datatable table-responsive">
        <table class="table table-hover fixit-table border-top">
            <thead>
                <tr>
                    <th>المركز</th>
                    <th>هاتف المركز</th>
                    <th>العمولة</th>
                    <th>إجمالي المحصل</th>
                    <th>مستحق المنصة</th>
                    <th>الحالة</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="centers-table-body">
                @forelse($centers as $center)
                    @include('dashboard.centers._row', compact('center'))
                @empty
                <tr id="empty-row">
                    <td colspan="7" class="text-center py-5">
                        <div class="empty-state-icon mb-3"><i class="ti tabler-building-store" style="font-size:2.8rem;color:#c4c4ff"></i></div>
                        <h6 class="text-muted">لا توجد مراكز صيانة</h6>
                        <p class="text-muted small mb-0">ابدأ بإضافة أول مركز للنظام</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center border-top py-3 px-4" id="center-pagination">
        <div class="small text-muted">
            عرض <strong>{{ $centers->firstItem() ?? 0 }}</strong> إلى <strong>{{ $centers->lastItem() ?? 0 }}</strong>
            من أصل <strong>{{ $centers->total() }}</strong> مركز
        </div>
        <nav>{{ $centers->appends(request()->query())->links('pagination::bootstrap-4') }}</nav>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="centerModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" id="center-modal-content"></div>
    </div>
</div>

@endsection

@section('page-script')
<script>

const CENTERS_BASE_URL = '{{ url('dashboard/centers') }}';
const CSRF             = '{{ csrf_token() }}';

var bsShow = function(el) {
    (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).show();
};
var bsHide = function(el) {
    var inst = bootstrap.Modal.getInstance(el);
    if (inst) inst.hide();
};

$(document).ready(function () {

    // ── Add ────────────────────────────────────────────────────────
    $('#btn-add-center').on('click', function () {
        $('#center-modal-content').html(buildCenterForm('add'));
        bsShow(document.getElementById('centerModal'));
    });

    // ── Edit ───────────────────────────────────────────────────────
    $(document).on('click', '.btn-edit-center', function () {
        const id = $(this).data('id');
        $.get(CENTERS_BASE_URL + '/' + id + '/edit', function (res) {
            if (!res.success) return;
            const d = res.data;
            $('#center-modal-content').html(buildCenterForm('edit', id, d));
            bsShow(document.getElementById('centerModal'));
        });
    });

    // ── Build Form (JS Template) ───────────────────────────────────
    function buildCenterForm(mode, id = null, d = {}) {
        const isEdit = mode === 'edit';
        const action = isEdit ? CENTERS_BASE_URL + '/' + id : CENTERS_BASE_URL;
        const hidden = isEdit ? '<input type="hidden" name="_method" value="PUT">' : '';
        const title  = isEdit
            ? '<i class="ti tabler-edit me-2 text-primary"></i>تعديل مركز الصيانة'
            : '<i class="ti tabler-plus me-2 text-primary"></i>إضافة مركز صيانة جديد';

        const v = (key) => d[key] ?? '';
        const nameAr = isEdit ? (d.name?.ar ?? '') : '';
        const nameEn = isEdit ? (d.name?.en ?? '') : '';
        const addrAr = isEdit ? (d.address?.ar ?? '') : '';
        const addrEn = isEdit ? (d.address?.en ?? '') : '';

        const branchesHtml = buildBranchesSection(isEdit && d.branches ? d.branches : null);
        const passRequired = isEdit ? '' : 'required';
        const passLabel    = isEdit ? 'كلمة المرور (اتركها فارغة لعدم التغيير)' : 'كلمة المرور <span class="text-danger">*</span>';

        return `
        <div class="modal-header border-bottom">
            <h5 class="modal-title">${title}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="centerForm" action="${action}" method="POST">
            <input type="hidden" name="_token" value="${CSRF}">${hidden}
            <div class="modal-body">
                <div class="row g-3">

                    <!-- ─ معلومات المركز ─ -->
                    <div class="col-12">
                        <small class="text-muted fw-semibold text-uppercase" style="letter-spacing:.05em">معلومات المركز</small>
                        <hr class="mt-1 mb-2">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">اسم المركز (عربي) <span class="text-danger">*</span></label>
                        <input type="text" name="name[ar]" value="${nameAr}" class="form-control" required>
                        <div class="invalid-feedback" id="err-name_ar"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">اسم المركز (إنجليزي) <span class="text-danger">*</span></label>
                        <input type="text" name="name[en]" value="${nameEn}" class="form-control" required>
                        <div class="invalid-feedback" id="err-name_en"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">العنوان (عربي)</label>
                        <input type="text" name="address[ar]" value="${addrAr}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">العنوان (إنجليزي)</label>
                        <input type="text" name="address[en]" value="${addrEn}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">هاتف المركز</label>
                        <input type="text" name="phone" value="${v('phone')}" class="form-control">
                        <div class="invalid-feedback" id="err-phone"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">البريد الإلكتروني</label>
                        <input type="email" name="email" value="${v('email')}" class="form-control">
                    </div>

                    <!-- ─ بيانات المالك ─ -->
                    <div class="col-12 mt-2">
                        <small class="text-muted fw-semibold text-uppercase" style="letter-spacing:.05em">بيانات المالك (حساب النظام)</small>
                        <hr class="mt-1 mb-2">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">اسم المالك <span class="text-danger">*</span></label>
                        <input type="text" name="owner_name" value="${v('owner_name')}" class="form-control" required>
                        <div class="invalid-feedback" id="err-owner_name"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">هاتف المالك <span class="text-danger">*</span></label>
                        <input type="text" name="owner_phone" value="${v('owner_phone')}" class="form-control" required>
                        <div class="invalid-feedback" id="err-owner_phone"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">بريد المالك <span class="text-danger">*</span></label>
                        <input type="email" name="owner_email" value="${v('owner_email')}" class="form-control" required>
                        <div class="invalid-feedback" id="err-owner_email"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">${passLabel}</label>
                        <input type="password" name="password" class="form-control" ${passRequired}>
                        <div class="invalid-feedback" id="err-password"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" class="form-control" ${passRequired}>
                    </div>

                    <!-- ─ الفروع ─ -->
                    <div class="col-12 mt-2 d-flex justify-content-between align-items-center">
                        <small class="text-muted fw-semibold text-uppercase" style="letter-spacing:.05em">الفروع</small>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-branch-btn">
                            <i class="ti tabler-plus"></i> إضافة فرع
                        </button>
                    </div>
                    <div class="col-12"><hr class="mt-0 mb-2"></div>
                    <div class="col-12" id="branches-container">
                        ${branchesHtml}
                    </div>

                    <div id="center-form-errors" class="col-12 alert alert-danger d-none mt-2"></div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary" id="btn-save-center">
                    <i class="ti tabler-device-floppy me-1"></i> حفظ المركز
                </button>
            </div>
        </form>`;
    }

    function buildBranchesSection(branches) {
        if (!branches || branches.length === 0) {
            return buildBranchItem(0, null);
        }
        return branches.map((b, i) => buildBranchItem(i, b)).join('');
    }

    function buildBranchItem(bIndex, branch = null) {
        const isFirstBranch = bIndex === 0;
        const removeBtn = isFirstBranch ? '' : `<button type="button" class="btn btn-sm btn-icon btn-text-danger remove-branch"><i class="ti tabler-trash"></i></button>`;
        const title = isFirstBranch ? 'الفرع الرئيسي' : `فرع إضافي`;
        const idInput = branch ? `<input type="hidden" name="branches[${bIndex}][id]" value="${branch.id}">` : '';
        const nameAr = branch ? (branch.name?.ar ?? '') : '';
        const nameEn = branch ? (branch.name?.en ?? '') : '';

        const phonesHtml = (branch && branch.phones && branch.phones.length > 0)
            ? branch.phones.map((p, pi) => buildPhoneItem(bIndex, pi, p)).join('')
            : buildPhoneItem(bIndex, 0, null);

        return `
        <div class="card bg-lighter mb-3 branch-item" data-index="${bIndex}">
            <div class="card-body p-3">
                ${idInput}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-semibold">${title}</h6>
                    ${removeBtn}
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <input type="text" name="branches[${bIndex}][name][ar]" value="${nameAr}" class="form-control form-control-sm" placeholder="اسم الفرع (عربي)" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="branches[${bIndex}][name][en]" value="${nameEn}" class="form-control form-control-sm" placeholder="اسم الفرع (English)" required>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="phones-container" data-bindex="${bIndex}">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="fw-semibold text-muted"><i class="ti tabler-phone me-1"></i>الهواتف</small>
                                <button type="button" class="btn btn-xs btn-label-success add-phone-btn"><i class="ti tabler-plus"></i></button>
                            </div>
                            ${phonesHtml}
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }

    function buildPhoneItem(bIndex, pIndex, phone = null) {
        const val = phone ? phone.phone : '';
        const idInput = phone ? `<input type="hidden" name="branches[${bIndex}][phones][${pIndex}][id]" value="${phone.id}">` : '';
        const removeBtn = pIndex === 0 ? '' : `<button class="btn btn-outline-danger remove-phone" type="button"><i class="ti tabler-x"></i></button>`;
        return `
        <div class="input-group input-group-sm mb-1 phone-item">
            ${idInput}
            <input type="text" name="branches[${bIndex}][phones][${pIndex}][phone]" value="${val}" class="form-control" placeholder="05xxxxxxxx" required>
            ${removeBtn}
        </div>`;
    }

    // ── Branch Events ──────────────────────────────────────────────
    let branchIndex = 1;
    $(document).on('click', '#add-branch-btn', function () {
        $('#branches-container').append(buildBranchItem(branchIndex++));
    });
    $(document).on('click', '.remove-branch', function () {
        $(this).closest('.branch-item').remove();
    });
    $(document).on('click', '.add-phone-btn', function () {
        const container = $(this).closest('.phones-container');
        const bIndex    = container.data('bindex');
        const pIndex    = container.find('.phone-item').length;
        container.append(buildPhoneItem(bIndex, pIndex));
    });
    $(document).on('click', '.remove-phone', function () {
        $(this).closest('.phone-item').remove();
    });

    // ── Submit ─────────────────────────────────────────────────────
    $(document).on('submit', '#centerForm', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $btn  = $('#btn-save-center');
        clearCenterErrors();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>جارٍ الحفظ...');

        $.ajax({
            url:     $form.attr('action'),
            method:  'POST',
            data:    $form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function (res) {
                if (!res.success) { showCenterError(res.message); return; }
                bsHide(document.getElementById('centerModal'));
                const tbody = $('#centers-table-body');
                if ($form.find('[name="_method"]').val() === 'PUT') {
                    $(`tr[data-id="${res.data.id}"]`).replaceWith(res.data.row_html);
                } else {
                    $('#empty-row').remove();
                    tbody.prepend(res.data.row_html);
                }
                Swal.fire({ icon: 'success', title: res.message, timer: 2000, showConfirmButton: false });
            },
            error: function (xhr) {
                $btn.prop('disabled', false).html('<i class="ti tabler-device-floppy me-1"></i> حفظ المركز');
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (field, msgs) {
                        const key = field.replace(/\./g, '_').replace(/\[/g, '_').replace(/\]/g, '');
                        $(`#err-${key}`).text(msgs[0]).closest('.form-control, select').addClass('is-invalid');
                        $(`[name="${field}"]`).addClass('is-invalid');
                    });
                    showCenterError('يوجد أخطاء في البيانات، يرجى المراجعة.');
                } else {
                    showCenterError(xhr.responseJSON?.message ?? 'حدث خطأ غير متوقع');
                }
            }
        });
    });

    // ── Toggle Status ──────────────────────────────────────────────
    $(document).on('click', '.btn-toggle-center', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $.ajax({
            url:     CENTERS_BASE_URL + '/' + id + '/toggle',
            method:  'POST',
            data:    { _method: 'PATCH', _token: CSRF },
            headers: { 'Accept': 'application/json' },
            success: function (res) {
                if (!res.success) return;
                $(`tr[data-id="${id}"] .status-badge`)
                    .attr('class', `badge bg-label-${res.data.status === 'active' ? 'success' : 'danger'} status-badge`)
                    .html(res.data.status_badge);
                Swal.fire({ icon: 'success', title: res.message, timer: 1500, showConfirmButton: false });
            }
        });
    });

    // ── Delete ─────────────────────────────────────────────────────
    $(document).on('click', '.btn-delete-center', function () {
        const id   = $(this).data('id');
        const name = $(this).data('name');
        Swal.fire({
            title: 'حذف المركز؟',
            text: `سيتم حذف "${name}" وجميع فروعه نهائياً`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ti tabler-trash"></i> نعم، احذف',
            cancelButtonText: 'إلغاء',
            customClass: { confirmButton: 'btn btn-danger ms-1', cancelButton: 'btn btn-label-secondary' },
            buttonsStyling: false
        }).then(r => {
            if (!r.isConfirmed) return;
            $.ajax({
                url:     CENTERS_BASE_URL + '/' + id,
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

    // ── Filters ────────────────────────────────────────────────────
    let timer;
    $('#filter-search').on('input', function () {
        clearTimeout(timer);
        timer = setTimeout(applyFilters, 400);
    });
    $('#filter-status').on('change', applyFilters);
    $('#reset-filters').on('click', function () {
        $('#filter-search').val('');
        $('#filter-status').val('');
        applyFilters();
    });

    function applyFilters() {
        $('#table-loader').removeClass('d-none');
        $.get(CENTERS_BASE_URL, {
            search: $('#filter-search').val(),
            status: $('#filter-status').val(),
        }, function (html) {
            const $p = $(html);
            $('#centers-table-body').html($p.find('#centers-table-body').html());
            $('#center-pagination').html($p.find('#center-pagination').html());
            $('#table-loader').addClass('d-none');
        });
    }

    function clearCenterErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#center-form-errors').addClass('d-none').html('');
    }
    function showCenterError(msg) {
        $('#center-form-errors').removeClass('d-none').html(`<i class="ti tabler-alert-circle me-1"></i>${msg}`);
    }
});
</script>
@endsection