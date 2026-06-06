<div class="modal-header border-bottom">
    <h5 class="modal-title"><i class="ti tabler-briefcase me-2 text-primary"></i>إنشاء طلب صيانة جديد</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form class="row g-3" id="create-order-form">
        {{-- ── اختيار العميل ── --}}
        <div class="col-12">
            <label class="form-label fw-bold">العميل <span class="text-danger">*</span></label>
            <div class="d-flex gap-2 align-items-start">
                <div class="flex-grow-1">
                    <select class="form-select select2-modal" id="order-customer" required>
                        <option value="">بحث عن عميل...</option>
                        <option value="1">أحمد محمد</option>
                        <option value="2">سارة علي</option>
                        <option value="3">خالد حسن</option>
                    </select>
                </div>
                <button type="button" class="btn btn-label-success flex-shrink-0" id="btn-add-customer" title="إضافة عميل جديد">
                    <i class="ti tabler-user-plus me-1"></i> عميل جديد
                </button>
            </div>
        </div>

        {{-- ── نموذج العميل السريع (يظهر عند الحاجة) ── --}}
        <div class="col-12 d-none" id="quick-customer-form">
            <div class="card border-2 border-success border-opacity-25 bg-success bg-opacity-10 mb-0">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="fw-bold text-success"><i class="ti tabler-user-plus me-1"></i> إضافة عميل جديد بسرعة</div>
                        <button type="button" class="btn btn-sm btn-icon btn-label-danger" id="btn-cancel-customer">
                            <i class="ti tabler-x"></i>
                        </button>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="quick-cust-name" placeholder="الاسم الكامل">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="quick-cust-phone" placeholder="05xxxxxxxx">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">البريد الإلكتروني</label>
                            <input type="email" class="form-control form-control-sm" id="quick-cust-email" placeholder="example@email.com">
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-success" id="btn-save-quick-customer">
                            <i class="ti tabler-check me-1"></i> حفظ واختيار العميل
                        </button>
                        <button type="button" class="btn btn-sm btn-label-secondary" id="btn-cancel-customer2">إلغاء</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── الماركة والجهاز ── --}}
        <div class="col-md-6">
            <label class="form-label fw-bold">الماركة (Brand) <span class="text-danger">*</span></label>
            <select class="form-select select2-modal" id="order-brand" required>
                <option value="">اختر الماركة...</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand }}">{{ $brand }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">الجهاز <span class="text-danger">*</span></label>
            <select class="form-select select2-modal" id="order-device" required>
                <option value="">اختر الماركة أولاً</option>
            </select>
        </div>

        {{-- ── المركز والفني ── --}}
        <div class="col-md-6">
            <label class="form-label fw-bold">مركز الصيانة <span class="text-danger">*</span></label>
            <select class="form-select select2-modal" id="order-center" required>
                <option value="">اختر المركز...</option>
                @foreach($centers as $center)
                    <option value="{{ $center['id'] }}">{{ $center['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">الفني المكلف</label>
            <select class="form-select select2-modal" id="order-tech">
                <option value="">اختر المركز أولاً</option>
            </select>
        </div>

        <div class="col-12">
            <label class="form-label fw-bold">وصف المشكلة <span class="text-danger">*</span></label>
            <textarea class="form-control" rows="3" placeholder="اكتب تفاصيل المشكلة هنا..." required></textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">التكلفة المتوقعة (EGP)</label>
            <input type="number" class="form-control" placeholder="0.00">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">كود الخصم (اختياري)</label>
            <input type="text" class="form-control" placeholder="FIXIT20">
        </div>
    </form>
</div>
<div class="modal-footer border-top">
    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
    <button type="button" class="btn btn-primary" id="btn-submit-order">
        <i class="ti tabler-device-floppy me-1"></i> إنشاء الطلب
    </button>
</div>

<script>
(function() {
    // ── Select2 init ────────────────────────────────
    function initSelect2() {
        $('.select2-modal').each(function() {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                $(this).select2({
                    dropdownParent: $(this).closest('.modal'),
                    width: '100%',
                    dir: 'rtl',
                    minimumResultsForSearch: 0
                });
            }
        });
    }
    // Init immediately + after modal shown event
    initSelect2();
    $(document).on('shown.bs.modal', '#createOrderModal', function() {
        initSelect2();
    });

    const deviceData = @json($devices);

    // ── Brand → Device ──────────────────────────────
    $(document).on('change', '#order-brand', function() {
        const brand = $(this).val();
        const $dev  = $('#order-device');
        $dev.empty().append('<option value="">اختر الجهاز...</option>');
        if (brand && deviceData[brand]) {
            deviceData[brand].forEach(d => $dev.append(new Option(d, d)));
        }
        $dev.trigger('change.select2');
    });

    // ── Quick Customer: Show form ────────────────────
    $(document).on('click', '#btn-add-customer', function() {
        $('#quick-customer-form').removeClass('d-none');
        $('#quick-cust-name').focus();
    });

    // ── Quick Customer: Hide form ────────────────────
    $(document).on('click', '#btn-cancel-customer, #btn-cancel-customer2', function() {
        $('#quick-customer-form').addClass('d-none');
        $('#quick-cust-name, #quick-cust-phone, #quick-cust-email').val('');
    });

    // ── Quick Customer: Save ─────────────────────────
    $(document).on('click', '#btn-save-quick-customer', function() {
        const name  = $('#quick-cust-name').val().trim();
        const phone = $('#quick-cust-phone').val().trim();
        const email = $('#quick-cust-email').val().trim();

        if (!name) {
            $('#quick-cust-name').addClass('is-invalid').focus();
            return;
        }
        if (!phone || !/^05\d{8}$/.test(phone)) {
            $('#quick-cust-phone').addClass('is-invalid').focus();
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'warning', title: 'رقم الهاتف غير صحيح', text: 'يجب أن يبدأ بـ 05 ومكون من 10 أرقام', timer: 2500, showConfirmButton: false });
            }
            return;
        }
        $('#quick-cust-name, #quick-cust-phone').removeClass('is-invalid');

        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> جارٍ الحفظ...');

        setTimeout(() => {
            const newId   = 'new_' + Date.now();
            const display = name + ' — ' + phone;

            const $sel = $('#order-customer');
            const opt  = new Option(display, newId, true, true);
            $sel.append(opt).trigger('change');

            $('#quick-customer-form').addClass('d-none');
            $('#quick-cust-name, #quick-cust-phone, #quick-cust-email').val('');
            btn.prop('disabled', false).html('<i class="ti tabler-check me-1"></i> حفظ واختيار العميل');

            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'success', title: 'تم إضافة العميل', text: `تم اختيار "${name}" تلقائياً`, timer: 2000, showConfirmButton: false });
            }
        }, 600);
    });
})();
</script>