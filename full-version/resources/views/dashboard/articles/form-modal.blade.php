@php $isEdit = isset($article); @endphp

<div class="modal-header">
    <h5 class="modal-title">
        <i class="ti ti-article me-2 text-primary"></i>
        {{ $isEdit ? 'تعديل المقال' : 'إضافة مقال جديد' }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form
    action="{{ $isEdit ? route('admin.articles.update', $article) : route('admin.articles.store') }}"
    method="POST"
    enctype="multipart/form-data"
    id="article-form"
>
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="modal-body" style="max-height:75vh;overflow-y:auto">

        {{-- Nav Tabs --}}
        <ul class="nav nav-tabs mb-4" id="articleTabs">
            <li class="nav-item">
                <a class="nav-link active" href="#tab-basic"   data-bs-toggle="tab"><i class="ti ti-info-circle me-1"></i>المعلومات الأساسية</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"        href="#tab-content" data-bs-toggle="tab"><i class="ti ti-file-text me-1"></i>المحتوى</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"        href="#tab-seo"     data-bs-toggle="tab"><i class="ti ti-search me-1"></i>SEO</a>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ════ TAB 1: Basic Info ════ --}}
            <div class="tab-pane fade show active" id="tab-basic">
                <div class="row g-3">

                    {{-- Title AR --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">العنوان (عربي) <span class="text-danger">*</span></label>
                        <input type="text" name="title[ar]" class="form-control"
                               value="{{ $isEdit ? $article->getTranslation('title','ar') : '' }}" required>
                    </div>
                    {{-- Title EN --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">العنوان (إنجليزي)</label>
                        <input type="text" name="title[en]" class="form-control"
                               value="{{ $isEdit ? $article->getTranslation('title','en') : '' }}">
                    </div>

                    {{-- Category --}}
                    <div class="col-6 col-md-4">
                        <label class="form-label fw-semibold">التصنيف <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->value }}" {{ ($isEdit && $article->category?->value === $cat->value) ? 'selected' : '' }}>
                                    {{ $cat->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Article Type --}}
                    <div class="col-6 col-md-4">
                        <label class="form-label fw-semibold">نوع المقال <span class="text-danger">*</span></label>
                        <select name="article_type" class="form-select" id="article-type-select">
                            <option value="html" {{ (!$isEdit || ($article->article_type ?? 'html') === 'html') ? 'selected' : '' }}>
                                HTML (نص كامل)
                            </option>
                            <option value="pdf" {{ ($isEdit && ($article->article_type ?? 'html') === 'pdf') ? 'selected' : '' }}>
                                PDF (ملف PDF)
                            </option>
                        </select>
                    </div>

                    {{-- Read Time --}}
                    <div class="col-6 col-md-4">
                        <label class="form-label fw-semibold">وقت القراءة (دقائق)</label>
                        <input type="number" name="read_time" class="form-control" min="1" max="999"
                               value="{{ $isEdit ? $article->read_time : '' }}">
                    </div>

                    {{-- Published --}}
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="a-is-published" name="is_published" value="1"
                                   {{ (!$isEdit || $article->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="a-is-published">منشور (مرئي على الموقع)</label>
                        </div>
                    </div>

                    {{-- Thumbnail --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">الصورة المصغرة</label>
                        @if($isEdit && $article->thumbnail)
                            <div class="mb-2">
                                <img id="thumb-preview" src="{{ asset($article->thumbnail) }}" alt="thumbnail"
                                     style="max-height:120px;border-radius:8px;border:1px solid #ddd">
                            </div>
                        @else
                            <img id="thumb-preview" src="" alt="" style="max-height:0;display:none">
                        @endif
                        <input type="file" name="thumbnail" class="form-control" accept="image/*"
                               onchange="previewImg(this,'thumb-preview')">
                    </div>

                    {{-- PDF Upload (shown when type=pdf) --}}
                    <div class="col-12" id="pdf-upload-row" style="{{ (!$isEdit || ($article->article_type ?? 'html') !== 'pdf') ? 'display:none' : '' }}">
                        <label class="form-label fw-semibold">ملف PDF</label>
                        @if($isEdit && $article->pdf_path)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $article->pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                    <i class="ti ti-file-type-pdf me-1"></i>عرض الملف الحالي
                                </a>
                            </div>
                        @endif
                        <input type="file" name="pdf_file" class="form-control" accept=".pdf">
                    </div>

                    {{-- Excerpt AR --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">الملخص (عربي)</label>
                        <textarea name="excerpt[ar]" class="form-control" rows="3">{{ $isEdit ? $article->getTranslation('excerpt','ar') : '' }}</textarea>
                    </div>
                    {{-- Excerpt EN --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">الملخص (إنجليزي)</label>
                        <textarea name="excerpt[en]" class="form-control" rows="3">{{ $isEdit ? $article->getTranslation('excerpt','en') : '' }}</textarea>
                    </div>

                </div>
            </div>

            {{-- ════ TAB 2: Content ════ --}}
            <div class="tab-pane fade" id="tab-content">
                <p class="text-muted small mb-2">
                    <i class="ti ti-info-circle me-1"></i>
                    اكتب المحتوى الكامل للمقال هنا (فقط للمقالات من نوع HTML).
                </p>
                <div class="mb-3">
                    <label class="form-label fw-semibold">المحتوى (عربي)</label>
                    <textarea name="body[ar]" class="form-control" rows="12" style="font-family:monospace;font-size:.9rem">{{ $isEdit ? $article->getTranslation('body','ar') : '' }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">المحتوى (إنجليزي)</label>
                    <textarea name="body[en]" class="form-control" rows="12" style="font-family:monospace;font-size:.9rem">{{ $isEdit ? $article->getTranslation('body','en') : '' }}</textarea>
                </div>
            </div>

            {{-- ════ TAB 3: SEO ════ --}}
            <div class="tab-pane fade" id="tab-seo">
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-1"></i>
                    اترك هذه الحقول فارغة وسيتم استخدام العنوان والملخص تلقائياً كـ SEO.
                </div>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">SEO Title (عربي)</label>
                        <input type="text" name="seo_title[ar]" class="form-control" maxlength="255"
                               value="{{ $isEdit ? $article->getTranslation('seo_title','ar') : '' }}">
                        <small class="text-muted">الأمثل: 50-60 حرف</small>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">SEO Title (إنجليزي)</label>
                        <input type="text" name="seo_title[en]" class="form-control" maxlength="255"
                               value="{{ $isEdit ? $article->getTranslation('seo_title','en') : '' }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">SEO Description (عربي)</label>
                        <textarea name="seo_description[ar]" class="form-control" rows="3" maxlength="500">{{ $isEdit ? $article->getTranslation('seo_description','ar') : '' }}</textarea>
                        <small class="text-muted">الأمثل: 120-160 حرف</small>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">SEO Description (إنجليزي)</label>
                        <textarea name="seo_description[en]" class="form-control" rows="3" maxlength="500">{{ $isEdit ? $article->getTranslation('seo_description','en') : '' }}</textarea>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">SEO Keywords (عربي)</label>
                        <input type="text" name="seo_keywords[ar]" class="form-control"
                               placeholder="معاذ عليان, مقارنة الأديان, ..."
                               value="{{ $isEdit ? $article->getTranslation('seo_keywords','ar') : '' }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">SEO Keywords (إنجليزي)</label>
                        <input type="text" name="seo_keywords[en]" class="form-control"
                               value="{{ $isEdit ? $article->getTranslation('seo_keywords','en') : '' }}">
                    </div>
                </div>
            </div>

        </div>{{-- /.tab-content --}}
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i>
            {{ $isEdit ? 'حفظ التعديلات' : 'حفظ المقال' }}
        </button>
    </div>
</form>

<script>
// Show/hide PDF upload field based on type
document.getElementById('article-type-select')?.addEventListener('change', function() {
    const pdfRow = document.getElementById('pdf-upload-row');
    pdfRow.style.display = this.value === 'pdf' ? '' : 'none';
});

// Image preview
function previewImg(input, previewId) {
    if (input.files && input.files[0]) {
        const preview = document.getElementById(previewId);
        preview.src = URL.createObjectURL(input.files[0]);
        preview.style.display = 'block';
        preview.style.maxHeight = '120px';
    }
}

// Submit via AJAX for modal context
document.getElementById('article-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);

    // Handle unchecked checkboxes
    if (!form.querySelector('#a-is-published').checked) {
        formData.set('is_published', '0');
    }

    const submitBtn = form.querySelector('[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الحفظ...';

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('universalModal')).hide();
            Swal.fire({ icon: 'success', title: data.message, timer: 2000, showConfirmButton: false })
                .then(() => window.location.reload());
        } else {
            const errors = data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || 'حدث خطأ');
            Swal.fire({ icon: 'error', title: 'خطأ', text: errors });
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ti ti-device-floppy me-1"></i> حفظ';
        }
    })
    .catch(() => {
        Swal.fire({ icon: 'error', title: 'خطأ في الاتصال' });
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ti ti-device-floppy me-1"></i> حفظ';
    });
});
</script>
