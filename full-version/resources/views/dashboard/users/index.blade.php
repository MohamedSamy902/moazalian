@extends('layouts/layoutMaster')
@section('title', 'المشتركون - Fix-It')

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
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
  <div>
    <h4 class="mb-0">
      <i class="ti tabler-users me-2 text-primary"></i> إدارة المشتركين
    </h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('admin.home') }}">الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1 text-muted" style="font-size:0.6rem"></i>
      <span>المشتركون</span>
    </div>
  </div>
  @can('users.create')
  <button class="btn btn-primary" onclick="openUniversalModal('{{ route('admin.users.create') }}')">
    <i class="ti tabler-plus me-1"></i> إضافة مشترك جديد
  </button>
  @endcan
</div>

{{-- ══ Stats Cards ═══════════════════════════════════════════════ --}}
<div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="avatar avatar-lg bg-label-primary flex-shrink-0">
          <span class="avatar-initial rounded"><i class="ti tabler-users ti-md"></i></span>
        </div>
        <div>
          <div class="card-label mb-1">إجمالي المشتركين</div>
          <h5 class="card-value mb-0">{{ $stats['total'] }}</h5>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="avatar avatar-lg bg-label-success flex-shrink-0">
          <span class="avatar-initial rounded"><i class="ti tabler-user-check ti-md"></i></span>
        </div>
        <div>
          <div class="card-label mb-1">نشط</div>
          <h5 class="card-value mb-0">{{ $stats['active'] }}</h5>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="avatar avatar-lg bg-label-warning flex-shrink-0">
          <span class="avatar-initial rounded"><i class="ti tabler-user-exclamation ti-md"></i></span>
        </div>
        <div>
          <div class="card-label mb-1">غير نشط</div>
          <h5 class="card-value mb-0">{{ $stats['inactive'] }}</h5>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="avatar avatar-lg bg-label-danger flex-shrink-0">
          <span class="avatar-initial rounded"><i class="ti tabler-user-off ti-md"></i></span>
        </div>
        <div>
          <div class="card-label mb-1">محظور</div>
          <h5 class="card-value mb-0">{{ $stats['blocked'] }}</h5>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ══ Filter Bar ════════════════════════════════════════════════ --}}
<div class="card mb-4">
  <div class="card-body">
    <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3 align-items-end">
      <div class="col-md-5">
        <label class="form-label fw-bold text-muted mb-1">البحث</label>
        <div class="input-group">
          <span class="input-group-text bg-transparent border-end-0"><i class="ti tabler-search text-muted"></i></span>
          <input type="text" class="form-control border-start-0 ps-0" name="search" value="{{ request('search') }}" placeholder="الاسم، البريد، الهاتف، الديانة...">
        </div>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-bold text-muted mb-1">الحالة</label>
        <select class="form-select select2" name="status">
          <option value="">جميع الحالات</option>
          <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
          <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
          <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>محظور</option>
        </select>
      </div>
      <div class="col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-grow-1">
          <i class="ti tabler-filter me-1"></i> تصفية
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-label-secondary flex-grow-1">
          <i class="ti tabler-refresh me-1"></i> إعادة ضبط
        </a>
      </div>
    </form>
  </div>
</div>

