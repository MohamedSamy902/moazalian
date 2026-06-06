@extends('layouts/layoutMaster')
@section('title', 'الأدوار والصلاحيات - Fix-It')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])
@endsection
@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

@section('content')

{{-- ══ Page Header ══════════════════════════════════════════════ --}}
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
  <div>
    <h4 class="mb-0"><i class="ti tabler-shield-lock me-2"></i>الأدوار والصلاحيات</h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('admin.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>الأدوار والصلاحيات</span>
    </div>
  </div>
  <a href="{{ route('admin.roles.create') }}" data-ajax-modal class="btn btn-primary">
    <i class="ti tabler-plus me-1"></i> إضافة دور جديد
  </a>
</div>

{{-- ══ Stats Cards ═══════════════════════════════════════════════ --}}
<div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="avatar avatar-lg bg-label-primary flex-shrink-0">
          <span class="avatar-initial rounded"><i class="ti tabler-shield-lock ti-md"></i></span>
        </div>
        <div>
          <div class="card-label mb-1">إجمالي الأدوار</div>
          <h5 class="card-value mb-0">{{ $stats['total_roles'] }}</h5>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="avatar avatar-lg bg-label-success flex-shrink-0">
          <span class="avatar-initial rounded"><i class="ti tabler-users ti-md"></i></span>
        </div>
        <div>
          <div class="card-label mb-1">المستخدمون المعيّنون</div>
          <h5 class="card-value mb-0">{{ $stats['total_users'] }}</h5>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="avatar avatar-lg bg-label-danger flex-shrink-0">
          <span class="avatar-initial rounded"><i class="ti tabler-lock ti-md"></i></span>
        </div>
        <div>
          <div class="card-label mb-1">أدوار النظام</div>
          <h5 class="card-value mb-0">{{ $stats['system_roles'] }}</h5>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="avatar avatar-lg bg-label-info flex-shrink-0">
          <span class="avatar-initial rounded"><i class="ti tabler-puzzle ti-md"></i></span>
        </div>
        <div>
          <div class="card-label mb-1">أدوار مخصصة</div>
          <h5 class="card-value mb-0">{{ $stats['custom_roles'] }}</h5>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ══ Roles Grid ════════════════════════════════════════════════ --}}
