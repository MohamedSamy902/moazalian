@php
    $avatarColors = ['primary','success','info','warning','danger','secondary'];
    $color = $avatarColors[$employee->id % count($avatarColors)];
    $salaryTypeLabel = ['monthly'=>'راتب شهري','per_task'=>'بالمهمة','freelance'=>'فريلانس'][$employee->salary_type] ?? $employee->salary_type;
    $salaryTypeColor = ['monthly'=>'primary','per_task'=>'warning','freelance'=>'info'][$employee->salary_type] ?? 'secondary';
    $statusColor = $employee->status === 'active' ? 'success' : 'secondary';
    $statusLabel = $employee->status === 'active' ? 'نشط' : 'غير نشط';
@endphp
<div class="col-xl-4 col-md-6" id="emp-card-{{ $employee->id }}">
    <div class="card glass-card border-0 employee-card h-100">
        <div class="card-body text-center pt-4 pb-3">
            <div class="avatar avatar-xl mx-auto mb-3">
                @if($employee->avatar)
                    <img src="{{ asset('storage/' . $employee->avatar) }}" class="rounded-circle" style="width:64px;height:64px;object-fit:cover">
                @else
                    <span class="avatar-initial rounded-circle bg-label-{{ $color }} fs-3 fw-bold">
                        {{ mb_substr($employee->name, 0, 1) }}
                    </span>
                @endif
            </div>
            <h5 class="mb-1 fw-bold">{{ $employee->name }}</h5>
            <p class="text-muted small mb-2">{{ $employee->job_title }}</p>
            <div class="d-flex justify-content-center gap-1 flex-wrap mb-3">
                @foreach($employee->departments as $dept)
                    <span class="badge bg-label-secondary small">{{ $dept->name }}</span>
                @endforeach
            </div>
            <div class="d-flex justify-content-center gap-2 mb-3">
                <span class="badge bg-label-{{ $salaryTypeColor }}">{{ $salaryTypeLabel }}</span>
                <span class="badge bg-label-{{ $statusColor }}">{{ $statusLabel }}</span>
            </div>
            @if($employee->salary_type === 'monthly')
            <div class="salary-display mb-3">
                <span class="fs-5 fw-bold text-primary">{{ number_format($employee->base_salary) }}</span>
                <span class="text-muted small"> / شهر</span>
            </div>
            @else
            <div class="salary-display mb-3 text-muted small">راتب متغير</div>
            @endif
        </div>
        <div class="card-footer border-top d-flex gap-2 justify-content-center py-2">
            <a href="{{ route('dashboard.hr.employees.show', $employee->id) }}" class="btn btn-sm btn-label-primary flex-fill">
                <i class="ti tabler-eye me-1"></i> الملف
            </a>
            <button class="btn btn-sm btn-label-warning flex-fill btn-edit-emp" data-id="{{ $employee->id }}">
                <i class="ti tabler-edit me-1"></i> تعديل
            </button>
            <button class="btn btn-sm btn-icon btn-label-danger btn-delete-emp"
                    data-id="{{ $employee->id }}"
                    data-name="{{ $employee->name }}" title="حذف">
                <i class="ti tabler-trash"></i>
            </button>
        </div>
    </div>
</div>
