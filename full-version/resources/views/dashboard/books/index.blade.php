@extends('layouts.layoutMaster')
@section('title', 'الكتب')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">الكتب PDF</h4>
      <p class="text-muted mb-0">إدارة مؤلفات معاذ عليان — {{ $books->total() }} كتاب</p>
    </div>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
      <i class="ti ti-plus me-1"></i>إضافة كتاب
    </a>
  </div>

  {{-- Info banner --}}
  <div class="alert alert-info border-0 mb-4" style="background:rgba(30,150,255,.08)">
    <i class="ti ti-info-circle me-2"></i>
    الكتب المُفعَّل عليها <strong>ظهور في الرئيسية</strong> ستظهر تلقائياً في قسم "مؤلفات معاذ عليان" في الصفحة الرئيسية.
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>عنوان الكتاب</th>
            <th>المؤلف</th>
            <th>التصنيف</th>
            <th>PDF</th>
            <th class="text-center">في الرئيسية</th>
            <th>إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($books as $book)
          <tr>
            <td class="text-muted" style="font-size:.8rem">{{ $book->id }}</td>
            <td>
              <div style="font-size:.9rem;font-weight:600;max-width:280px">
                {{ \Illuminate\Support\Str::limit($book->getTranslation('title','ar'), 70) }}
              </div>
            </td>
            <td style="font-size:.82rem;color:var(--bs-secondary)">
              {{ $book->getTranslation('author','ar') }}
            </td>
            <td>
              <span class="badge bg-label-primary" style="font-size:.72rem">
                {{ $book->category?->label() ?? '—' }}
              </span>
            </td>
            <td>
              @if($book->pdf_path)
                @php
                  $pdfUrl = str_starts_with($book->pdf_path,'http')
                    ? $book->pdf_path
                    : asset('storage/' . $book->pdf_path);
                @endphp
                <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="font-size:.72rem">
                  <i class="ti ti-eye me-1"></i>عرض
                </a>
              @else
                <span class="text-muted" style="font-size:.78rem">لا يوجد</span>
              @endif
            </td>
            <td class="text-center">
              {{-- Toggle switch --}}
              <div class="form-check form-switch d-flex justify-content-center">
                <input class="form-check-input featured-toggle" type="checkbox"
                  id="featured-{{ $book->id }}"
                  data-id="{{ $book->id }}"
                  data-url="{{ route('admin.books.toggle-featured', $book->id) }}"
                  {{ $book->is_featured ? 'checked' : '' }}
                  style="cursor:pointer;width:2.2rem;height:1.2rem">
              </div>
            </td>
            <td>
              <div class="d-flex gap-1">
                <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-sm btn-icon btn-outline-primary">
                  <i class="ti ti-edit"></i>
                </a>
                <button class="btn btn-sm btn-icon btn-outline-danger"
                  onclick="deleteBook({{ $book->id }})">
                  <i class="ti ti-trash"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              <i class="ti ti-book" style="font-size:2.5rem"></i>
              <p class="mt-2">لا توجد كتب</p>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($books->hasPages())
    <div class="card-footer d-flex justify-content-center">
      {{ $books->links() }}
    </div>
    @endif
  </div>

</div>
@endsection

@push('page-script')
<script>
// Toggle featured via AJAX
document.querySelectorAll('.featured-toggle').forEach(function(toggle) {
  toggle.addEventListener('change', function() {
    const url = this.dataset.url;
    const checkbox = this;
    checkbox.disabled = true;

    fetch(url, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      }
    })
    .then(r => r.json())
    .then(data => {
      checkbox.disabled = false;
      if (data.success) {
        // Toast notification
        const type = data.is_featured ? 'success' : 'secondary';
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
        toast.style.zIndex = 9999;
        toast.innerHTML = `<i class="ti ti-check me-2"></i>${data.message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
      }
    })
    .catch(() => {
      checkbox.disabled = false;
      checkbox.checked = !checkbox.checked; // revert on error
    });
  });
});

// Delete book
function deleteBook(id) {
  if (!confirm('هل تريد حذف هذا الكتاب؟')) return;
  fetch(`/ar/admin/books/${id}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Accept': 'application/json',
    }
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) location.reload();
  });
}
</script>
@endpush
