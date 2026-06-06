@extends('layouts/layoutMaster')
@section('title', 'المرتبات - Fix-It')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-cash me-2"></i>إدارة المرتبات</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}">الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <a href="{{ route('dashboard.hr.overview') }}">الموارد البشرية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>المرتبات</span>
        </div>
    </div>
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="fw-semibold small mb-0">الشهر:</label>
        <input type="month" name="month" class="form-control form-control-sm" value="{{ $month }}" onchange="this.form.submit()">
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card glass-card border-0"><div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon gradient-primary flex-shrink-0"><i class="ti tabler-calculator text-white ti-md"></i></div>
        <div><div class="card-label">الإجمالي</div><div class="card-value">{{ number_format($stats['total_net']) }} <small>ر.س</small></div></div>
    </div></div></div>
    <div class="col-md-4"><div class="card glass-card border-0"><div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon gradient-success flex-shrink-0"><i class="ti tabler-circle-check text-white ti-md"></i></div>
        <div><div class="card-label">مصروف ({{ $stats['paid_count'] }})</div><div class="card-value text-success">{{ number_format($stats['total_paid']) }} <small>ر.س</small></div></div>
    </div></div></div>
    <div class="col-md-4"><div class="card glass-card border-0"><div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon gradient-warning flex-shrink-0"><i class="ti tabler-clock text-white ti-md"></i></div>
        <div><div class="card-label">معلق ({{ $stats['pending_count'] }})</div><div class="card-value text-warning">{{ number_format($stats['total_pending']) }} <small>ر.س</small></div></div>
    </div></div></div>
</div>

<div class="card glass-card overflow-hidden">
    <div class="card-header border-bottom">
        <h5 class="card-title mb-0"><i class="ti tabler-table me-2 text-primary"></i>مرتبات {{ $month }}</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover fixit-table border-top mb-0">
            <thead>
                <tr>
                    <th>الموظف</th>
                    <th>المسمى</th>
                    <th>الأساسي</th>
                    <th>مكافآت</th>
                    <th>خصومات</th>
                    <th>الصافي</th>
                    <th>الحالة</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                <tr data-payroll-id="{{ $r->id }}">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm">
                                <span class="avatar-initial rounded-circle bg-label-primary">{{ mb_substr($r->employee?->name ?? '؟', 0, 1) }}</span>
                            </div>
                            @if($r->employee)
                            <a href="{{ route('dashboard.hr.employees.show', $r->employee->id) }}" class="fw-semibold">
                                {{ $r->employee->name }}
                            </a>
                            @else
                                <span class="fw-semibold text-muted">موظف محذوف</span>
                            @endif
                        </div>
                    </td>
                    <td class="small text-muted">{{ $r->employee?->job_title ?? '—' }}</td>
                    <td>{{ number_format($r->basic_salary) }}</td>
                    <td class="text-success">{{ $r->bonuses > 0 ? '+'.number_format($r->bonuses) : '—' }}</td>
                    <td class="text-danger">{{ $r->deductions > 0 ? '-'.number_format($r->deductions) : '—' }}</td>
                    <td class="fw-bold">{{ number_format($r->net_salary) }} <small class="text-muted">ر.س</small></td>
                    <td>
                        <span class="badge {{ $r->status === 'paid' ? 'bg-label-success' : 'bg-label-warning' }}">
                            {{ $r->status === 'paid' ? '✅ مصروف' : '⏳ معلق' }}
                        </span>
                    </td>
                    <td>
                        @if($r->status === 'pending')
                        <button class="btn btn-sm btn-success btn-pay"
                                data-id="{{ $r->id }}"
                                data-name="{{ $r->employee?->name }}"
                                data-amount="{{ number_format($r->net_salary) }}">
                            <i class="ti tabler-cash me-1"></i> صرف
                        </button>
                        @else
                        <button class="btn btn-sm btn-label-secondary" disabled>
                            <i class="ti tabler-check"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="ti tabler-cash-off" style="font-size:2.5rem;color:#c4c4ff"></i>
                        <p class="text-muted mt-2">لا توجد سجلات مرتبات لهذا الشهر</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($records->isNotEmpty())
            <tfoot class="table-active fw-bold">
                <tr>
                    <td colspan="2">الإجمالي</td>
                    <td>{{ number_format($records->sum('basic_salary')) }}</td>
                    <td class="text-success">+{{ number_format($records->sum('bonuses')) }}</td>
                    <td class="text-danger">-{{ number_format($records->sum('deductions')) }}</td>
                    <td colspan="3" class="fw-bold">{{ number_format($records->sum('net_salary')) }} ر.س</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    <div class="card-footer py-2">
        {{ $records->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection

@section('page-script')
<script>
$(document).ready(function () {
    $(document).on('click', '.btn-pay', function () {
        const name   = $(this).data('name');
        const amount = $(this).data('amount');
        Swal.fire({
            title: 'تأكيد صرف الراتب؟',
            html: `الموظف: <strong>${name}</strong><br>المبلغ: <strong>${amount} ر.س</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'نعم، صرف',
            cancelButtonText: 'إلغاء',
            customClass: { confirmButton: 'btn btn-success ms-1', cancelButton: 'btn btn-label-secondary' },
            buttonsStyling: false
        }).then(r => {
            if (r.isConfirmed) {
                Swal.fire({ icon: 'success', title: 'تم الصرف!', timer: 2000, showConfirmButton: false });
            }
        });
    });
});
</script>
@endsection
