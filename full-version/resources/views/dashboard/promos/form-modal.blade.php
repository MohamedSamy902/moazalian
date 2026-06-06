<div class="modal-header border-bottom">
    <h5 class="modal-title"><i class="ti tabler-ticket me-2 text-primary"></i>إنشاء كود خصم جديد</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">كود الخصم <span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="مثال: FIXIT2024">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">نوع الخصم</label>
            <select class="form-select">
                <option value="percentage">نسبة مئوية (%)</option>
                <option value="fixed">مبلغ ثابت (EGP)</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">قيمة الخصم <span class="text-danger">*</span></label>
            <input type="number" class="form-control" placeholder="القيمة">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">تاريخ الانتهاء</label>
            <input type="date" class="form-control">
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">الحد الأقصى للاستخدام</label>
            <input type="number" class="form-control" placeholder="اترك فارغاً لعدد لا نهائي">
        </div>
    </form>
</div>
<div class="modal-footer border-top">
    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
    <button type="button" class="btn btn-primary"><i class="ti tabler-device-floppy me-1"></i> حفظ الكود</button>
</div>
