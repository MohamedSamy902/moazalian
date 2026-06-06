<div class="modal-header border-bottom">
  <h5 class="modal-title">
    <i class="ti {{ isset($user) && $user ? 'tabler-edit' : 'tabler-user-plus' }} me-2 text-primary"></i>
    {{ isset($user) && $user ? 'تعديل بيانات المشترك' : 'إضافة مشترك جديد' }}
  </h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
  <form id="user-form" class="row g-3" data-action="{{ isset($user) && $user ? route('admin.users.update', $user->id) : route('admin.users.store') }}" data-method="{{ isset($user) && $user ? 'PUT' : 'POST' }}">
    
    <div class="col-12 d-flex justify-content-center mb-2 mt-3">
        <div class="position-relative" style="width: 120px; height: 120px; cursor: pointer;" onclick="document.getElementById('user-avatar').click()" title="تغيير الصورة">
            <img id="avatar-preview" 
                 src="{{ isset($user) ? $user->avatar_url : 'https://ui-avatars.com/api/?name=User&background=7367f0&color=fff&rounded=true' }}" 
                 class="rounded-circle object-fit-cover w-100 h-100 shadow-sm" 
                 style="border: 3px solid #7367f0;"
                 alt="Avatar">
            <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow" 
                 style="width: 36px; height: 36px; border: 2px solid #fff;">
                <i class="ti tabler-camera" style="font-size: 1.1rem;"></i>
            </div>
        </div>
        <input type="file" class="d-none" name="avatar" id="user-avatar" accept="image/*" onchange="previewUserAvatar(event)">
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">الاسم <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="name" id="user-name" required value="{{ isset($user) ? $user->name : '' }}">
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">البريد الإلكتروني <span class="text-danger">*</span></label>
        <input type="email" class="form-control" name="email" id="user-email" required value="{{ isset($user) ? $user->email : '' }}">
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">كلمة المرور {!! isset($user) ? '<small class="text-muted">(اتركها فارغة إذا لم ترد التغيير)</small>' : '<span class="text-danger">*</span>' !!}</label>
        <input type="password" class="form-control" name="password" id="user-password" {{ isset($user) ? '' : 'required' }} autocomplete="new-password">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">رقم الهاتف</label>
        <input type="text" class="form-control" name="phone" id="user-phone" value="{{ isset($user) ? $user->phone : '' }}" placeholder="01xxxxxxxxx">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">الديانة</label>
        <select class="form-select" name="religion" id="user-religion">
            <option value="">غير محدد</option>
            <option value="مسلم" {{ isset($user) && $user->religion == 'مسلم' ? 'selected' : '' }}>مسلم</option>
            <option value="مسيحي" {{ isset($user) && $user->religion == 'مسيحي' ? 'selected' : '' }}>مسيحي</option>
            <option value="أخرى" {{ isset($user) && $user->religion == 'أخرى' ? 'selected' : '' }}>أخرى</option>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">حالة الحساب <span class="text-danger">*</span></label>
        <select class="form-select" name="status" id="user-status" required>
            <option value="active" {{ (!isset($user)) || (isset($user) && $user->status == 'active') ? 'selected' : '' }}>نشط</option>
            <option value="inactive" {{ isset($user) && $user->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
            <option value="blocked" {{ isset($user) && $user->status == 'blocked' ? 'selected' : '' }}>محظور</option>
        </select>
    </div>

  </form>
</div>

<div class="modal-footer border-top">
  <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
    <i class="ti tabler-x me-1"></i> إلغاء
  </button>
  <button type="button" class="btn btn-primary" id="btn-save-user">
    <i class="ti tabler-device-floppy me-1"></i>
    {{ isset($user) && $user ? 'حفظ التعديلات' : 'إضافة المشترك' }}
  </button>
</div>

<script>
document.getElementById('btn-save-user').addEventListener('click', function() {
    const form = document.getElementById('user-form');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const actionUrl = form.dataset.action;
    const method = form.dataset.method;
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }
    formData.append('name', document.getElementById('user-name').value);
    formData.append('email', document.getElementById('user-email').value);
    
    const password = document.getElementById('user-password').value;
    if (password) {
        formData.append('password', password);
    }
    
    formData.append('phone', document.getElementById('user-phone').value);
    formData.append('religion', document.getElementById('user-religion').value);
    formData.append('status', document.getElementById('user-status').value);
    
    const avatarInput = document.getElementById('user-avatar');
    if (avatarInput.files.length > 0) {
        formData.append('avatar', avatarInput.files[0]);
    }

    const btn = this;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جارٍ الحفظ...';

    fetch(actionUrl, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Validation error');
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(btn.closest('.modal'));
            if (modal) modal.hide();
            Swal.fire({
                icon: 'success',
                title: 'تم الحفظ!',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.reload();
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({ icon: 'error', title: 'خطأ', text: error.message || 'حدث خطأ في الاتصال بالخادم' });
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});

function previewUserAvatar(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('avatar-preview');
        output.src = reader.result;
    };
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>