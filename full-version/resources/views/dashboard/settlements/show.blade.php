@extends('layouts/layoutMaster')
@section('title', 'تفاصيل التسوية ' . $settlement['reference_number'] . ' - Fix-It')

@section('content')

{{-- ═══════════════════════════════════════════════════════
     PAGE HEADER
════════════════════════════════════════════════════════ --}}
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">
            <i class="ti tabler-file-invoice me-2"></i>
            تسوية: <span class="text-primary font-monospace">{{ $settlement['reference_number'] }}</span>
        </h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <a href="{{ route('dashboard.settlements.index') }}">التسويات</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>{{ $settlement['reference_number'] }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        @if($settlement['status'] !== 'completed')
            <button id="btn-add-payment" class="btn btn-success">
                <i class="ti tabler-plus me-1"></i> تسجيل دفعة
            </button>
            <button id="btn-mark-complete" class="btn btn-primary">
                <i class="ti tabler-circle-check me-1"></i> تأكيد الاكتمال
            </button>
            <button id="btn-early-close" class="btn btn-label-warning">
                <i class="ti tabler-lock me-1"></i> إغلاق مبكر
            </button>
        @endif
        <a href="{{ route('dashboard.settlements.index') }}" class="btn btn-label-secondary">
            <i class="ti tabler-arrow-right me-1"></i> رجوع
        </a>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     TOP INFO ROW
════════════════════════════════════════════════════════ --}}
<div class="row g-4 mb-4">

    {{-- ── Settlement Summary Card ─────────────────────── --}}
    <div class="col-lg-5">
        <div class="card glass-card border-0 h-100">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0"><i class="ti tabler-info-circle me-2 text-primary"></i>ملخص التسوية</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted small fw-semibold" style="width:45%">رقم الحوالة</td>
                            <td><span class="fw-bold text-primary font-monospace">{{ $settlement['reference_number'] }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted small fw-semibold">المركز</td>
                            <td class="fw-semibold">{{ $settlement['center_name'] }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small fw-semibold">نوع الدورة</td>
                            <td>
                                @php $typeLabel = ['weekly'=>'أسبوعي','biweekly'=>'أسبوعين','monthly'=>'شهري'][$settlement['period_type']] ?? $settlement['period_type']; @endphp
                                <span class="badge bg-label-secondary">{{ $typeLabel }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted small fw-semibold">تاريخ البداية</td>
                            <td>{{ $settlement['starts_at'] }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small fw-semibold">تاريخ الإغلاق</td>
                            <td>{{ $settlement['ends_at'] ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small fw-semibold">الحالة</td>
                            <td>
                                @php
                                    $sc = ['open'=>['bg-label-warning','مفتوحة'],'partial_paid'=>['bg-label-orange','مدفوع جزئياً'],'completed'=>['bg-label-success','مكتملة']][$settlement['status']] ?? ['bg-label-secondary',$settlement['status']];
                                @endphp
                                <span class="badge {{ $sc[0] }}">{{ $sc[1] }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Financial Breakdown Card ─────────────────────── --}}
    <div class="col-lg-7">
        <div class="card glass-card border-0 h-100">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0"><i class="ti tabler-calculator me-2 text-success"></i>التفاصيل المالية</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="financial-breakdown-item visa">
                            <div class="fbi-icon"><i class="ti tabler-credit-card ti-md"></i></div>
                            <div class="fbi-label">إجمالي الفيزا</div>
                            <div class="fbi-value">{{ number_format($settlement['total_visa_amount']) }} <small>EGP</small></div>
                            <div class="fbi-note">المنصة تمسك الفلوس</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="financial-breakdown-item cash">
                            <div class="fbi-icon"><i class="ti tabler-cash ti-md"></i></div>
                            <div class="fbi-label">إجمالي الكاش</div>
                            <div class="fbi-value">{{ number_format($settlement['total_cash_amount']) }} <small>EGP</small></div>
                            <div class="fbi-note">المركز يمسك الفلوس</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="financial-breakdown-item commission">
                            <div class="fbi-icon"><i class="ti tabler-percentage ti-md"></i></div>
                            <div class="fbi-label">عمولة المنصة</div>
                            <div class="fbi-value">{{ number_format($settlement['platform_commission']) }} <small>EGP</small></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="financial-breakdown-item net {{ $settlement['direction'] === 'platform_pays_center' ? 'net-green' : 'net-red' }}">
                            <div class="fbi-icon"><i class="ti tabler-arrows-exchange ti-md"></i></div>
                            <div class="fbi-label">الصافي المستحق</div>
                            <div class="fbi-value">{{ number_format($settlement['net_due']) }} <small>EGP</small></div>
                            <div class="fbi-note">
                                @if($settlement['direction'] === 'platform_pays_center')
                                    <i class="ti tabler-arrow-down-left me-1"></i>المنصة تدفع للمركز
                                @else
                                    <i class="ti tabler-arrow-up-right me-1"></i>المركز يدفع للمنصة
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment progress --}}
                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-semibold text-muted">تقدم السداد</span>
                    <span class="small fw-bold">{{ $settlement['payment_progress'] }}%</span>
                </div>
                <div class="progress mb-2" style="height:10px; border-radius:6px;">
                    <div class="progress-bar
                        {{ $settlement['payment_progress'] >= 100 ? 'bg-success' : ($settlement['payment_progress'] > 0 ? 'bg-warning' : 'bg-secondary') }}"
                        style="width: {{ $settlement['payment_progress'] }}%; transition: width 0.6s ease;">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="small text-success"><i class="ti tabler-check me-1"></i>مسدد: {{ number_format($settlement['total_paid']) }} EGP</span>
                    <span class="small text-danger"><i class="ti tabler-clock me-1"></i>متبقي: {{ number_format($settlement['balance_remaining']) }} EGP</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     PAYMENTS HISTORY
════════════════════════════════════════════════════════ --}}
<div class="card glass-card overflow-hidden mb-4">
    <div class="card-header border-bottom d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0"><i class="ti tabler-receipt me-2 text-success"></i>سجل الدفعات</h5>
        <span class="badge bg-label-success">{{ count($payments) }} دفعة</span>
    </div>
    <div class="card-datatable table-responsive">
        <table class="table table-hover fixit-table border-top mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المبلغ</th>
                    <th>تاريخ الدفع</th>
                    <th>ملاحظة</th>
                    <th>سجلها</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $i => $payment)
                <tr>
                    <td><span class="badge bg-label-secondary">{{ $i + 1 }}</span></td>
                    <td><span class="fw-bold text-success">{{ number_format($payment['amount']) }} EGP</span></td>
                    <td>{{ $payment['paid_at'] }}</td>
                    <td>{{ $payment['note'] ?? '—' }}</td>
                    <td>{{ $payment['recorded_by'] ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="ti tabler-receipt-off ti-lg mb-2 d-block"></i>
                        لا توجد دفعات مسجلة بعد
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     ORDERS TABLE
════════════════════════════════════════════════════════ --}}
<div class="card glass-card overflow-hidden">
    <div class="card-header border-bottom d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0"><i class="ti tabler-clipboard-list me-2 text-primary"></i>الأوردرات المشمولة</h5>
        <span class="badge bg-label-primary">{{ count($orders) }} أوردر</span>
    </div>
    <div class="card-datatable table-responsive">
        <table class="table table-hover fixit-table border-top mb-0">
            <thead>
                <tr>
                    <th>رقم الأوردر</th>
                    <th>طريقة الدفع</th>
                    <th>قيمة الأوردر</th>
                    <th>العمولة</th>
                    <th>صافي المركز</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>
                        <span class="fw-bold font-monospace text-primary">{{ $order['reference'] }}</span>
                    </td>
                    <td>
                        @if($order['payment_method'] === 'visa')
                            <span class="payment-badge visa-badge">
                                <i class="ti tabler-credit-card me-1"></i>فيزا
                            </span>
                        @else
                            <span class="payment-badge cash-badge">
                                <i class="ti tabler-cash me-1"></i>كاش
                            </span>
                        @endif
                    </td>
                    <td><span class="fw-semibold">{{ number_format($order['amount']) }} EGP</span></td>
                    <td>
                        <span class="text-danger fw-semibold">-{{ number_format($order['commission']) }} EGP</span>
                    </td>
                    <td>
                        @php $net = $order['amount'] - $order['commission']; @endphp
                        <span class="fw-bold text-success">{{ number_format($net) }} EGP</span>
                    </td>
                    <td class="text-muted">{{ $order['date'] }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-active">
                <tr>
                    <td colspan="2" class="fw-bold">الإجمالي</td>
                    <td class="fw-bold">{{ number_format(array_sum(array_column($orders, 'amount'))) }} EGP</td>
                    <td class="fw-bold text-danger">-{{ number_format(array_sum(array_column($orders, 'commission'))) }} EGP</td>
                    <td class="fw-bold text-success">
                        {{ number_format(array_sum(array_column($orders, 'amount')) - array_sum(array_column($orders, 'commission'))) }} EGP
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     MODALS
════════════════════════════════════════════════════════ --}}

