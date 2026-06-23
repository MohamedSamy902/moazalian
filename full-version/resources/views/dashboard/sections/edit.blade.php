@extends('layouts.layoutMaster')

@section('title', 'تعديل - ' . $name_ar)

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = document.querySelectorAll('.account-file-input');
        fileInputs.forEach(input => {
            input.addEventListener('change', function(e) {
                if (e.target.files[0]) {
                    const img = document.getElementById('preview-' + e.target.id);
                    if (img) {
                        img.src = window.URL.createObjectURL(e.target.files[0]);
                    }
                }
            });
        });

        const resetBtns = document.querySelectorAll('.account-image-reset');
        resetBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const img = document.getElementById('preview-' + targetId);
                const originalSrc = img.getAttribute('data-original-src');
                
                input.value = '';
                img.src = originalSrc;
            });
        });
    });
</script>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">{{ __('Interface Settings') }} / {{ __('Sections') }} /</span> {{ $name_ar }}
        </h4>
        <a href="{{ route('admin.sections.index') }}" class="btn btn-secondary">
            <i class="icon-base ti tabler-arrow-right me-1"></i> {{ __('Back') }}
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.sections.update', ['page' => $page, 'section_name' => $section_name]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @foreach($keys as $keyModel)
                    <div class="mb-4 pb-4 border-bottom">
                        <label class="form-label text-primary fw-bold fs-5 mb-3"><i class="icon-base ti tabler-point text-primary"></i> {{ __(ucwords(str_replace('_', ' ', $keyModel->key))) }}</label>
                        
                        @if($keyModel->type === 'image' || $keyModel->type === 'url')
                            @if($keyModel->type === 'image')
                                <div class="d-flex align-items-start align-items-sm-center gap-6">
                                    <img id="preview-{{ $keyModel->key }}" src="{{ $keyModel->value ? asset($keyModel->value) : 'https://via.placeholder.com/100?text=No+Image' }}" data-original-src="{{ $keyModel->value ? asset($keyModel->value) : 'https://via.placeholder.com/100?text=No+Image' }}" alt="Image" class="d-block w-px-100 h-px-100 rounded" style="object-fit: cover;">
                                    <div class="button-wrapper">
                                        <label for="{{ $keyModel->key }}" class="btn btn-primary me-3 mb-4" tabindex="0">
                                            <span class="d-none d-sm-block">{{ __('Upload new photo') }}</span>
                                            <i class="icon-base ti tabler-upload d-block d-sm-none"></i>
                                            <input type="file" id="{{ $keyModel->key }}" name="{{ $keyModel->key }}" class="account-file-input" hidden accept="image/png, image/jpeg, image/webp" />
                                        </label>
                                        <button type="button" class="btn btn-label-secondary account-image-reset mb-4" data-target="{{ $keyModel->key }}">
                                            <i class="icon-base ti tabler-refresh d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">{{ __('Reset') }}</span>
                                        </button>
                                        <div class="text-muted">{{ __('Allowed: JPG, GIF, PNG or WEBP.') }}</div>
                                    </div>
                                </div>
                            @else
                                <input type="url" name="{{ $keyModel->key }}" class="form-control" value="{{ $keyModel->value }}">
                            @endif
                        @else
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-success">AR</label>
                                    @if($keyModel->type === 'textarea')
                                        <textarea name="{{ $keyModel->key }}_ar" class="form-control" rows="3">{{ $keyModel->getTranslation('value', 'ar') }}</textarea>
                                    @else
                                        <input type="text" name="{{ $keyModel->key }}_ar" class="form-control" value="{{ $keyModel->getTranslation('value', 'ar') }}">
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-info">EN</label>
                                    @if($keyModel->type === 'textarea')
                                        <textarea name="{{ $keyModel->key }}_en" class="form-control" rows="3">{{ $keyModel->getTranslation('value', 'en') }}</textarea>
                                    @else
                                        <input type="text" name="{{ $keyModel->key }}_en" class="form-control" value="{{ $keyModel->getTranslation('value', 'en') }}">
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-base ti tabler-device-floppy me-1"></i> {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
