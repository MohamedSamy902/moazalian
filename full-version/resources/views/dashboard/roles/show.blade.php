@extends('layouts/layoutMaster')
@section('title', 'تفاصيل الدور: ' . $role['name_ar'] . ' - Fix-It')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

@section('content')

{{-- ══ Page Header ══════════════════════════════════════════════ --}}
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
  <div>
    <h4 class="mb-0">
      <i class="ti {{ $role['icon'] }} me-2 text-{{ $role['color'] }}"></i>
      {{ $role['name_ar'] }}
    </h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('admin.home') }}">الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <a href="{{ route('admin.roles.index') }}">الأدوار والصلاحيات</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>{{ $role['name_ar'] }}</span>
    </div>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-label-secondary">
      <i class="ti tabler-arrow-right me-1"></i> العودة
    </a>
    @if(!$role['is_system'])
    <a href="{{ route('admin.roles.edit', $role['id']) }}" data-ajax-modal class="btn btn-sm btn-primary">
      <i class="ti tabler-edit me-1"></i> تعديل الدور
    </a>
    @endif
  </div>
</div>

<div class="row g-4">

  {{-- ══ Left Column: Info + Users ═══════════════════════════════ --}}
  <div class="col-lg-4">

    {{-- Role Info Card --}}
    <div class="card glass-card border-0 mb-4">
      <div class="card-body p-4 text-center">
        <div class="role-avatar mx-auto mb-3 bg-label-{{ $role['color'] }}"
             style="width:80px;height:80px;border-radius:20px;display:flex;align-items:center;justify-content:center;">
          <i class="ti {{ $role['icon'] }} text-{{ $role['color'] }}" style="font-size:2.5rem"></i>
        </div>
        <h5 class="fw-bold mb-1">{{ $role['name_ar'] }}</h5>
        <div class="font-monospace text-muted small mb-2">{{ $role['name'] }}</div>
        @if($role['is_system'])
          <span class="badge bg-label-danger mb-3"><i class="ti tabler-lock me-1"></i>دور النظام</span>
        @else
          <span class="badge bg-label-success mb-3"><i class="ti tabler-puzzle me-1"></i>دور مخصص</span>
        @endif
        <p class="text-muted small mb-3" style="line-height:1.7">{{ $role['description'] }}</p>

        <div class="row g-2 text-center">
          <div class="col-6">
            <div class="p-2 bg-light rounded-2">
              <div class="fw-bold fs-5 text-primary">{{ $role['users_count'] }}</div>
              <div class="text-muted" style="font-size:0.75rem">مستخدم</div>
            </div>
          </div>
          <div class="col-6">
            <div class="p-2 bg-light rounded-2">
              <div class="fw-bold fs-5 text-success">{{ is_array($role['permissions']) ? count($role['permissions']) : 'الكل' }}</div>
              <div class="text-muted" style="font-size:0.75rem">صلاحية</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Assigned Users --}}
    <div class="card glass-card border-0">
      <div class="card-header border-bottom d-flex align-items-center justify-content-between">
        <h6 class="card-title mb-0"><i class="ti tabler-users me-2 text-primary"></i>المستخدمون المعيّنون</h6>
        <button class="btn btn-sm btn-label-primary btn-assign-user" data-bs-toggle="tooltip" title="تعيين مستخدم">
          <i class="ti tabler-user-plus"></i>
        </button>
      </div>
      <div class="card-body p-0">
        @forelse($assignedUsers as $user)
        <div class="d-flex align-items-center gap-3 p-3 border-bottom hover-row">
          <div class="avatar avatar-sm flex-shrink-0">
            <span class="avatar-initial rounded-circle bg-label-primary">{{ $user['avatar'] }}</span>
          </div>
          <div class="flex-grow-1">
            <div class="fw-semibold small">{{ $user['name'] }}</div>
            <div class="text-muted" style="font-size:0.75rem">{{ $user['email'] }}</div>
          </div>
          <button class="btn btn-xs btn-icon btn-label-danger btn-remove-user" data-id="{{ $user['id'] }}" data-name="{{ $user['name'] }}" title="إزالة من الدور" style="width:26px;height:26px;padding:0">
            <i class="ti tabler-x" style="font-size:0.75rem"></i>
          </button>
        </div>
        @empty
        <div class="text-center py-4 text-muted small">لا يوجد مستخدمون معيّنون</div>
        @endforelse
      </div>
    </div>
  </div>

  {{-- ══ Right Column: Permissions Matrix ════════════════════════ --}}
  <div class="col-lg-8">
    <div class="card glass-card border-0">
      <div class="card-header border-bottom d-flex align-items-center justify-content-between">
        <h6 class="card-title mb-0">
          <i class="ti tabler-key me-2 text-primary"></i>
          مصفوفة الصلاحيات
        </h6>
        @if($role['permissions'] === 'all')
          <span class="badge bg-label-danger">
            <i class="ti tabler-infinity me-1"></i>جميع الصلاحيات
          </span>
        @else
          <span class="badge bg-label-primary">{{ count($role['permissions']) }} صلاحية مفعّلة</span>
        @endif
      </div>
      <div class="card-body p-3">

        @foreach($matrix as $moduleName => [$moduleKey, $actions])
        @php
          $modulePerms = is_array($role['permissions']) ? collect($role['permissions'])->filter(fn($p) => strpos($p, $moduleKey . '.') === 0) : collect([]);
          $hasAny = $role['permissions'] === 'all' || $modulePerms->count() > 0;
          $hasAll = $role['permissions'] === 'all' || $modulePerms->count() === count($actions);
        @endphp

        <div class="permission-row mb-2 rounded-3 border overflow-hidden {{ $hasAny ? 'border-primary' : '' }} {{ $hasAny ? '' : 'opacity-50' }}">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-3 py-2 {{ $hasAll ? 'bg-label-primary' : ($hasAny ? 'bg-label-success' : '') }}">

            {{-- Module Name --}}
            <div class="d-flex align-items-center gap-2">
              @if($hasAll)
                <i class="ti tabler-circle-check text-primary" style="font-size:1rem"></i>
              @elseif($hasAny)
                <i class="ti tabler-circle-half-2 text-success" style="font-size:1rem"></i>
              @else
                <i class="ti tabler-circle-x text-muted" style="font-size:1rem"></i>
              @endif
              <span class="fw-semibold small">{{ $moduleName }}</span>
            </div>

            {{-- Action Badges --}}
            <div class="d-flex flex-wrap gap-1">
              @foreach($actions as $action => $actionNameAr)
              @php
                $permKey = $moduleKey . '.' . $action;
                $active  = $role['permissions'] === 'all' || (is_array($role['permissions']) && in_array($permKey, $role['permissions']));
              @endphp
              <span class="badge {{ $active ? 'bg-primary' : 'bg-label-secondary' }}" style="font-size:0.7rem;">
                <i class="ti {{ $active ? 'tabler-check' : 'tabler-x' }} me-1" style="font-size:0.6rem"></i>
                {{ $actionNameAr }}
              </span>
              @endforeach
            </div>
          </div>
        </div>
        @endforeach

      </div>
    </div>
  </div>

