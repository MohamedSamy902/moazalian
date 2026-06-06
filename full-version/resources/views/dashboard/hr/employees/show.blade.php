@extends('layouts/layoutMaster')
@section('title', $employee['name'] . ' - ملف الموظف')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-user me-2"></i>ملف الموظف: <span class="text-primary">{{ $employee['name'] }}</span></h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}">الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <a href="{{ route('dashboard.hr.employees.index') }}">الموظفون</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>{{ $employee['name'] }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-label-warning"><i class="ti tabler-edit me-1"></i> تعديل</button>
        <a href="{{ route('dashboard.hr.employees.index') }}" class="btn btn-label-secondary"><i class="ti tabler-arrow-right me-1"></i> رجوع</a>
    </div>
</div>

<div class="row g-4">
    {{-- Profile card --}}
    <div class="col-lg-4">
        <div class="card glass-card border-0 text-center">
            <div class="card-body py-5">
                <div class="avatar avatar-xl mx-auto mb-3">
                    <span class="avatar-initial rounded-circle bg-label-{{ $employee['avatar_color'] }} fs-2 fw-bold">
                        {{ mb_substr($employee['name'],0,1) }}
                    </span>
                </div>
                <h4 class="mb-1 fw-bold">{{ $employee['name'] }}</h4>
                <p class="text-muted mb-2">{{ $employee['role'] }}</p>
                <div class="d-flex justify-content-center flex-wrap gap-1 mb-3">
                    @foreach($employee['departments'] as $dept)
                        <span class="badge bg-label-primary">{{ $dept }}</span>
                    @endforeach
                </div>
                @php $st = ['monthly'=>['primary','شهري'],'per_task'=>['warning','بالتاسك'],'freelance'=>['info','فريلانس']][$employee['salary_type']]; @endphp
                <span class="badge bg-label-{{ $st[0] }} mb-3">{{ $st[1] }}</span>
                <hr>
                <table class="table table-borderless text-start mb-0">
                    <tr><td class="text-muted small fw-semibold">الهاتف</td><td><a href="tel:{{ $employee['phone'] }}">{{ $employee['phone'] }}</a></td></tr>
                    <tr><td class="text-muted small fw-semibold">تاريخ التعيين</td><td>{{ $employee['hire_date'] }}</td></tr>
                    <tr><td class="text-muted small fw-semibold">الحالة</td><td><span class="badge bg-label-{{ $employee['status']==='active' ? 'success':'secondary' }}">{{ $employee['status']==='active'?'نشط':'غير نشط' }}</span></td></tr>
                    @if($employee['salary_type']==='monthly')
                    <tr><td class="text-muted small fw-semibold">الراتب الأساسي</td><td class="fw-bold text-primary">{{ number_format($employee['base_salary']) }} EGP</td></tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="card glass-card border-0 mt-4">
            <div class="card-body d-grid gap-2">
                <button class="btn btn-label-success" data-bs-toggle="modal" data-bs-target="#addBonusModal">
                    <i class="ti tabler-plus me-1"></i> إضافة مكافأة / خصم
                </button>
                <button class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    <i class="ti tabler-clipboard-plus me-1"></i> تسجيل تاسك جديد
                </button>
            </div>
        </div>
    </div>

    {{-- Right column --}}
    <div class="col-lg-8">
        {{-- Payroll history --}}
        <div class="card glass-card border-0 mb-4">
            <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0"><i class="ti tabler-cash me-2 text-warning"></i>سجل المرتبات</h5>
                <a href="{{ route('dashboard.hr.payroll') }}" class="btn btn-sm btn-label-warning">لوحة المرتبات</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover fixit-table border-top mb-0">
                    <thead><tr><th>الشهر</th><th>الراتب الأساسي</th><th>مكافآت</th><th>خصومات</th><th>الصافي</th><th>الحالة</th></tr></thead>
                    <tbody>
                        @forelse($payroll as $p)
                        <tr>
                            <td class="fw-semibold">{{ \Carbon\Carbon::createFromFormat('Y-m', $p['month'])->translatedFormat('F Y') }}</td>
                            <td>{{ number_format($p['basic_salary']) }}</td>
                            <td class="text-success">{{ $p['bonuses'] > 0 ? '+'.number_format($p['bonuses']) : '—' }}</td>
                            <td class="text-danger">{{ $p['deductions'] > 0 ? '-'.number_format($p['deductions']) : '—' }}</td>
                            <td class="fw-bold">{{ number_format($p['net_salary']) }} EGP</td>
                            <td><span class="badge {{ $p['status']==='paid'?'bg-label-success':'bg-label-warning' }}">{{ $p['status']==='paid'?'مصروف':'معلق' }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-3 text-muted">لا توجد سجلات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tasks (per_task/freelance) --}}
        @if(in_array($employee['salary_type'], ['per_task','freelance']))
        <div class="card glass-card border-0">
            <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0"><i class="ti tabler-checklist me-2 text-info"></i>التاسكات والمهام</h5>
                <span class="badge bg-label-info">{{ count($tasks) }} تاسك</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover fixit-table border-top mb-0">
                    <thead><tr><th>المهمة</th><th>المبلغ</th><th>التاريخ</th><th>الحالة</th></tr></thead>
                    <tbody>
                        @foreach($tasks as $task)
                        <tr>
                            <td class="fw-semibold">{{ $task['title'] }}</td>
                            <td class="fw-bold text-primary">{{ number_format($task['amount']) }} EGP</td>
                            <td class="text-muted small">{{ $task['date'] }}</td>
                            <td><span class="badge {{ $task['status']==='paid'?'bg-label-success':'bg-label-warning' }}">{{ $task['status']==='paid'?'مدفوع':'معلق' }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-active">
                        <tr>
                            <td class="fw-bold">الإجمالي</td>
                            <td class="fw-bold text-primary" colspan="3">{{ number_format(array_sum(array_column($tasks,'amount'))) }} EGP</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Add Bonus Modal --}}
<div class="modal fade" id="addBonusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title"><i class="ti tabler-plus me-2 text-success"></i>إضافة مكافأة / خصم</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">النوع</label>
                    <div class="d-flex gap-3">
                        <label class="contact-type-btn"><input type="radio" name="adj_type" value="bonus" checked><span><i class="ti tabler-plus me-1 text-success"></i>مكافأة</span></label>
                        <label class="contact-type-btn"><input type="radio" name="adj_type" value="deduction"><span><i class="ti tabler-minus me-1 text-danger"></i>خصم</span></label>
                    </div>
                </div>
                <div class="mb-3"><label class="form-label fw-semibold">المبلغ (EGP)</label><input type="number" class="form-control" placeholder="0"></div>
                <div class="mb-3"><label class="form-label fw-semibold">الشهر</label><input type="month" class="form-control" value="{{ date('Y-m') }}"></div>
                <div class="mb-0"><label class="form-label fw-semibold">السبب</label><textarea class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer border-top">
                <button class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button class="btn btn-success"><i class="ti tabler-device-floppy me-1"></i> حفظ</button>
            </div>
        </div>
    </div>
</div>

{{-- Add Task Modal --}}
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title"><i class="ti tabler-clipboard-plus me-2 text-primary"></i>تسجيل تاسك جديد</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold">اسم المهمة</label><input type="text" class="form-control" placeholder="مثال: تصميم بوست سوشيال"></div>
                <div class="mb-3"><label class="form-label fw-semibold">المبلغ (EGP)</label><input type="number" class="form-control" placeholder="0"></div>
                <div class="mb-3"><label class="form-label fw-semibold">التاريخ</label><input type="date" class="form-control" value="{{ date('Y-m-d') }}"></div>
                <div class="mb-0"><label class="form-label fw-semibold">ملاحظة</label><textarea class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer border-top">
                <button class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button class="btn btn-primary"><i class="ti tabler-device-floppy me-1"></i> حفظ</button>
            </div>
        </div>
    </div>
</div>

<style>
.contact-type-btn input { display: none; }
.contact-type-btn span { display: inline-flex; align-items: center; padding: 6px 16px; border-radius: 20px; border: 1.5px solid #d9d9d9; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s; color: #6e6b7b; }
.contact-type-btn input:checked + span { border-color: #7367f0; background: rgba(115,103,240,0.1); color: #7367f0; }
</style>
@endsection
