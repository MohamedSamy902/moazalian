{{-- ══ Add / Edit Role Modal ═══════════════════════════════════════ --}}
<div class="modal-header border-bottom">
  <h5 class="modal-title">
    <i class="ti {{ isset($role) && $role ? 'tabler-edit' : 'tabler-shield-plus' }} me-2 text-primary"></i>
    {{ isset($role) && $role ? 'تعديل الدور: ' . $role['name_ar'] : 'إضافة دور جديد' }}
  </h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body" style="max-height:80vh;overflow-y:auto">
  <form id="role-form" class="row g-4" data-action="{{ isset($role) && $role ? route('admin.roles.update', $role['id']) : route('admin.roles.store') }}" data-method="{{ isset($role) && $role ? 'PUT' : 'POST' }}">

    {{-- ── Basic Info ─────────────────────────────────────────── --}}
    <div class="col-12">
      <div class="card border-0 bg-light rounded-3 p-3 mb-1">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold">الاسم بالعربية <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="ti tabler-shield text-muted"></i></span>
              <input type="text" class="form-control" id="role-name-ar"
                     placeholder="مثال: مدير مالي"
                     value="{{ isset($role) && $role ? $role['name_ar'] : '' }}" required>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">الاسم بالإنجليزية (Slug) <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="ti tabler-code text-muted"></i></span>
              <input type="text" class="form-control font-monospace" id="role-name-en"
                     placeholder="finance-manager"
                     value="{{ isset($role) && $role ? $role['name'] : '' }}" required>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-bold">وصف الدور</label>
            <textarea class="form-control" id="role-description" rows="2"
                      placeholder="اكتب وصفاً موجزاً لهذا الدور وصلاحياته...">{{ isset($role) && $role ? $role['description'] : '' }}</textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">لون الشارة</label>
            <div class="d-flex gap-2 flex-wrap" id="color-picker">
              @foreach(['primary','success','danger','warning','info','secondary'] as $color)
              <label class="color-option" style="cursor:pointer">
                <input type="radio" name="role-color" value="{{ $color }}"
                       class="d-none color-radio"
                       {{ (isset($role) && $role && $role['color'] === $color) || (!isset($role) || !$role) && $color === 'primary' ? 'checked' : '' }}>
                <span class="badge bg-{{ $color }} px-3 py-2 color-badge"
                      style="font-size:0.8rem;cursor:pointer;border:2px solid transparent;transition:all 0.2s">
                  {{ ['الأزرق','الأخضر','الأحمر','البرتقالي','السماوي','الرمادي'][array_search($color, ['primary','success','danger','warning','info','secondary'])] }}
                </span>
              </label>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ── Permissions Matrix ──────────────────────────────────── --}}
    <div class="col-12">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="fw-bold"><i class="ti tabler-key me-2 text-primary"></i>مصفوفة الصلاحيات</div>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-sm btn-label-success" id="select-all-perms">
            <i class="ti tabler-check-all me-1"></i> تحديد الكل
          </button>
          <button type="button" class="btn btn-sm btn-label-secondary" id="clear-all-perms">
            <i class="ti tabler-square me-1"></i> إلغاء الكل
          </button>
        </div>
      </div>

      <div class="permissions-matrix">
        @foreach($matrix as $moduleName => [$moduleKey, $actions])
        <div class="permission-module mb-3 card border-0 bg-light rounded-3">
          <div class="card-body p-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
              {{-- Module Toggle (select all for this module) --}}
              <div class="d-flex align-items-center gap-2">
                <div class="form-check form-check-inline m-0">
                  <input class="form-check-input module-toggle" type="checkbox"
                         id="module-{{ $moduleKey }}"
                         data-module="{{ $moduleKey }}">
                </div>
                <label class="fw-semibold mb-0" for="module-{{ $moduleKey }}" style="cursor:pointer">
                  {{ $moduleName }}
                </label>
              </div>

              {{-- Permission Checkboxes --}}
              <div class="d-flex flex-wrap gap-2">
                @foreach($actions as $action)
                @php
                  $permKey = $moduleKey . '.' . strtolower(str_replace([' ', '(', ')'], ['-', '', ''], $action));
                  $checked = isset($role) && $role && is_array($role['permissions'])
                             && in_array($permKey, $role['permissions']);
                @endphp
                <div class="form-check form-check-inline m-0 perm-check" data-module="{{ $moduleKey }}">
                  <input class="form-check-input perm-checkbox" type="checkbox"
                         id="perm-{{ $permKey }}"
                         name="permissions[]"
                         value="{{ $permKey }}"
                         {{ $checked ? 'checked' : '' }}>
                  <label class="form-check-label small" for="perm-{{ $permKey }}">{{ $action }}</label>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>

  </form>
