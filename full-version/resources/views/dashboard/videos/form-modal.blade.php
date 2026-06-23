<div class="modal-header border-bottom">
  <h5 class="modal-title">
    <i class="ti {{ isset($video) ? 'tabler-edit' : 'tabler-plus' }} me-2 text-primary"></i>
    {{ isset($video) ? __('Edit Video') : __('Add Video') }}
  </h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
  <form id="video-form" class="row g-3" data-action="{{ isset($video) ? route('admin.videos.update', $video) : route('admin.videos.store') }}" data-method="{{ isset($video) ? 'PUT' : 'POST' }}">
    
    <div class="col-md-6">
        <label class="form-label text-success">{{ __('Title (AR)') }} <span class="text-danger">*</span></label>
        <input type="text" name="title[ar]" class="form-control" value="{{ isset($video) ? $video->getTranslation('title', 'ar', false) : '' }}" required>
    </div>
    
    <div class="col-md-6">
        <label class="form-label text-info">{{ __('Title (EN)') }}</label>
        <input type="text" name="title[en]" class="form-control" value="{{ isset($video) ? $video->getTranslation('title', 'en', false) : '' }}">
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">{{ __('Video Type') }} <span class="text-danger">*</span></label>
        <select name="video_type" id="v-type-selector" class="form-select" required>
            <option value="external" {{ (isset($video) && $video->video_type == 'external') ? 'selected' : '' }}>{{ __('External URL (YouTube, Vimeo, etc)') }}</option>
            <option value="upload" {{ (isset($video) && $video->video_type == 'upload') ? 'selected' : '' }}>{{ __('Upload Video File') }}</option>
        </select>
    </div>

    <div class="col-md-6" id="v-url-row">
        <label class="form-label fw-bold">{{ __('Video URL') }} <span class="text-danger">*</span></label>
        <input type="url" name="video_url" id="v-video-url" class="form-control" value="{{ (isset($video) && $video->video_type == 'external') ? $video->video_url : '' }}" placeholder="https://youtube.com/watch?v=...">
    </div>

    <div class="col-md-6" id="v-file-row" style="display:none;">
        <label class="form-label fw-bold">{{ __('Upload Video') }} <span class="text-danger">*</span></label>
        <input type="file" name="video_file" id="v-video-file" class="form-control" accept="video/mp4,video/x-m4v,video/*">
        @if(isset($video) && $video->video_type == 'upload' && $video->video_url)
            <small class="text-success d-block mt-1"><i class="ti ti-check"></i> {{ __('Video uploaded previously') }}</small>
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">{{ __('Category') }} <span class="text-danger">*</span></label>
        <select name="category" class="form-select" required>
            <option value="">{{ __('Select Category') }}</option>
            @foreach(\App\Enums\VideoCategory::cases() as $category)
                <option value="{{ $category->value }}" {{ (isset($video) && $video->category?->value === $category->value) ? 'selected' : '' }}>
                    {{ $category->label() }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12 mt-4 mb-2">
        <hr>
        <h6 class="fw-bold mb-1">{{ __('Description') }}</h6>
        <p class="text-muted small">يمكنك كتابة الوصف وإدراج الصور والفيديوهات مباشرة داخل المحرر</p>
    </div>

    <div class="col-md-6">
        <label class="form-label text-success">{{ __('Description (AR)') }}</label>
        <textarea name="description[ar]" id="v-desc-ar" class="d-none">{{ isset($video) ? $video->getTranslation('description', 'ar', false) : '' }}</textarea>
        <div class="form-control p-0 border-0">
            <div id="quill-editor-ar" style="height: 300px;">
                {!! isset($video) ? $video->getTranslation('description', 'ar', false) : '' !!}
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <label class="form-label text-info">{{ __('Description (EN)') }}</label>
        <textarea name="description[en]" id="v-desc-en" class="d-none">{{ isset($video) ? $video->getTranslation('description', 'en', false) : '' }}</textarea>
        <div class="form-control p-0 border-0">
            <div id="quill-editor-en" style="height: 300px;">
                {!! isset($video) ? $video->getTranslation('description', 'en', false) : '' !!}
            </div>
        </div>
    </div>

    <div class="col-12 mt-4 mb-2">
        <hr>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-1">{{ __('References (Shorts)') }}</h6>
                <p class="text-muted small">المراجع المستخدمة في الحلقة (فيديوهات أو صور أو نصوص)</p>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-reference">
                <i class="ti ti-plus me-1"></i> إضافة مرجع
            </button>
        </div>
        <div id="references-container" class="mt-3">
            @if(isset($video) && $video->references->count() > 0)
                @foreach($video->references as $index => $ref)
                <div class="reference-item card border shadow-none mb-3">
                    <div class="card-body position-relative p-3">
                        <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 btn-remove-reference"></button>
                        <input type="hidden" name="references[{{ $index }}][id]" value="{{ $ref->id }}">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label text-success small">العنوان (AR)</label>
                                <input type="text" name="references[{{ $index }}][title][ar]" class="form-control form-control-sm mb-2" value="{{ $ref->getTranslation('title', 'ar', false) }}">
                                <label class="form-label text-info small">العنوان (EN)</label>
                                <input type="text" name="references[{{ $index }}][title][en]" class="form-control form-control-sm" value="{{ $ref->getTranslation('title', 'en', false) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">النوع والمحتوى</label>
                                <select name="references[{{ $index }}][type]" class="form-select form-select-sm mb-2 ref-type-selector">
                                    <option value="link" {{ $ref->type == 'link' ? 'selected' : '' }}>رابط خارجي</option>
                                    <option value="upload" {{ $ref->type == 'upload' ? 'selected' : '' }}>رفع ملف</option>
                                    <option value="text" {{ $ref->type == 'text' ? 'selected' : '' }}>نص</option>
                                </select>
                                
                                <div class="ref-input-container">
                                    <input type="url" name="{{ $ref->type == 'link' ? 'references['.$index.'][content]' : '' }}" class="form-control form-control-sm ref-content-link" value="{{ $ref->type == 'link' ? $ref->content : '' }}" style="{{ $ref->type == 'link' ? '' : 'display:none;' }}" placeholder="https://youtube.com/shorts/...">
                                    
                                    <div class="ref-content-upload" style="{{ $ref->type == 'upload' ? '' : 'display:none;' }}">
                                        <input type="file" name="{{ $ref->type == 'upload' ? 'references['.$index.'][file]' : '' }}" class="form-control form-control-sm" accept="video/*,image/*">
                                        @if($ref->type == 'upload' && $ref->content)
                                            <a href="{{ asset($ref->content) }}" target="_blank" class="small mt-1 d-block"><i class="ti ti-external-link"></i> عرض الملف المرفوع</a>
                                        @endif
                                    </div>

                                    <textarea name="{{ $ref->type == 'text' ? 'references['.$index.'][content]' : '' }}" class="form-control form-control-sm ref-content-text" style="{{ $ref->type == 'text' ? '' : 'display:none;' }}" rows="2">{{ $ref->type == 'text' ? $ref->content : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="col-12 mt-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="v-is-published" name="is_published" value="1" {{ (!isset($video) || $video->is_published) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold" for="v-is-published">{{ __('Published') }}</label>
        </div>
    </div>

    {{-- SEO Section --}}
    <div class="col-12 mt-4 mb-2">
        <hr>
        <div class="d-flex align-items-center gap-2 mb-3">
            <h6 class="fw-bold mb-0"><i class="ti ti-search text-primary me-1"></i>تحسين محركات البحث (SEO)</h6>
            <span class="badge bg-label-info">اختياري</span>
        </div>
        <p class="text-muted small">اتركها فارغة لاستخدام عنوان الفيديو ووصفه تلقائياً</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-success small">SEO Title (عربي)</label>
        <input type="text" name="seo_title[ar]" class="form-control form-control-sm" maxlength="255"
               value="{{ isset($video) ? $video->getTranslation('seo_title','ar',false) : '' }}"
               placeholder="عنوان صفحة البحث...">
    </div>
    <div class="col-md-6">
        <label class="form-label text-info small">SEO Title (إنجليزي)</label>
        <input type="text" name="seo_title[en]" class="form-control form-control-sm" maxlength="255"
               value="{{ isset($video) ? $video->getTranslation('seo_title','en',false) : '' }}">
    </div>
    <div class="col-md-6">
        <label class="form-label text-success small">SEO Description (عربي)</label>
        <textarea name="seo_description[ar]" class="form-control form-control-sm" rows="2" maxlength="500">{{ isset($video) ? $video->getTranslation('seo_description','ar',false) : '' }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label text-info small">SEO Description (إنجليزي)</label>
        <textarea name="seo_description[en]" class="form-control form-control-sm" rows="2" maxlength="500">{{ isset($video) ? $video->getTranslation('seo_description','en',false) : '' }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label text-success small">SEO Keywords (عربي) — مفصول بفاصلة</label>
        <input type="text" name="seo_keywords[ar]" class="form-control form-control-sm"
               value="{{ isset($video) ? $video->getTranslation('seo_keywords','ar',false) : '' }}"
               placeholder="معاذ عليان, مقارنة الأديان, ...">
    </div>
    <div class="col-md-6">
        <label class="form-label text-info small">SEO Keywords (إنجليزي)</label>
        <input type="text" name="seo_keywords[en]" class="form-control form-control-sm"
               value="{{ isset($video) ? $video->getTranslation('seo_keywords','en',false) : '' }}">
    </div>
  </form>
</div>

<div class="modal-footer border-top">
  <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
    <i class="ti tabler-x me-1"></i> {{ __('Cancel') }}
  </button>
  <button type="button" class="btn btn-primary" id="btn-save-video">
    <i class="ti tabler-device-floppy me-1"></i>
    {{ isset($video) ? __('Save Changes') : __('Add Video') }}
  </button>
</div>

<!-- Load TinyMCE if not loaded -->
<script>
    (function() {
        const typeSelector = document.getElementById('v-type-selector');
        const urlRow = document.getElementById('v-url-row');
        const fileRow = document.getElementById('v-file-row');
        const urlInput = document.getElementById('v-video-url');
        const fileInput = document.getElementById('v-video-file');

        function updateVideoFields() {
            if (typeSelector.value === 'external') {
                urlRow.style.display = 'block';
                fileRow.style.display = 'none';
                urlInput.required = true;
                fileInput.required = false;
            } else {
                urlRow.style.display = 'none';
                fileRow.style.display = 'block';
                urlInput.required = false;
                // Only require file if it's not already uploaded
                @if(isset($video) && $video->video_type == 'upload' && $video->video_url)
                    fileInput.required = false;
                @else
                    fileInput.required = true;
                @endif
            }
        }

        typeSelector.addEventListener('change', updateVideoFields);
        updateVideoFields(); // run on load

        let quillAr, quillEn;
        
        function initEditors() {
            if (typeof Quill !== 'undefined') {
                const toolbarOptions = [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ];

                const arContainer = document.querySelector('#quill-editor-ar');
                if(arContainer && !arContainer.classList.contains('ql-container')) {
                    quillAr = new Quill('#quill-editor-ar', {
                        theme: 'snow',
                        modules: { toolbar: toolbarOptions }
                    });
                    quillAr.format('direction', 'rtl');
                    quillAr.format('align', 'right');
                }

                const enContainer = document.querySelector('#quill-editor-en');
                if(enContainer && !enContainer.classList.contains('ql-container')) {
                    quillEn = new Quill('#quill-editor-en', {
                        theme: 'snow',
                        modules: { toolbar: toolbarOptions }
                    });
                }
            } else {
                console.warn('Quill is not loaded. Ensure assets are included in index.blade.php');
            }
        }

        setTimeout(initEditors, 100);

        // Repeater Logic
        let refIndex = {{ isset($video) ? $video->references->count() : 0 }};
        const refContainer = document.getElementById('references-container');
        
        document.getElementById('btn-add-reference').addEventListener('click', function() {
            const html = `
                <div class="reference-item card border shadow-none mb-3">
                    <div class="card-body position-relative p-3">
                        <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 btn-remove-reference"></button>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label text-success small">العنوان (AR)</label>
                                <input type="text" name="references[${refIndex}][title][ar]" class="form-control form-control-sm mb-2" placeholder="العنوان بالعربية">
                                <label class="form-label text-info small">العنوان (EN)</label>
                                <input type="text" name="references[${refIndex}][title][en]" class="form-control form-control-sm" placeholder="Title in English">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">النوع والمحتوى</label>
                                <select name="references[${refIndex}][type]" class="form-select form-select-sm mb-2 ref-type-selector">
                                    <option value="link">رابط خارجي</option>
                                    <option value="upload">رفع ملف</option>
                                    <option value="text">نص</option>
                                </select>
                                
                                <div class="ref-input-container">
                                    <input type="url" name="references[${refIndex}][content]" class="form-control form-control-sm ref-content-link" placeholder="https://youtube.com/shorts/...">
                                    
                                    <div class="ref-content-upload" style="display:none;">
                                        <input type="file" name="" class="form-control form-control-sm" accept="video/*,image/*">
                                    </div>

                                    <textarea name="" class="form-control form-control-sm ref-content-text" style="display:none;" rows="2" placeholder="النص المرجعي"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            refContainer.insertAdjacentHTML('beforeend', html);
            refIndex++;
        });

        refContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-reference')) {
                e.target.closest('.reference-item').remove();
            }
        });

        refContainer.addEventListener('change', function(e) {
            if (e.target.classList.contains('ref-type-selector')) {
                const type = e.target.value;
                const container = e.target.closest('.col-md-6').querySelector('.ref-input-container');
                const linkInput = container.querySelector('.ref-content-link');
                const uploadContainer = container.querySelector('.ref-content-upload');
                const fileInput = uploadContainer.querySelector('input[type="file"]');
                const textInput = container.querySelector('.ref-content-text');
                
                // Get the base name from the select input (e.g. references[0])
                const baseName = e.target.name.replace('[type]', '');

                // Reset names and hide all
                linkInput.name = ''; linkInput.style.display = 'none';
                fileInput.name = ''; uploadContainer.style.display = 'none';
                textInput.name = ''; textInput.style.display = 'none';

                if (type === 'link') {
                    linkInput.name = `${baseName}[content]`;
                    linkInput.style.display = 'block';
                } else if (type === 'upload') {
                    fileInput.name = `${baseName}[file]`;
                    uploadContainer.style.display = 'block';
                } else if (type === 'text') {
                    textInput.name = `${baseName}[content]`;
                    textInput.style.display = 'block';
                }
            }
        });

        document.getElementById('btn-save-video').addEventListener('click', function() {
            if (quillAr) {
                document.getElementById('v-desc-ar').value = quillAr.root.innerHTML;
            }
            if (quillEn) {
                document.getElementById('v-desc-en').value = quillEn.root.innerHTML;
            }

            const form = document.getElementById('video-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const btn = this;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جارٍ الحفظ...';

            const formData = new FormData(form);
            formData.append('_token', '{{ csrf_token() }}');
            if (form.dataset.method === 'PUT') {
                formData.append('_method', 'PUT');
            }
            
            if (!document.getElementById('v-is-published').checked) {
                formData.append('is_published', '0');
            }

            fetch(form.dataset.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Validation error');
                return data;
            })
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(btn.closest('.modal'));
                    if (modal) modal.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'نجاح',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({ icon: 'error', title: 'خطأ', text: error.message || 'حدث خطأ غير متوقع.' });
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });
    })();
</script>
