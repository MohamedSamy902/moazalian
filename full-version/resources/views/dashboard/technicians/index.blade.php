@extends('layouts/layoutMaster')
@section('title', 'الفنيين - Fix-It')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/select2/select2.js'])
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0"><i class="ti tabler-users-group"></i> إدارة الفنيين</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>الفنيين</span>
        </div>
    </div>
    <div class="d-flex gap-2 ms-auto align-items-center">
        <div class="btn-group" role="group">
            <button class="btn btn-outline-primary active" id="btn-list-view" title="قائمة"><i class="ti tabler-list"></i></button>
            <button class="btn btn-outline-primary" id="btn-map-view" title="خريطة"><i class="ti tabler-map-2"></i></button>
        </div>
        <button type="button" class="btn btn-primary" id="btn-add-tech">
            <i class="ti tabler-plus me-1"></i> إضافة فني جديد
        </button>
    </div>
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
            <div class="col-md-3">
                <label class="form-label small fw-bold">بحث</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="ti tabler-search text-muted"></i></span>
                    <input type="text" id="filter-search" class="form-control border-start-0 ps-0" placeholder="الاسم أو الهاتف...">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">المركز</label>
                <select id="filter-center" class="form-select">
                    <option value="">كل المراكز</option>
                    @foreach(\App\Models\Center::active()->select('id','name')->get() as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">الحالة</label>
                <select id="filter-active" class="form-select">
                    <option value="">الكل</option>
                    <option value="1">نشط</option>
                    <option value="0">موقوف</option>
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
                    <th>الفني</th>
                    <th>المركز</th>
                    <th>الهاتف</th>
                    <th>التخصصات</th>
                    <th class="text-center">التقييم</th>
                    <th>الطلبات</th>
                    <th>الحالة</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="technicians-table-body">
                @forelse($technicians as $tech)
                @include('dashboard.technicians._row', ['tech' => $tech])
                @empty
                <tr id="empty-row">
                    <td colspan="8" class="text-center py-5">
                        <div class="empty-state-icon mb-3"><i class="ti tabler-tool" style="font-size:2.8rem;color:#c4c4ff"></i></div>
                        <h6 class="text-muted">لا يوجد فنيين</h6>
                        <p class="text-muted small mb-0">ابدأ بإضافة فني للنظام</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center border-top py-3 px-4" id="tech-pagination">
        <div class="small text-muted">
            عرض <strong>{{ $technicians->firstItem() ?? 0 }}</strong> إلى <strong>{{ $technicians->lastItem() ?? 0 }}</strong>
            من أصل <strong>{{ $technicians->total() }}</strong> فني
        </div>
        <nav>{{ $technicians->appends(request()->query())->links('pagination::bootstrap-4') }}</nav>
    </div>
</div>
</div>

{{-- Map View --}}
<div id="map-view" class="d-none">
    <div class="card glass-card border-0 shadow-sm overflow-hidden" style="height: 550px">
        <div class="w-100 h-100 d-flex align-items-center justify-content-center flex-column p-4 text-center bg-light">
            <i class="ti tabler-map-2 text-primary opacity-25" style="font-size:7rem"></i>
            <h4 class="fw-bold mb-1 mt-3">خريطة توزيع الفنيين</h4>
            <p class="text-muted">خريطة تفاعلية لمواقع الفنيين والطلبات — تتطلب تفعيل Google Maps API</p>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="techModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content" id="tech-modal-content"></div>
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

const TECHS_BASE_URL  = '{{ url('dashboard/technicians') }}';
const CENTERS_LIST    = @json(\App\Models\Center::active()->select('id','name')->get());
const CSRF            = '{{ csrf_token() }}';

$(document).ready(function () {

    // ── Add ───────────────────────────────────────────────────────
    $('#btn-add-tech').on('click', function () {
        $('#tech-modal-content').html(buildTechForm('add'));
        bsShow('techModal');
    });

    // ── Edit ──────────────────────────────────────────────────────
    $(document).on('click', '.btn-edit-tech', function () {
        const id = $(this).data('id');
        $.get(TECHS_BASE_URL + '/' + id + '/edit', function (res) {
            if (!res.success) return;
            const d = res.data;
            $('#tech-modal-content').html(buildTechForm('edit', id));
            $('#t-name').val(d.name);
            $('#t-phone').val(d.phone);
            $('#t-center').val(d.center_id);
            $('#t-notes').val(d.notes);
            bsShow('techModal');
        });
    });

    function buildTechForm(mode, id = null) {
        const isEdit = mode === 'edit';
        const action = isEdit ? TECHS_BASE_URL + '/' + id : TECHS_BASE_URL;
        const hidden = isEdit ? '<input type="hidden" name="_method" value="PUT">' : '';
        const title  = isEdit ? '<i class="ti tabler-edit me-2 text-primary"></i>تعديل الفني' : '<i class="ti tabler-plus me-2 text-primary"></i>إضافة فني جديد';

        let centerOptions = '<option value="">اختر المركز... *</option>';
        CENTERS_LIST.forEach(c => centerOptions += `<option value="${c.id}">${c.name}</option>`);

        return `
        <div class="modal-header border-bottom">
            <h5 class="modal-title">${title}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="techForm" action="${action}" method="POST">
            <input type="hidden" name="_token" value="${CSRF}">${hidden}
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">المركز <span class="text-danger">*</span></label>
                        <select id="t-center" name="center_id" class="form-select" required>
                            ${centerOptions}
                        </select>
                        <div class="invalid-feedback" id="err-center_id"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">اسم الفني <span class="text-danger">*</span></label>
                        <input type="text" id="t-name" name="name" class="form-control" required>
                        <div class="invalid-feedback" id="err-name"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">رقم الهاتف <span class="text-danger">*</span></label>
                        <input type="text" id="t-phone" name="phone" class="form-control" placeholder="05xxxxxxxx" required>
                        <div class="invalid-feedback" id="err-phone"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">ملاحظات</label>
                        <textarea id="t-notes" name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div id="tech-form-errors" class="alert alert-danger mt-3 d-none"></div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary" id="btn-save-tech">
                    <i class="ti tabler-device-floppy me-1"></i> حفظ
                </button>
            </div>
        </form>`;
    }

    // ── Submit ────────────────────────────────────────────────────
    $(document).on('submit', '#techForm', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $btn  = $('#btn-save-tech');
        clearTechErrors();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>جارٍ الحفظ...');

        $.ajax({
            url:     $form.attr('action'),
            method:  'POST',
            data:    $form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function (res) {
                if (!res.success) { showTechError(res.message); return; }
                bsHide('techModal');
                const tbody = $('#technicians-table-body');
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
                        $(`#err-${field.replace('.','_')}`).text(msgs[0]).show();
                        $(`[name="${field}"]`).addClass('is-invalid');
                    });
                } else {
                    showTechError(xhr.responseJSON?.message || 'حدث خطأ');
                }
                $btn.prop('disabled', false).html('<i class="ti tabler-device-floppy me-1"></i> حفظ');
            }
        });
    });

    // ── Toggle Active ─────────────────────────────────────────────
    $(document).on('click', '.btn-toggle-tech', function () {
        const id = $(this).data('id');
        $.ajax({
            url:     TECHS_BASE_URL + '/' + id + '/toggle',
            method:  'POST',
            data:    { _method: 'PATCH', _token: CSRF },
            headers: { 'Accept': 'application/json' },
            success: function (res) {
                if (!res.success) return;
                const $badge = $(`tr[data-id="${id}"] .status-badge`);
                const active = res.data.active;
                $badge.removeClass('bg-label-success bg-label-danger')
                      .addClass(active ? 'bg-label-success' : 'bg-label-danger')
                      .html(active ? '<i class="ti tabler-wifi me-1"></i>نشط' : '<i class="ti tabler-wifi-off me-1"></i>موقوف');
                Swal.fire({ icon: 'success', title: res.message, timer: 1500, showConfirmButton: false });
            }
        });
    });

    // ── Delete ────────────────────────────────────────────────────
    $(document).on('click', '.btn-delete-tech', function () {
        const id   = $(this).data('id');
        const name = $(this).data('name');
        Swal.fire({
            title: 'حذف الفني؟',
            text: `سيتم حذف "${name}"`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ti tabler-trash"></i> نعم، احذف',
            cancelButtonText: 'إلغاء',
            customClass: { confirmButton: 'btn btn-danger ms-1', cancelButton: 'btn btn-label-secondary' },
            buttonsStyling: false
        }).then(r => {
            if (!r.isConfirmed) return;
            $.ajax({
                url:     TECHS_BASE_URL + '/' + id,
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
    let timer;
    $('#filter-search').on('input', function () {
        clearTimeout(timer);
        timer = setTimeout(applyFilters, 400);
    });
    $('#filter-center, #filter-active').on('change', applyFilters);
    $('#reset-filters').on('click', function () {
        $('#filter-search').val('');
        $('#filter-center, #filter-active').val('');
        applyFilters();
    });

    function applyFilters() {
        $('#table-loader').removeClass('d-none');
        $.get(TECHS_BASE_URL, {
            search:    $('#filter-search').val(),
            center_id: $('#filter-center').val(),
            is_active: $('#filter-active').val(),
        }, function (html) {
            const $p = $(html);
            $('#technicians-table-body').html($p.find('#technicians-table-body').html());
            $('#tech-pagination').html($p.find('#tech-pagination').html());
            $('#table-loader').addClass('d-none');
        });
    }

    // ── View Toggle ───────────────────────────────────────────────
    $('#btn-map-view').on('click', function () {
        $('#list-view').addClass('d-none');
        $('#map-view').removeClass('d-none');
        $(this).addClass('active');
        $('#btn-list-view').removeClass('active');
    });
    $('#btn-list-view').on('click', function () {
        $('#map-view').addClass('d-none');
        $('#list-view').removeClass('d-none');
        $(this).addClass('active');
        $('#btn-map-view').removeClass('active');
    });

    function clearTechErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('').hide();
        $('#tech-form-errors').addClass('d-none').html('');
    }
    function showTechError(msg) {
        $('#tech-form-errors').removeClass('d-none').html(`<i class="ti tabler-alert-circle me-1"></i>${msg}`);
    }
});
</script>
@endsection
