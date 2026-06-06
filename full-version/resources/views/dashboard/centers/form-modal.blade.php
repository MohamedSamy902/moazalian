@php
    $isEdit = isset($center);
    $actionUrl = $isEdit ? route('dashboard.centers.update', $center->id) : route('dashboard.centers.store');
@endphp
<div class="modal-header border-bottom">
    <h5 class="modal-title">
        <i class="ti tabler-building-store me-2 text-primary"></i>
        {{ $isEdit ? 'تعديل مركز صيانة' : 'إضافة مركز صيانة جديد' }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ $actionUrl }}" method="POST" id="centersForm" class="ajax-form">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif
    <div class="modal-body">
        <div class="row g-3">
            {{-- Logo --}}
            <div class="col-12 text-center mb-1">
                <div id="center-image-preview" class="mb-2 mx-auto d-flex align-items-center justify-content-center bg-light rounded-circle shadow-sm" style="width: 100px; height: 100px; border: 2px dashed #ddd; overflow: hidden;">
                    @if($isEdit && $center->logo)
                        <img src="{{ $center->logo }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="ti tabler-camera text-muted" style="font-size:2rem"></i>
                    @endif
                </div>
                <label class="btn btn-sm btn-label-primary">
                    <i class="ti tabler-upload me-1"></i> رفع صورة المركز
                    <input type="file" name="logo_file" class="image-preview-input d-none" data-preview="center-image-preview" accept="image/*">
                </label>
            </div>

            {{-- Center Info --}}
            <div class="col-12"><small class="text-muted fw-semibold text-uppercase">معلومات المركز</small><hr class="mt-1 mb-2"></div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">اسم المركز (عربي) <span class="text-danger">*</span></label>
                <input type="text" name="name[ar]" value="{{ $isEdit ? $center->getTranslation('name', 'ar') : '' }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">اسم المركز (إنجليزي) <span class="text-danger">*</span></label>
                <input type="text" name="name[en]" value="{{ $isEdit ? $center->getTranslation('name', 'en') : '' }}" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">العنوان (عربي)</label>
                <input type="text" name="address[ar]" value="{{ $isEdit ? $center->getTranslation('address', 'ar', false) : '' }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">العنوان (إنجليزي)</label>
                <input type="text" name="address[en]" value="{{ $isEdit ? $center->getTranslation('address', 'en', false) : '' }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">هاتف المركز</label>
                <input type="text" name="phone" value="{{ $isEdit ? $center->phone : '' }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">البريد الإلكتروني للمركز</label>
                <input type="email" name="email" value="{{ $isEdit ? $center->email : '' }}" class="form-control">
            </div>

            {{-- Owner Info --}}
            <div class="col-12 mt-3"><small class="text-muted fw-semibold text-uppercase">بيانات المالك (مدير النظام)</small><hr class="mt-1 mb-2"></div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">اسم المالك <span class="text-danger">*</span></label>
                <input type="text" name="owner_name" value="{{ $isEdit ? $center->owner_name : '' }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">هاتف المالك <span class="text-danger">*</span></label>
                <input type="text" name="owner_phone" value="{{ $isEdit ? $center->owner_phone : '' }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">بريد المالك الإلكتروني <span class="text-danger">*</span></label>
                <input type="email" name="owner_email" value="{{ $isEdit ? $center->owner_email : '' }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">كلمة المرور {{ $isEdit ? '(اتركه فارغاً لعدم التغيير)' : '<span class="text-danger">*</span>' }}</label>
                <input type="password" name="password" class="form-control" {{ $isEdit ? '' : 'required' }}>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" class="form-control" {{ $isEdit ? '' : 'required' }}>
            </div>

            {{-- Branches --}}
            <div class="col-12 mt-3 d-flex justify-content-between align-items-center">
                <small class="text-muted fw-semibold text-uppercase">الفروع</small>
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-branch-btn"><i class="ti tabler-plus"></i> إضافة فرع</button>
            </div>
            <div class="col-12"><hr class="mt-0 mb-2"></div>

            <div class="col-12" id="branches-container">
                @if($isEdit && $center->branches->count() > 0)
                    @foreach($center->branches as $bIndex => $branch)
                        <div class="card bg-lighter mb-3 branch-item" data-index="{{ $bIndex }}">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="mb-0">فرع #{{ $bIndex + 1 }}</h6>
                                    @if($bIndex > 0)
                                    <button type="button" class="btn btn-sm btn-icon btn-text-danger remove-branch"><i class="ti tabler-trash"></i></button>
                                    @endif
                                </div>
                                <input type="hidden" name="branches[{{ $bIndex }}][id]" value="{{ $branch->id }}">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" name="branches[{{ $bIndex }}][name][ar]" value="{{ $branch->getTranslation('name', 'ar') }}" class="form-control form-control-sm" placeholder="اسم الفرع (عربي)" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="branches[{{ $bIndex }}][name][en]" value="{{ $branch->getTranslation('name', 'en') }}" class="form-control form-control-sm" placeholder="اسم الفرع (إنجليزي)" required>
                                    </div>
                                    <div class="col-12">
                                        <div class="phones-container" data-bindex="{{ $bIndex }}">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="fw-bold text-muted">الهواتف:</small>
                                                <button type="button" class="btn btn-xs btn-label-success add-phone-btn"><i class="ti tabler-plus"></i> هاتف</button>
                                            </div>
                                            @foreach($branch->phones as $pIndex => $phone)
                                            <div class="input-group input-group-sm mb-1 phone-item">
                                                <input type="hidden" name="branches[{{ $bIndex }}][phones][{{ $pIndex }}][id]" value="{{ $phone->id }}">
                                                <input type="text" name="branches[{{ $bIndex }}][phones][{{ $pIndex }}][phone]" value="{{ $phone->phone }}" class="form-control" placeholder="رقم الهاتف" required>
                                                @if($pIndex > 0)
                                                <button class="btn btn-outline-danger remove-phone" type="button"><i class="ti tabler-x"></i></button>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card bg-lighter mb-3 branch-item" data-index="0">
                        <div class="card-body p-3">
                            <h6 class="mb-2">الفرع الرئيسي</h6>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" name="branches[0][name][ar]" class="form-control form-control-sm" placeholder="اسم الفرع (عربي)" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="branches[0][name][en]" class="form-control form-control-sm" placeholder="اسم الفرع (إنجليزي)" required>
                                </div>
                                <div class="col-12">
                                    <div class="phones-container" data-bindex="0">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="fw-bold text-muted">الهواتف:</small>
                                            <button type="button" class="btn btn-xs btn-label-success add-phone-btn"><i class="ti tabler-plus"></i> هاتف</button>
                                        </div>
                                        <div class="input-group input-group-sm mb-1 phone-item">
                                            <input type="text" name="branches[0][phones][0][phone]" class="form-control" placeholder="رقم الهاتف" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
    <div class="modal-footer border-top">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary" id="save-btn"><i class="ti tabler-device-floppy me-1"></i> حفظ المركز</button>
    </div>