{{-- Add Payment Modal --}}
<div class="modal fade" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title"><i class="ti tabler-plus me-2 text-success"></i>تسجيل دفعة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
                    <i class="ti tabler-info-circle"></i>
                    <div>
                        المبلغ المتبقي: <strong>{{ number_format($settlement['balance_remaining']) }} EGP</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">المبلغ المدفوع <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" id="payment_amount" class="form-control" placeholder="0" min="1" max="{{ $settlement['balance_remaining'] }}">
                        <span class="input-group-text">EGP</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">تاريخ الدفع <span class="text-danger">*</span></label>
                    <input type="date" id="payment_date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">ملاحظة</label>
                    <textarea id="payment_note" class="form-control" rows="2" placeholder="مثال: تحويل بنكي، حوالة..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="btn-save-payment" class="btn btn-success">
                    <i class="ti tabler-device-floppy me-1"></i> حفظ الدفعة
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Styles --}}
<style>
.financial-breakdown-item {
    border-radius: 12px;
    padding: 14px 16px;
    border: 1px solid rgba(115,103,240,0.1);
    background: rgba(115,103,240,0.04);
    height: 100%;
}
.financial-breakdown-item.visa    { border-color: rgba(115,103,240,0.2); background: rgba(115,103,240,0.06); }
.financial-breakdown-item.cash    { border-color: rgba(40,199,111,0.2);  background: rgba(40,199,111,0.06); }
.financial-breakdown-item.commission { border-color: rgba(255,159,67,0.2); background: rgba(255,159,67,0.06); }
.financial-breakdown-item.net-green  { border-color: rgba(40,199,111,0.3);  background: rgba(40,199,111,0.08); }
.financial-breakdown-item.net-red    { border-color: rgba(234,84,85,0.3);   background: rgba(234,84,85,0.08); }
.fbi-icon { font-size: 1.3rem; margin-bottom: 6px; opacity: 0.7; }
.fbi-label { font-size: 0.75rem; color: #8592a3; font-weight: 600; margin-bottom: 2px; }
.fbi-value { font-size: 1.15rem; font-weight: 700; color: #444; }
.fbi-note  { font-size: 0.72rem; margin-top: 4px; color: #8592a3; }

.payment-badge {
    display: inline-flex;
    align-items: center;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
}
.visa-badge {
    background: rgba(115,103,240,0.12);
    color: #7367f0;
    border: 1px solid rgba(115,103,240,0.25);
}
.cash-badge {
    background: rgba(40,199,111,0.12);
    color: #28c76f;
    border: 1px solid rgba(40,199,111,0.25);
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
    const settlementId = {{ $settlement['id'] }};

    // ── Add Payment button ──────────────────────────────
    document.getElementById('btn-add-payment')?.addEventListener('click', () => {
        new bootstrap.Modal(document.getElementById('addPaymentModal')).show();
    });

    // ── Save Payment ────────────────────────────────────
    document.getElementById('btn-save-payment')?.addEventListener('click', function () {
        const amount  = document.getElementById('payment_amount').value;
        const date    = document.getElementById('payment_date').value;
        const note    = document.getElementById('payment_note').value;

        if (!amount || amount <= 0) {
            Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'يرجى إدخال مبلغ صحيح.' });
            return;
        }

        fetch(`/dashboard/settlements/${settlementId}/add-payment`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ amount, paid_at: date, note }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'تم', text: data.message, timer: 2000, showConfirmButton: false })
                    .then(() => location.reload());
            }
        });
    });

    // ── Mark Complete ───────────────────────────────────
    document.getElementById('btn-mark-complete')?.addEventListener('click', () => {
        Swal.fire({
            title: 'تأكيد اكتمال التسوية؟',
            text: 'سيتم إغلاق هذه التسوية نهائياً وتحديد حالتها كـ "مكتملة".',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="ti tabler-circle-check me-1"></i> نعم، أكمل',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#28c76f',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/dashboard/settlements/${settlementId}/mark-complete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: 'تم', text: data.message, timer: 2000, showConfirmButton: false })
                            .then(() => location.reload());
                    }
                });
            }
        });
    });

    // ── Early Close ─────────────────────────────────────
    document.getElementById('btn-early-close')?.addEventListener('click', () => {
        Swal.fire({
            title: 'إغلاق مبكر للتسوية؟',
            html: `<p class="text-muted small">سيتم إغلاق الدورة الحالية فوراً بدلاً من انتظار نهايتها الطبيعية.</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ti tabler-lock me-1"></i> نعم، أغلق الآن',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#ff9f43',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/dashboard/settlements/${settlementId}/early-close`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: 'تم', text: data.message, timer: 2000, showConfirmButton: false })
                            .then(() => location.reload());
                    }
                });
            }
        });
    });
});
</script>
@endsection
