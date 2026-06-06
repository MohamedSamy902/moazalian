<div class="modal-header border-bottom">
    <h5 class="modal-title"><i class="ti tabler-devices me-2 text-primary"></i>إعدادات الجهاز / الفئة</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="#" method="POST" id="devicesForm">
    @csrf
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold">اسم الفئة (الجهاز) <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="مثال: غسالة أتوماتيك" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">أيقونة / صورة الجهاز</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">الماركات المدعومة <span class="text-muted small fw-normal">(اختياري، مفصولة بفاصلة)</span></label>
                <input type="text" name="brands" class="form-control" placeholder="LG, Samsung, Toshiba, ...">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">الحالة</label>
                <select name="is_active" class="form-select">
                    <option value="1">مفعل — يظهر للعملاء</option>
                    <option value="0">معطل مؤقتاً</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer border-top">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary"><i class="ti tabler-device-floppy me-1"></i> حفظ الجهاز</button>
    </div>
</form>