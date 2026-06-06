<div class="modal-header border-bottom">
  <h5 class="modal-title">
    <i class="ti {{ isset($admin) && $admin ? 'tabler-edit' : 'tabler-user-plus' }} me-2 text-primary"></i>
    {{ isset($admin) && $admin ? 'تعديل بيانات المشرف' : 'إضافة مشرف جديد' }}
  </h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
  <form id="admin-form" class="row g-3" data-action="{{ isset($admin) && $admin ? route('admin.admins.update', $admin->id) : route('admin.admins.store') }}" data-method="{{ isset($admin) && $admin ? 'PUT' : 'POST' }}">
    
    <div class="col-12 d-flex justify-content-center mb-2">
        <div class="position-relative" style="width: 120px; height: 120px; cursor: pointer;" onclick="document.getElementById('admin-avatar').click()" title="تغيير الصورة">
            <img id="avatar-preview" 
                 src="{{ isset($admin) ? $admin->avatar_url : 'https://ui-avatars.com/api/?name=Admin&background=7367f0&color=fff&rounded=true' }}" 
                 class="rounded-circle object-fit-cover w-100 h-100 shadow-sm" 
                 style="border: 3px solid #7367f0;"
                 alt="Avatar">
            <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow" 
                 style="width: 36px; height: 36px; border: 2px solid #fff;">
                <i class="ti tabler-camera" style="font-size: 1.1rem;"></i>
            </div>
        </div>
        <input type="file" class="d-none" name="avatar" id="admin-avatar" accept="image/*" onchange="previewAvatar(event)">
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">الاسم <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="name" id="admin-name" required value="{{ isset($admin) ? $admin->name : '' }}">
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">البريد الإلكتروني <span class="text-danger">*</span></label>
        <input type="email" class="form-control" name="email" id="admin-email" required value="{{ isset($admin) ? $admin->email : '' }}">
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">كلمة المرور {!! isset($admin) ? '<small class="text-muted">(اتركها فارغة إذا لم ترد التغيير)</small>' : '<span class="text-danger">*</span>' !!}</label>
        <input type="password" class="form-control" name="password" id="admin-password" {{ isset($admin) ? '' : 'required' }}>
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">الأدوار والصلاحيات</label>
        <select class="select2 form-select" name="roles[]" id="admin-roles" multiple>
            @foreach($roles as $role)
                @php
                    $selected = isset($admin) && $admin->hasRole($role->name);
                @endphp
                <option value="{{ $role->name }}" {{ $selected ? 'selected' : '' }}>{{ __($role->name) }}</option>
            @endforeach
        </select>
    </div>

  </form>
</div>

<div class="modal-footer border-top">
  <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
    <i class="ti tabler-x me-1"></i> إلغاء
  </button>
  <button type="button" class="btn btn-primary" id="btn-save-admin">
    <i class="ti tabler-device-floppy me-1"></i>
    {{ isset($admin) && $admin ? 'حفظ التعديلات' : 'إضافة المشرف' }}
  </button>
</div>

<script>
$(document).ready(function() {
    $('#admin-roles').select2({
        dropdownParent: $('#admin-form').parent(),
        placeholder: "اختر الأدوار",
        allowClear: true
    });
});

document.getElementById('btn-save-admin').addEventListener('click', function() {
    const form = document.getElementById('admin-form');
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
    formData.append('name', document.getElementById('admin-name').value);
    formData.append('email', document.getElementById('admin-email').value);
    
    const password = document.getElementById('admin-password').value;
    if (password) {
        formData.append('password', password);
    }
    
    const avatarInput = document.getElementById('admin-avatar');
    if (avatarInput.files.length > 0) {
        formData.append('avatar', avatarInput.files[0]);
    }

    const roles = $('#admin-roles').val() || [];
    roles.forEach(role => {
        formData.append('roles[]', role);
    });

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

function previewAvatar(event) {
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