</form>

<script>
$(document).ready(function() {
    let branchIndex = $('.branch-item').length;

    $('#add-branch-btn').click(function() {
        const html = `
            <div class="card bg-lighter mb-3 branch-item" data-index="${branchIndex}">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="mb-0">فرع إضافي</h6>
                        <button type="button" class="btn btn-sm btn-icon btn-text-danger remove-branch"><i class="ti tabler-trash"></i></button>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="text" name="branches[${branchIndex}][name][ar]" class="form-control form-control-sm" placeholder="اسم الفرع (عربي)" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="branches[${branchIndex}][name][en]" class="form-control form-control-sm" placeholder="اسم الفرع (إنجليزي)" required>
                        </div>
                        <div class="col-12">
                            <div class="phones-container" data-bindex="${branchIndex}">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="fw-bold text-muted">الهواتف:</small>
                                    <button type="button" class="btn btn-xs btn-label-success add-phone-btn"><i class="ti tabler-plus"></i> هاتف</button>
                                </div>
                                <div class="input-group input-group-sm mb-1 phone-item">
                                    <input type="text" name="branches[${branchIndex}][phones][0][phone]" class="form-control" placeholder="رقم الهاتف" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#branches-container').append(html);
        branchIndex++;
    });

    $(document).on('click', '.remove-branch', function() {
        $(this).closest('.branch-item').remove();
    });

    $(document).on('click', '.add-phone-btn', function() {
        const container = $(this).closest('.phones-container');
        const bIndex = container.data('bindex');
        const pIndex = container.find('.phone-item').length;
        
        const html = `
            <div class="input-group input-group-sm mb-1 phone-item">
                <input type="text" name="branches[${bIndex}][phones][${pIndex}][phone]" class="form-control" placeholder="رقم الهاتف" required>
                <button class="btn btn-outline-danger remove-phone" type="button"><i class="ti tabler-x"></i></button>
            </div>
        `;
        container.append(html);
    });

    $(document).on('click', '.remove-phone', function() {
        $(this).closest('.phone-item').remove();
    });

    $('#centersForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = $('#save-btn');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري الحفظ...');

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                if(response.success) {
                    $('#centersForm').closest('.modal').modal('hide');
                    $(document).trigger('modal-saved');
                    Swal.fire({
                        icon: 'success',
                        title: 'نجاح',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html('<i class="ti tabler-device-floppy me-1"></i> حفظ المركز');
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    for(let key in errors) {
                        errorMsg += errors[key][0] + '<br>';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ في البيانات',
                        html: errorMsg,
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'حدث خطأ غير متوقع',
                    });
                }
            }
        });
    });
});
</script>