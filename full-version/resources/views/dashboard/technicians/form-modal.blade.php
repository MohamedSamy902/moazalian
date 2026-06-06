<div class="modal-header border-bottom">
    <h5 class="modal-title"><i class="ti tabler-tool me-2 text-primary"></i>بيانات الفني</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="#" method="POST" id="techniciansForm">
    @csrf
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">اسم الفني <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="اسم الفني التجاري" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">رقم الهاتف <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" placeholder="05xxxxxxxx" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">الموقع الجغرافي</label>
                <input type="text" name="location" class="form-control" placeholder="المدينة، الحي...">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">حالة التوثيق</label>
                <select name="is_verified" class="form-select">
                    <option value="1">موثق (الهوية معتمدة)</option>
                    <option value="0">قيد المراجعة</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">التخصصات (الأجهزة)</label>
                <select name="specialties[]" class="form-select" multiple>
                    <option>تكييف</option>
                    <option>ثلاجات</option>
                    <option>غسالات</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">الرصيد المتاح (العمولات)</label>
                <div class="input-group">
                    <input type="number" name="balance" class="form-control" value="0">
                    <span class="input-group-text">EGP</span>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">حالة الحساب</label>
                <select name="status" class="form-select">
                    <option value="active">مفعل</option>
                    <option value="suspended">موقوف</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer border-top">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary"><i class="ti tabler-device-floppy me-1"></i> حفظ الفني</button>
    </div>
</form>