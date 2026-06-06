<div class="modal-header border-bottom">
  <h5 class="modal-title">
    <i class="ti {{ isset($quickResponse) ? 'tabler-edit' : 'tabler-plus' }} me-2 text-primary"></i>
    {{ isset($quickResponse) ? __('Edit Quick Response') : __('Add Quick Response') }}
  </h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
  <form id="quick-response-form" class="row g-3" data-action="{{ isset($quickResponse) ? route('admin.quick-responses.update', $quickResponse) : route('admin.quick-responses.store') }}" data-method="{{ isset($quickResponse) ? 'PUT' : 'POST' }}">
    
    <div class="col-md-6">
        <label class="form-label text-success">{{ __('Title (AR)') }}</label>
        <input type="text" name="title[ar]" id="qr-title-ar" class="form-control" value="{{ isset($quickResponse) ? $quickResponse->getTranslation('title', 'ar', false) : '' }}">
    </div>
    
    <div class="col-md-6">
        <label class="form-label text-info">{{ __('Title (EN)') }}</label>
        <input type="text" name="title[en]" id="qr-title-en" class="form-control" value="{{ isset($quickResponse) ? $quickResponse->getTranslation('title', 'en', false) : '' }}">
    </div>

    <div class="col-12">
        <label class="form-label fw-bold">{{ __('Type') }} <span class="text-danger">*</span></label>
        <select name="type" id="qr-type" class="form-select" required>
            <option value="text" {{ (isset($quickResponse) && $quickResponse->type == 'text') ? 'selected' : '' }}>{{ __('Text') }}</option>
            <option value="video" {{ (isset($quickResponse) && $quickResponse->type == 'video') ? 'selected' : '' }}>{{ __('Video') }}</option>
            <option value="image" {{ (isset($quickResponse) && $quickResponse->type == 'image') ? 'selected' : '' }}>{{ __('Image') }}</option>
        </select>
    </div>

    <div class="col-12 mt-4 mb-2" id="qr-content-header-row">
        <hr>
        <h6 class="fw-bold mb-1">{{ __('Content') }}</h6>
        <p class="text-muted small">يمكنك كتابة النص وإدراج الصور والفيديوهات مباشرة داخل المحرر</p>
    </div>

    <div class="col-md-6" id="qr-content-ar-row">
        <label class="form-label text-success">{{ __('Content (AR)') }}</label>
        <textarea name="content_text[ar]" id="qr-content-ar" class="d-none">{{ isset($quickResponse) ? $quickResponse->getTranslation('content', 'ar', false) : '' }}</textarea>
        <div class="form-control p-0 border-0">
            <div id="quill-editor-ar" style="height: 300px;">
                {!! isset($quickResponse) ? $quickResponse->getTranslation('content', 'ar', false) : '' !!}
            </div>
        </div>
    </div>
    
    <div class="col-md-6" id="qr-content-en-row">
        <label class="form-label text-info">{{ __('Content (EN)') }}</label>
        <textarea name="content_text[en]" id="qr-content-en" class="d-none">{{ isset($quickResponse) ? $quickResponse->getTranslation('content', 'en', false) : '' }}</textarea>
        <div class="form-control p-0 border-0">
            <div id="quill-editor-en" style="height: 300px;">
                {!! isset($quickResponse) ? $quickResponse->getTranslation('content', 'en', false) : '' !!}
            </div>
        </div>
    </div>

    <div class="col-12" id="qr-attachment-file-row" style="display:none;">
        <label class="form-label fw-bold">{{ __('Image Attachment') }}</label>
        @if(isset($quickResponse) && $quickResponse->type == 'image' && $quickResponse->attachment)
            <div class="mb-2">
                <img src="{{ asset($quickResponse->attachment) }}" width="100" class="rounded border">
            </div>
        @endif
        <input type="file" name="image" id="qr-attachment" class="form-control" accept="image/*">
    </div>

    <div class="col-12" id="qr-attachment-url-row" style="display:none;">
        <label class="form-label fw-bold">{{ __('YouTube URL') }}</label>
        <input type="url" name="youtube_url" id="qr-youtube" class="form-control" value="{{ (isset($quickResponse) && $quickResponse->type == 'video') ? $quickResponse->attachment : '' }}" placeholder="https://youtube.com/...">
    </div>

    <div class="col-12 mt-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="qr-is-published" name="is_published" value="1" {{ (!isset($quickResponse) || $quickResponse->is_published) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold" for="qr-is-published">{{ __('Published') }}</label>
        </div>
    </div>
  </form>
</div>

<div class="modal-footer border-top">
  <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
    <i class="ti tabler-x me-1"></i> {{ __('Cancel') }}
  </button>
  <button type="button" class="btn btn-primary" id="btn-save-qr">
    <i class="ti tabler-device-floppy me-1"></i>
    {{ isset($quickResponse) ? __('Save Changes') : __('Add Quick Response') }}
  </button>
</div>

<script>
    (function() {
        const typeSelector = document.getElementById('qr-type');
        const fileRow = document.getElementById('qr-attachment-file-row');
        const urlRow = document.getElementById('qr-attachment-url-row');
        const contentHeaderRow = document.getElementById('qr-content-header-row');
        const contentArRow = document.getElementById('qr-content-ar-row');
        const contentEnRow = document.getElementById('qr-content-en-row');

        function updateFields() {
            if (typeSelector.value === 'text') {
                fileRow.style.display = 'none';
                urlRow.style.display = 'none';
                contentHeaderRow.style.display = 'block';
                contentArRow.style.display = 'block';
                contentEnRow.style.display = 'block';
            } else if (typeSelector.value === 'image') {
                fileRow.style.display = 'block';
                urlRow.style.display = 'none';
                contentHeaderRow.style.display = 'none';
                contentArRow.style.display = 'none';
                contentEnRow.style.display = 'none';
            } else if (typeSelector.value === 'video') {
                fileRow.style.display = 'none';
                urlRow.style.display = 'block';
                contentHeaderRow.style.display = 'none';
                contentArRow.style.display = 'none';
                contentEnRow.style.display = 'none';
            }
        }

        typeSelector.addEventListener('change', updateFields);
        updateFields(); // run on load

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

        document.getElementById('btn-save-qr').addEventListener('click', function() {
            if (quillAr) {
                document.getElementById('qr-content-ar').value = quillAr.root.innerHTML;
            }
            if (quillEn) {
                document.getElementById('qr-content-en').value = quillEn.root.innerHTML;
            }

            const form = document.getElementById('quick-response-form');
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
            
            // Handle unchecked checkbox because FormData ignores them
            if (!document.getElementById('qr-is-published').checked) {
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