</div>

@endsection

@section('page-style')
<style>
.hover-row { transition: background 0.15s ease; }
.hover-row:hover { background: rgba(115,103,240,0.04); }
.permission-row { transition: all 0.2s ease; }
</style>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const btnAssign = document.querySelector('.btn-assign-user');
  if(btnAssign) {
    btnAssign.addEventListener('click', function() {
      let unassigned = @json($unassignedAdmins);
      if(unassigned.length === 0) {
          Swal.fire('تنبيه', 'لا يوجد مشرفين متاحين لتعيينهم', 'info');
          return;
      }
      
      let options = '<select id="assign-admin-select" class="form-select">';
      unassigned.forEach(admin => {
          options += `<option value="${admin.id}">${admin.name}</option>`;
      });
      options += '</select>';

      Swal.fire({
        title: 'تعيين مشرف لهذا الدور',
        html: options,
        showCancelButton: true,
        confirmButtonText: 'تعيين',
        cancelButtonText: 'إلغاء',
        customClass: {
          confirmButton: 'btn btn-primary me-3',
          cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
      }).then((result) => {
        if (result.isConfirmed) {
           let adminId = document.getElementById('assign-admin-select').value;
           fetch(`{{ route('admin.roles.assignUser', $role['id']) }}`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Accept': 'application/json'
              },
              body: JSON.stringify({admin_id: adminId})
           }).then(async r => {
               let data = await r.json();
               if(!r.ok) throw new Error(data.message || 'حدث خطأ غير متوقع');
               Swal.fire('نجاح', data.message, 'success').then(() => location.reload());
           }).catch(e => {
               Swal.fire('خطأ', e.message, 'error');
           });
        }
      });
    });
  }

  document.querySelectorAll('.btn-remove-user').forEach(btn => {
     btn.addEventListener('click', function() {
        let adminId = this.dataset.id;
        let adminName = this.dataset.name;
        Swal.fire({
          title: 'تأكيد الإزالة',
          text: `هل أنت متأكد من إزالة الصلاحية من ${adminName}؟`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'نعم، إزالة',
          cancelButtonText: 'إلغاء',
          customClass: {
            confirmButton: 'btn btn-danger me-3',
            cancelButton: 'btn btn-label-secondary'
          },
          buttonsStyling: false
        }).then((result) => {
          if (result.isConfirmed) {
             fetch(`{{ url('admin/roles/' . $role['id'] . '/remove-user') }}/${adminId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
             }).then(async r => {
                 let data = await r.json();
                 if(!r.ok) throw new Error(data.message || 'حدث خطأ غير متوقع');
                 Swal.fire('نجاح', data.message, 'success').then(() => location.reload());
             }).catch(e => {
                 Swal.fire('خطأ', e.message, 'error');
             });
          }
        });
     });
  });
});
</script>
@endsection