{{-- ══ Users Table ═══════════════════════════════════════════════ --}}
<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>المشترك</th>
          <th>معلومات الاتصال</th>
          <th>الديانة</th>
          <th>تاريخ الانضمام</th>
          <th>الحالة</th>
          <th>الإجراءات</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse($users as $user)
        <tr>
          <td>
            <div class="d-flex align-items-center">
              <div class="avatar avatar-sm me-3">
                <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle object-fit-cover">
              </div>
              <div>
                <span class="fw-medium d-block">{{ $user->name }}</span>
              </div>
            </div>
          </td>
          <td>
            <div class="d-flex flex-column">
              <span class="text-muted small"><i class="ti tabler-mail me-1"></i>{{ $user->email }}</span>
              @if($user->phone)
                <span class="text-muted small"><i class="ti tabler-phone me-1"></i>{{ $user->phone }}</span>
              @else
                <span class="text-muted small opacity-50"><i class="ti tabler-phone me-1"></i>غير محدد</span>
              @endif
            </div>
          </td>
          <td>{{ $user->religion ?? 'غير محدد' }}</td>
          <td>
            <span class="badge bg-label-secondary">
              <i class="ti tabler-calendar-event me-1" style="font-size:0.8rem"></i>
              {{ $user->created_at->format('Y-m-d') }}
            </span>
          </td>
          <td>
            @if($user->status == 'active')
              <span class="badge bg-label-success"><i class="ti tabler-check me-1"></i> نشط</span>
            @elseif($user->status == 'blocked')
              <span class="badge bg-label-danger"><i class="ti tabler-ban me-1"></i> محظور</span>
            @else
              <span class="badge bg-label-warning"><i class="ti tabler-clock me-1"></i> غير نشط</span>
            @endif
          </td>
          <td>
            <div class="d-flex align-items-center gap-1">
              @can('users.edit')
              <button class="btn btn-sm btn-icon btn-label-secondary" onclick="openUniversalModal('{{ route('admin.users.edit', $user->id) }}')" data-bs-toggle="tooltip" title="تعديل">
                <i class="ti tabler-edit text-primary"></i>
              </button>
              @endcan
              
              @can('users.delete')
              <button class="btn btn-sm btn-icon btn-label-danger" onclick="deleteRecord('{{ route('admin.users.destroy', $user->id) }}', 'المشترك {{ $user->name }}')" data-bs-toggle="tooltip" title="حذف">
                <i class="ti tabler-trash"></i>
              </button>
              @endcan
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center py-5 text-muted">
            <i class="ti tabler-users-minus mb-3" style="font-size: 2.5rem; color:#c4c4ff"></i>
            <h6 class="text-muted">لا يوجد مشتركين مسجلين بعد</h6>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())
    <div class="card-footer border-top px-4 py-3">
      {{ $users->links() }}
    </div>
  @endif
</div>

{{-- ══ Universal Modal ═══════════════════════════════════════════ --}}
<div class="modal fade" id="universalModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" id="universalModalContent">
      <div class="d-flex justify-content-center p-4">
        <div class="spinner-border text-primary" role="status"></div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page-script')
<script>
let universalModal;

document.addEventListener('DOMContentLoaded', function () {
  universalModal = new bootstrap.Modal(document.getElementById('universalModal'));
  
  if ($.fn.select2) {
    $('.select2').select2({
      minimumResultsForSearch: Infinity,
      dir: 'rtl'
    });
  }

  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
});

function openUniversalModal(url) {
  const contentDiv = document.getElementById('universalModalContent');
  contentDiv.innerHTML = '<div class="d-flex justify-content-center p-5"><div class="spinner-border text-primary" role="status"></div></div>';
  universalModal.show();

  fetch(url, {
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'text/html'
    }
  })
  .then(response => {
    if (!response.ok) throw new Error('Network error');
    return response.text();
  })
  .then(html => {
    contentDiv.innerHTML = html;
    const scripts = contentDiv.getElementsByTagName('script');
    for (let i = 0; i < scripts.length; i++) {
      eval(scripts[i].innerText);
    }
  })
  .catch(error => {
    contentDiv.innerHTML = `<div class="p-5 text-center text-danger"><i class="ti tabler-alert-circle mb-2" style="font-size: 2rem"></i><p>حدث خطأ أثناء تحميل البيانات</p></div>`;
  });
}

function deleteRecord(url, name) {
  Swal.fire({
    title: 'تأكيد الحذف',
    text: `هل أنت متأكد من حذف ${name} بشكل نهائي؟`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'نعم، احذف!',
    cancelButtonText: 'إلغاء',
    customClass: {
      confirmButton: 'btn btn-danger me-3',
      cancelButton: 'btn btn-label-secondary'
    },
    buttonsStyling: false
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(url, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'حدث خطأ');
        return data;
      })
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'تم الحذف!',
            text: data.message,
            showConfirmButton: false,
            timer: 1500
          }).then(() => {
            window.location.reload();
          });
        }
      })
      .catch(error => {
        Swal.fire('خطأ', error.message, 'error');
      });
    }
  });
}
</script>
@endsection