@extends('layouts.layoutMaster')
@section('title', __('Articles'))

@section('vendor-style')
@vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection
@section('vendor-script')
@vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">{{ __('Content & Interfaces') }} /</span> {{ __('Articles') }}
        </h4>
        <button class="btn btn-primary" onclick="openUniversalModal('{{ route('admin.articles.create') }}', 'modal-xl')">
            <i class="ti ti-plus me-1"></i> إضافة مقال
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.articles.index') }}" class="d-flex gap-3 flex-wrap align-items-end">
                <div class="flex-grow-1" style="min-width:200px">
                    <label class="form-label">البحث</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="عنوان المقال..." value="{{ request('search') }}">
                    </div>
                </div>
                <div style="min-width:150px">
                    <label class="form-label">التصنيف</label>
                    <select name="category" class="form-select">
                        <option value="">الكل</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->value }}" {{ request('category') == $cat->value ? 'selected' : '' }}>
                                {{ $cat->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="min-width:130px">
                    <label class="form-label">النوع</label>
                    <select name="article_type" class="form-select">
                        <option value="">الكل</option>
                        <option value="html" {{ request('article_type') == 'html' ? 'selected' : '' }}>HTML</option>
                        <option value="pdf"  {{ request('article_type') == 'pdf'  ? 'selected' : '' }}>PDF</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-filter me-1"></i> فلترة
                    </button>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
                        <i class="ti ti-refresh"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>النوع</th>
                        <th>التصنيف</th>
                        <th>وقت القراءة</th>
                        <th>الحالة</th>
                        <th>تاريخ النشر</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                    <tr>
                        <td>{{ $article->id }}</td>
                        <td style="max-width:300px;white-space:normal">
                            <div class="fw-semibold" style="font-size:.88rem">{{ \Illuminate\Support\Str::limit($article->getTranslation('title', 'ar'), 80) }}</div>
                            @if($article->getTranslation('title', 'en'))
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($article->getTranslation('title', 'en'), 60) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $article->article_type === 'pdf' ? 'bg-label-danger' : 'bg-label-success' }}">
                                <i class="ti {{ $article->article_type === 'pdf' ? 'ti-file-type-pdf' : 'ti-file-type-html' }} me-1"></i>
                                {{ strtoupper($article->article_type ?? 'html') }}
                            </span>
                        </td>
                        <td><span class="badge bg-label-info">{{ $article->category?->label() ?? '—' }}</span></td>
                        <td>{{ $article->read_time ? $article->read_time . ' د' : '—' }}</td>
                        <td>
                            <button
                                class="btn btn-sm {{ $article->is_published ? 'btn-success' : 'btn-secondary' }}"
                                id="pub-btn-{{ $article->id }}"
                                onclick="togglePublished({{ $article->id }}, this)"
                                title="{{ $article->is_published ? 'منشور — اضغط للإخفاء' : 'مسودة — اضغط للنشر' }}"
                            >
                                <i class="ti {{ $article->is_published ? 'ti-eye' : 'ti-eye-off' }}"></i>
                                {{ $article->is_published ? 'منشور' : 'مسودة' }}
                            </button>
                        </td>
                        <td>{{ $article->published_at?->format('Y-m-d') ?? '—' }}</td>
                        <td>
                            <button onclick="openUniversalModal('{{ route('admin.articles.edit', $article) }}', 'modal-xl')"
                                    class="btn btn-sm btn-icon btn-label-primary" title="تعديل">
                                <i class="ti ti-pencil"></i>
                            </button>
                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline"
                                  onsubmit="return confirmDelete(event, this)">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon btn-label-danger" title="حذف">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="ti ti-article" style="font-size:2rem;color:#aaa"></i>
                            <p class="mt-2 text-muted">لا توجد مقالات</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-4 pb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">إجمالي: {{ $articles->total() }} مقال</small>
            {{ $articles->links() }}
        </div>
    </div>

</div>

{{-- Universal Modal --}}
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
function openUniversalModal(url, sizeClass = '') {
    const contentDiv = document.getElementById('universalModalContent');
    const dialogDiv  = contentDiv.parentElement;
    dialogDiv.className = 'modal-dialog modal-dialog-centered ' + sizeClass;
    contentDiv.innerHTML = '<div class="d-flex justify-content-center p-5"><div class="spinner-border text-primary" role="status"></div></div>';
    window.bsShow('universalModal');
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
        .then(r => { if (!r.ok) throw new Error(); return r.text(); })
        .then(html => {
            contentDiv.innerHTML = html;
            Array.from(contentDiv.getElementsByTagName('script')).forEach(s => eval(s.innerText));
        })
        .catch(() => {
            contentDiv.innerHTML = '<div class="p-5 text-center text-danger"><i class="ti ti-alert-circle" style="font-size:2rem"></i><p>حدث خطأ</p></div>';
        });
}

function confirmDelete(e, form) {
    e.preventDefault();
    Swal.fire({
        title: 'تأكيد الحذف',
        text: 'هل أنت متأكد؟ لا يمكن التراجع!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء',
        confirmButtonColor: '#dc3545',
    }).then(result => { if (result.isConfirmed) form.submit(); });
    return false;
}

function togglePublished(id, btn) {
    fetch(`/ar/admin/articles/${id}/toggle-published`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            btn.className = 'btn btn-sm ' + (data.is_published ? 'btn-success' : 'btn-secondary');
            btn.innerHTML = `<i class="ti ${data.is_published ? 'ti-eye' : 'ti-eye-off'}"></i> ${data.is_published ? 'منشور' : 'مسودة'}`;
            Swal.fire({ icon: 'success', title: data.message, timer: 1500, showConfirmButton: false });
        }
    });
}
</script>
@endsection