<div class="row g-4">
  @foreach($roles as $role)
  <div class="col-xl-4 col-md-6 fade-in-up">
    <div class="card glass-card border-0 h-100 role-card">
      <div class="card-body p-4">

        {{-- Header --}}
        <div class="d-flex align-items-start justify-content-between mb-3">
          <div class="d-flex align-items-center gap-3">
            <div class="role-icon bg-label-{{ $role['color'] }}" style="width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;">
              <i class="ti {{ $role['icon'] }} text-{{ $role['color'] }}" style="font-size:1.5rem"></i>
            </div>
            <div>
              <div class="fw-bold fs-6">{{ $role['name_ar'] }}</div>
              <div class="text-muted small font-monospace">{{ $role['name'] }}</div>
            </div>
          </div>
          <div class="d-flex flex-column align-items-end gap-1">
            @if($role['is_system'])
              <span class="badge bg-label-danger" style="font-size:0.7rem">
                <i class="ti tabler-lock me-1" style="font-size:0.65rem"></i>نظام
              </span>
            @else
              <span class="badge bg-label-success" style="font-size:0.7rem">
                <i class="ti tabler-puzzle me-1" style="font-size:0.65rem"></i>مخصص
              </span>
            @endif
          </div>
        </div>

        {{-- Description --}}
        <p class="text-muted small mb-3" style="line-height:1.6">{{ $role['description'] }}</p>

        {{-- Users Count --}}
        <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom">
          <div class="users-stack d-flex">
            @for($i = 0; $i < min($role['users_count'], 4); $i++)
            <div class="avatar avatar-xs" style="margin-right:-8px">
              <span class="avatar-initial rounded-circle bg-label-{{ ['primary','success','warning','info','danger','secondary'][$i % 6] }}" style="font-size:0.65rem">
                {{ ['م','س','خ','ل','ن','د'][$i] }}
              </span>
            </div>
            @endfor
          </div>
          <span class="small text-muted ms-2">{{ $role['users_count'] }} مستخدم معيّن</span>
        </div>

        {{-- Permissions Preview --}}
        <div class="mb-3">
          <div class="small fw-bold text-muted mb-2">الصلاحيات</div>
          @if($role['permissions'] === 'all')
            <span class="badge bg-label-danger px-2 py-1">
              <i class="ti tabler-infinity me-1"></i>جميع الصلاحيات
            </span>
          @elseif($role['permissions'] === '*.view')
            <span class="badge bg-label-secondary px-2 py-1">
              <i class="ti tabler-eye me-1"></i>عرض فقط - كل الأقسام
            </span>
          @else
            <div class="d-flex flex-wrap gap-1">
              @foreach(array_slice((array)$role['permissions'], 0, 3) as $perm)
                <span class="badge bg-label-{{ $role['color'] }}" style="font-size:0.7rem">
                  {{ $perm }}
                </span>
              @endforeach
              @if(count((array)$role['permissions']) > 3)
                <span class="badge bg-label-secondary" style="font-size:0.7rem">
                  +{{ count((array)$role['permissions']) - 3 }} أخرى
                </span>
              @endif
            </div>
          @endif
        </div>

      </div>

      {{-- Footer Actions --}}
      <div class="card-footer bg-transparent border-top px-4 py-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.roles.show', $role['id']) }}" class="btn btn-sm btn-label-primary">
          <i class="ti tabler-eye me-1"></i> عرض الصلاحيات
        </a>
        <div class="d-flex gap-2">
          @if(!$role['is_system'])
            <a href="{{ route('admin.roles.edit', $role['id']) }}" data-ajax-modal
               class="btn btn-sm btn-icon btn-label-secondary" title="تعديل">
              <i class="ti tabler-edit"></i>
            </a>
            <button class="btn btn-sm btn-icon btn-label-danger delete-role" title="حذف"
                    data-id="{{ $role['id'] }}" data-name="{{ $role['name_ar'] }}"
                    data-users="{{ $role['users_count'] }}">
              <i class="ti tabler-trash"></i>
            </button>
          @else
            <button class="btn btn-sm btn-icon btn-label-secondary disabled" title="لا يمكن تعديل أدوار النظام" disabled>
              <i class="ti tabler-lock"></i>
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>
  @endforeach

  {{-- Add New Role Card --}}
  <div class="col-xl-4 col-md-6">
    <a href="{{ route('admin.roles.create') }}" data-ajax-modal class="text-decoration-none">
      <div class="card glass-card border-2 border-dashed h-100 d-flex align-items-center justify-content-center"
           style="min-height:280px;cursor:pointer;border-color:rgba(115,103,240,0.3) !important;">
        <div class="text-center p-4">
          <div class="mb-3" style="width:60px;height:60px;border-radius:16px;background:rgba(115,103,240,0.1);display:flex;align-items:center;justify-content:center;margin:0 auto">
            <i class="ti tabler-plus text-primary" style="font-size:1.8rem"></i>
          </div>
          <div class="fw-bold text-primary mb-1">إضافة دور جديد</div>
          <div class="text-muted small">أنشئ دوراً مخصصاً بصلاحيات محددة</div>
        </div>
      </div>
    </a>
  </div>
</div>

@endsection

@section('page-script')
<script>
// Delete Role with confirmation
document.querySelectorAll('.delete-role').forEach(btn => {
  btn.addEventListener('click', function() {
    const name  = this.dataset.name;
    const id    = this.dataset.id;
    const users = parseInt(this.dataset.users);
    const card  = this.closest('.col-xl-4');

    let warningText = `سيتم حذف الدور "${name}" بشكل نهائي.`;
    if (users > 0) {
      warningText += `\n\n⚠️ تحذير: هناك ${users} مستخدم معيّن لهذا الدور. يجب إعادة تعيينهم قبل الحذف.`;
    }

    Swal.fire({
      title: 'حذف الدور؟',
      html: `
        <div class="text-start">
          <p class="text-muted mb-3">سيتم حذف الدور <strong>"${name}"</strong> بشكل نهائي ولا يمكن التراجع عن ذلك.</p>
          ${users > 0 ? `<div class="alert alert-warning py-2 px-3 mb-0 text-start">
            <i class="ti tabler-alert-triangle me-1"></i>
            <strong>${users} مستخدم</strong> معيّن لهذا الدور — سيفقدون صلاحياتهم.
          </div>` : ''}
        </div>
      `,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ea5455',
      cancelButtonColor: '#82868b',
      confirmButtonText: '<i class="ti tabler-trash me-1"></i> نعم، احذف',
      cancelButtonText: 'إلغاء',
      customClass: {
        confirmButton: 'btn btn-danger ms-1',
        cancelButton:  'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(result => {
      if (result.isConfirmed) {
        fetch(`{{ url('admin/roles') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Error deleting role');
            
            Swal.fire({
              title: 'تم الحذف!',
              text: data.message,
              icon: 'success',
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(error => {
            Swal.fire('خطأ', error.message, 'error');
        });
      }
    });
  });
});
</script>
@endsection

@section('page-style')
<style>
.role-card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.role-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(115,103,240,0.15) !important;
}
.users-stack .avatar {
  border: 2px solid #fff;
}
</style>
@endsection
