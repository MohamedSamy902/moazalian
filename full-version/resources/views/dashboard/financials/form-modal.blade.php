<div class="modal-header border-bottom">
    <h5 class="modal-title"><i class="ti tabler-cash me-2 text-success"></i>تسجيل تسوية مالية (سداد عمولة)</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="#" method="POST" id="financialsForm">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label">مقدم الخدمة (المركز أو الفني)</label>
                <select name="provider_id" class="form-select">
                    <option>المركز السعودي للصيانة (مستحق: 1200 EGP)</option>
                    <option>خالد عبدالله (مستحق: 75 EGP)</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">المبلغ المسدد للتطبيق</label>
                <div class="input-group">
                    <input type="number" name="amount" class="form-control" required>
                    <span class="input-group-text">EGP</span>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">طريقة الدفع</label>
                <select name="payment_method" class="form-select">
                    <option>تحويل بنكي</option>
                    <option>دفع إلكتروني (بوابة)</option>
                    <option>كاش (يدوي)</option>
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">ملاحظات والتفاصيل البنكية</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="رقم الحوالة أو المرجع..."></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-success">تأكيد السداد</button>
    </div>
</form>