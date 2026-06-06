@extends('layouts/layoutMaster')
@section('title', 'إدارة المشرفين')

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
    <h4 class="mb-0"><i class="ti tabler-users me-2"></i>إدارة المشرفين</h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('admin.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>المشرفون</span>
    </div>
  </div>
  <a href="{{ route('admin.admins.create') }}" data-ajax-modal class="btn btn-primary">
    <i class="ti tabler-plus me-1"></i> إضافة مشرف جديد
  </a>
</div>

{{-- ══ Admins Table ════════════════════════════════════════════════ --}}
<div class="card">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الأدوار</th>
                    <th>تاريخ الإضافة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($admins as $admin)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="avatar-wrapper me-2">
                                <div class="avatar avatar-sm me-2">
                                    <img src="{{ $admin->avatar_url }}" alt="Avatar" class="rounded-circle">
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-medium">{{ $admin->name }}</span>
                            </div>
                        </div>
                    </td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        @foreach($admin->roles as $role)
                            <span class="badge bg-label-primary">{{ __($role->name) }}</span>
                        @endforeach
                        @if($admin->roles->isEmpty())
                            <span class="badge bg-label-secondary">بلا دور</span>
                        @endif
                    </td>
                    <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.admins.edit', $admin->id) }}" data-ajax-modal class="btn btn-sm btn-icon btn-label-secondary" title="تعديل">
                                <i class="ti tabler-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-icon btn-label-danger delete-record"
                                    data-id="{{ $admin->id }}"
                                    data-name="{{ $admin->name }}" title="حذف">
                                <i class="ti tabler-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">لا يوجد مشرفين لعرضهم</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex justify-content-center">
        {{ $admins->links() }}
    </div>
</div>

@endsection

@section('page-script')
<script>
document.querySelectorAll('.delete-record').forEach(btn => {
  btn.addEventListener('click', function() {
    const id = this.dataset.id;
    const name = this.dataset.name;
    const row = this.closest('tr');

    Swal.fire({
      title: 'تأكيد الحذف',
      text: `هل أنت متأكد أنك تريد حذف المشرف "${name}"؟`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'نعم، احذف',
      cancelButtonText: 'إلغاء',
      customClass: {
        confirmButton: 'btn btn-danger me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        fetch(`{{ url('admin/admins') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Error deleting admin');

            Swal.fire({
              icon: 'success',
              title: 'تم الحذف!',
              text: data.message,
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
