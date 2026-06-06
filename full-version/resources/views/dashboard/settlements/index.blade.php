@extends('layouts/layoutMaster')
@section('title', 'التسويات المالية - Fix-It')

@section('content')
{{-- ═══════════════════════════════════════════════════════
     PAGE HEADER
════════════════════════════════════════════════════════ --}}
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-transfer-in"></i> التسويات المالية</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>التسويات</span>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     STAT CARDS
════════════════════════════════════════════════════════ --}}
<div class="row g-4 mb-4">
    {{-- Pending --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card glass-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon gradient-warning flex-shrink-0">
                        <i class="ti tabler-hourglass text-white ti-md"></i>
                    </div>
                    <div>
                        <div class="card-label">إجمالي المعلق</div>
                        <div class="card-value text-warning">{{ number_format($stats['total_pending']) }} <small>EGP</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Completed --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card glass-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon gradient-success flex-shrink-0">
                        <i class="ti tabler-circle-check text-white ti-md"></i>
                    </div>
                    <div>
                        <div class="card-label">إجمالي المكتمل</div>
                        <div class="card-value text-success">{{ number_format($stats['total_completed']) }} <small>EGP</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Open count --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card glass-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon gradient-primary flex-shrink-0">
                        <i class="ti tabler-clock text-white ti-md"></i>
                    </div>
                    <div>
                        <div class="card-label">تسويات مفتوحة</div>
                        <div class="card-value">{{ $stats['open_count'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Completed count --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card glass-card border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon gradient-info flex-shrink-0">
                        <i class="ti tabler-check text-white ti-md"></i>
                    </div>
                    <div>
                        <div class="card-label">تسويات مكتملة</div>
                        <div class="card-value">{{ $stats['completed_count'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     FILTER BAR
════════════════════════════════════════════════════════ --}}
<div class="card glass-card border-0 mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('dashboard.settlements.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold">بحث بالمركز أو رقم الحوالة</label>
                <input type="text" name="q" class="form-control" placeholder="بحث..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">الحالة</label>
                <select name="status" class="form-select">
                    <option value="">الكل</option>
                    <option value="open"         {{ request('status') === 'open'         ? 'selected' : '' }}>مفتوحة</option>
                    <option value="partial_paid" {{ request('status') === 'partial_paid' ? 'selected' : '' }}>مدفوع جزئياً</option>
                    <option value="completed"    {{ request('status') === 'completed'    ? 'selected' : '' }}>مكتملة</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">الاتجاه</label>
                <select name="direction" class="form-select">
                    <option value="">الكل</option>
                    <option value="center_pays_platform"  {{ request('direction') === 'center_pays_platform'  ? 'selected' : '' }}>المركز يدفع للمنصة</option>
                    <option value="platform_pays_center"  {{ request('direction') === 'platform_pays_center'  ? 'selected' : '' }}>المنصة تدفع للمركز</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="ti tabler-filter me-1"></i> تصفية
                </button>
                <a href="{{ route('dashboard.settlements.index') }}" class="btn btn-label-secondary" title="إعادة تعيين">
                    <i class="ti tabler-refresh"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     SETTLEMENTS TABLE
════════════════════════════════════════════════════════ --}}
<div class="card glass-card overflow-hidden">
    <div class="card-header border-bottom d-flex align-items-center">
        <h5 class="card-title mb-0"><i class="ti tabler-list me-2 text-primary"></i>قائمة التسويات</h5>
        <span class="badge bg-label-primary ms-2">{{ count($settlements) }} تسوية</span>
    </div>
    <div class="card-datatable table-responsive">
        <table class="table table-hover fixit-table border-top mb-0">
            <thead>
                <tr>
                    <th>رقم الحوالة</th>
                    <th>المركز</th>
                    <th>نوع الدورة</th>
                    <th>الاتجاه</th>
                    <th>المستحق</th>
                    <th>المسدد</th>
                    <th>التقدم</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($settlements as $s)
                <tr>
                    {{-- Reference --}}
                    <td>
                        <span class="fw-bold text-primary font-monospace">{{ $s['reference_number'] }}</span>
                    </td>

                    {{-- Center --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm">
                                <span class="avatar-initial rounded-circle bg-label-info fw-bold">
                                    {{ mb_substr($s['center_name'], 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $s['center_name'] }}</div>
                                <div class="small text-muted">{{ $s['orders_count'] }} أوردر</div>
                            </div>
                        </div>
                    </td>

                    {{-- Period type --}}
                    <td>
                        @php
                            $typeLabel = ['weekly' => 'أسبوعي', 'biweekly' => 'أسبوعين', 'monthly' => 'شهري'][$s['period_type']] ?? $s['period_type'];
                        @endphp
                        <span class="badge bg-label-secondary">{{ $typeLabel }}</span>
                    </td>

                    {{-- Direction --}}
                    <td>
                        @if($s['direction'] === 'center_pays_platform')
                            <span class="settlement-direction center-pays">
                                <i class="ti tabler-arrow-up-right me-1"></i>المركز يدفع للمنصة
                            </span>
                        @else
                            <span class="settlement-direction platform-pays">
                                <i class="ti tabler-arrow-down-left me-1"></i>المنصة تدفع للمركز
                            </span>
                        @endif
                    </td>

                    {{-- Net due --}}
                    <td>
                        <span class="fw-bold">{{ number_format($s['net_due']) }} EGP</span>
                    </td>

                    {{-- Total paid --}}
                    <td>
                        <span class="text-success fw-semibold">{{ number_format($s['total_paid']) }} EGP</span>
                        @if($s['balance_remaining'] > 0)
                            <div class="small text-danger">متبقي: {{ number_format($s['balance_remaining']) }}</div>
                        @endif
                    </td>

                    {{-- Progress bar --}}
                    <td style="min-width:120px">
                        <div class="d-flex align-items-center gap-2">
                            <div class="flex-grow-1">
                                <div class="progress" style="height:6px; border-radius:4px;">
                                    <div class="progress-bar
                                        {{ $s['payment_progress'] >= 100 ? 'bg-success' : ($s['payment_progress'] > 0 ? 'bg-warning' : 'bg-secondary') }}"
                                        role="progressbar"
                                        style="width: {{ $s['payment_progress'] }}%">
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted fw-semibold">{{ $s['payment_progress'] }}%</small>
                        </div>
                    </td>

                    {{-- Status --}}
                    <td>
                        @php
                            $badgeMap = [
                                'open'         => ['bg-label-warning',  'مفتوحة'],
                                'partial_paid' => ['bg-label-orange',   'جزئي'],
                                'completed'    => ['bg-label-success',  'مكتملة'],
                            ];
                            [$badgeClass, $badgeLabel] = $badgeMap[$s['status']] ?? ['bg-label-secondary', $s['status']];
                        @endphp
                        <span class="badge {{ $badgeClass }} status-badge">{{ $badgeLabel }}</span>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('dashboard.settlements.show', $s['id']) }}"
                               class="btn btn-sm btn-icon btn-label-primary"
                               title="عرض التفاصيل">
                                <i class="ti tabler-eye"></i>
                            </a>

                            @if($s['status'] !== 'completed')
                                <button class="btn btn-sm btn-icon btn-label-warning btn-early-close"
                                        data-id="{{ $s['id'] }}"
                                        data-ref="{{ $s['reference_number'] }}"
                                        title="إغلاق مبكر">
                                    <i class="ti tabler-lock"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="ti tabler-inbox ti-lg mb-2 d-block"></i>
                        لا توجد تسويات
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     INLINE STYLES
════════════════════════════════════════════════════════ --}}
<style>
.settlement-direction {
    display: inline-flex;
    align-items: center;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
}
.settlement-direction.center-pays {
    background: rgba(255, 75, 75, 0.1);
    color: #ff4b4b;
    border: 1px solid rgba(255, 75, 75, 0.25);
}
.settlement-direction.platform-pays {
    background: rgba(40, 199, 111, 0.1);
    color: #28c76f;
    border: 1px solid rgba(40, 199, 111, 0.25);
}
.bg-label-orange {
    background-color: rgba(255, 159, 67, 0.15) !important;
    color: #ff9f43 !important;
}
</style>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Early close ──────────────────────────────────────
    document.querySelectorAll('.btn-early-close').forEach(btn => {
        btn.addEventListener('click', function () {
            const id  = this.dataset.id;
            const ref = this.dataset.ref;

            Swal.fire({
                title: 'إغلاق مبكر للتسوية؟',
                html: `<p class="mb-1">رقم الحوالة: <strong>${ref}</strong></p>
                       <p class="text-muted small">سيتم إغلاق الدورة الحالية فوراً بدلاً من انتظار نهايتها الطبيعية.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="ti tabler-lock me-1"></i> نعم، أغلق الآن',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#ff9f43',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/dashboard/settlements/${id}/early-close`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({ icon: 'success', title: 'تم الإغلاق', text: data.message, timer: 2000, showConfirmButton: false })
                                .then(() => location.reload());
                        }
                    });
                }
            });
        });
    });

});
</script>
@endsection