</div>

<div class="modal-footer border-top">
  <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
    <i class="ti tabler-x me-1"></i> إلغاء
  </button>
  <button type="button" class="btn btn-primary" id="btn-save-role">
    <i class="ti tabler-device-floppy me-1"></i>
    {{ isset($role) && $role ? 'حفظ التعديلات' : 'إنشاء الدور' }}
  </button>
</div>

<script>
(function() {
  // ── Color Picker ────────────────────────────────────────────────
  document.querySelectorAll('.color-radio').forEach(radio => {
    function updateBadge(r) {
      document.querySelectorAll('.color-badge').forEach(b => {
        b.style.border = '2px solid transparent';
        b.style.opacity = '0.65';
      });
      const badge = r.closest('.color-option').querySelector('.color-badge');
      badge.style.border = '2px solid rgba(0,0,0,0.3)';
      badge.style.opacity = '1';
    }
    radio.addEventListener('change', () => updateBadge(radio));
    if (radio.checked) updateBadge(radio);
  });

  // ── Auto-slug from Arabic name ──────────────────────────────────
  document.getElementById('role-name-ar').addEventListener('input', function() {
    const slug = document.getElementById('role-name-en');
    if (!slug.dataset.manual) {
      slug.value = this.value
        .replace(/\s+/g, '-')
        .replace(/[^\w\u0600-\u06FF-]/g, '')
        .toLowerCase();
    }
  });
  document.getElementById('role-name-en').addEventListener('input', function() {
    this.dataset.manual = '1';
  });

  // ── Module Toggle (select all in module) ────────────────────────
  document.querySelectorAll('.module-toggle').forEach(toggle => {
    // Set indeterminate state based on children
    function syncToggle(mod) {
      const checks = document.querySelectorAll(`.perm-checkbox[value^="${mod}."]`);
      const checked = Array.from(checks).filter(c => c.checked).length;
      toggle.checked = checked === checks.length;
      toggle.indeterminate = checked > 0 && checked < checks.length;
    }

    toggle.addEventListener('change', function() {
      const mod = this.dataset.module;
      document.querySelectorAll(`.perm-checkbox[value^="${mod}."]`).forEach(c => {
        c.checked = this.checked;
      });
    });

    // Listen to children
    const mod = toggle.dataset.module;
    document.querySelectorAll(`.perm-checkbox[value^="${mod}."]`).forEach(c => {
      c.addEventListener('change', () => syncToggle(mod));
      syncToggle(mod); // init
    });
  });

  // ── Select / Clear All ──────────────────────────────────────────
  document.getElementById('select-all-perms').addEventListener('click', () => {
    document.querySelectorAll('.perm-checkbox').forEach(c => { c.checked = true; });
    document.querySelectorAll('.module-toggle').forEach(t => { t.checked = true; t.indeterminate = false; });
  });
  document.getElementById('clear-all-perms').addEventListener('click', () => {
    document.querySelectorAll('.perm-checkbox').forEach(c => { c.checked = false; });
    document.querySelectorAll('.module-toggle').forEach(t => { t.checked = false; t.indeterminate = false; });
  });

  // ── Save ─────────────────────────────────────────────────────────
  document.getElementById('btn-save-role').addEventListener('click', function() {
    const nameAr = document.getElementById('role-name-ar').value.trim();
    const nameEn = document.getElementById('role-name-en').value.trim();
    const perms  = document.querySelectorAll('.perm-checkbox:checked');

    if (!nameAr || !nameEn) {
      Swal.fire({ icon: 'warning', title: 'حقول مطلوبة', text: 'يرجى إدخال اسم الدور بالعربية والإنجليزية', timer: 2500, showConfirmButton: false });
      return;
    }
    if (perms.length === 0) {
      Swal.fire({ icon: 'warning', title: 'لا توجد صلاحيات', text: 'يرجى تحديد صلاحية واحدة على الأقل', timer: 2500, showConfirmButton: false });
      return;
    }

    const form = document.getElementById('role-form');
    const actionUrl = form.dataset.action;
    const method = form.dataset.method;
    
    // Prepare data
    const permValues = Array.from(perms).map(p => p.value);
    const payload = {
        _token: '{{ csrf_token() }}',
        _method: method,
        name: nameEn,
        name_ar: nameAr,
        permissions: permValues
    };

    const btn = this;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جارٍ الحفظ...';

    fetch(actionUrl, {
        method: 'POST', // always POST for fetch, using _method for PUT
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
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
})();
</script>
