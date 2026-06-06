<div class="modal-header border-bottom">
    <h5 class="modal-title"><i class="ti tabler-tags me-2 text-primary"></i>إضافة علامة تجارية (براند)</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form class="row g-3">
        <div class="col-12 text-center mb-3">
            <div id="brand-logo-preview" class="mb-2 d-flex justify-content-center align-items-center bg-light rounded" style="height: 120px; border: 2px dashed #ddd;">
                <span class="text-muted small">معاينة اللوجو</span>
            </div>
            <label class="btn btn-sm btn-outline-primary mt-2">
                <i class="ti tabler-upload me-1"></i> رفع اللوجو
                <input type="file" class="image-preview-input d-none" data-preview="brand-logo-preview" accept="image/*">
            </label>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">اسم البراند <span class="text-muted small fw-normal">(Brand Name)</span></label>
            <input type="text" class="form-control" placeholder="مثال: Samsung, LG...">
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">الفئات المشمولة</label>
            <select class="form-select select2-modal" multiple>
                <option value="ac">مكيفات</option>
                <option value="fridge">ثلاجات</option>
                <option value="washer">غسالات</option>
                <option value="tv">شاشات</option>
            </select>
        </div>
    </form>
</div>
<div class="modal-footer border-top">
    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
    <button type="button" class="btn btn-primary"><i class="ti tabler-device-floppy me-1"></i> حفظ البراند</button>
</div>

<script>
$(document).ready(function() {
    $('.select2-modal').select2({
        dropdownParent: $('#brand-logo-preview').closest('.modal'),
        placeholder: "اختر الفئات...",
        width: '100%'
    });
});
</script>
