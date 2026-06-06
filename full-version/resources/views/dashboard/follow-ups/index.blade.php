@extends('layouts/layoutMaster')
@section('title', 'متابعة ما بعد الصيانة - Fix-It')

@section('content')

{{-- ═══════════════════════════════════════════════════════
     PAGE HEADER
════════════════════════════════════════════════════════ --}}
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-headset me-2"></i>متابعة ما بعد الصيانة</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>متابعة العملاء</span>
        </div>
    </div>
</div>

{{-- STAT CARDS - unified glass-card --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-2">
        <div class="card glass-card border-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="stat-icon gradient-primary flex-shrink-0" style="width:40px;height:40px;min-width:40px;border-radius:10px;font-size:1.1rem">
                    <i class="ti tabler-list-check text-white"></i>
                </div>
                <div>
                    <div class="card-label" style="font-size:0.72rem">الإجمالي</div>
                    <div class="card-value" style="font-size:1.4rem">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card glass-card border-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="stat-icon gradient-secondary flex-shrink-0" style="width:40px;height:40px;min-width:40px;border-radius:10px;font-size:1.1rem">
                    <i class="ti tabler-clock text-white"></i>
                </div>
                <div>
                    <div class="card-label" style="font-size:0.72rem">لم يُتواصل</div>
                    <div class="card-value" style="font-size:1.4rem">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card glass-card border-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="stat-icon gradient-warning flex-shrink-0" style="width:40px;height:40px;min-width:40px;border-radius:10px;font-size:1.1rem">
                    <i class="ti tabler-phone-calling text-white"></i>
                </div>
                <div>
                    <div class="card-label" style="font-size:0.72rem">قيد المتابعة</div>
                    <div class="card-value" style="font-size:1.4rem">{{ $stats['in_progress'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card glass-card border-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="stat-icon gradient-success flex-shrink-0" style="width:40px;height:40px;min-width:40px;border-radius:10px;font-size:1.1rem">
                    <i class="ti tabler-mood-happy text-white"></i>
                </div>
                <div>
                    <div class="card-label" style="font-size:0.72rem">راضن</div>
                    <div class="card-value" style="font-size:1.4rem">{{ $stats['satisfied'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card glass-card border-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="stat-icon gradient-danger flex-shrink-0" style="width:40px;height:40px;min-width:40px;border-radius:10px;font-size:1.1rem">
                    <i class="ti tabler-mood-sad text-white"></i>
                </div>
                <div>
                    <div class="card-label" style="font-size:0.72rem">غير راضن</div>
                    <div class="card-value" style="font-size:1.4rem">{{ $stats['not_satisfied'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card glass-card border-0 {{ $stats['overdue']>0?'border-start border-danger border-3':'' }}">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="stat-icon {{ $stats['overdue']>0?'gradient-danger':'gradient-secondary' }} flex-shrink-0" style="width:40px;height:40px;min-width:40px;border-radius:10px;font-size:1.1rem">
                    <i class="ti tabler-phone-off text-white"></i>
                </div>
                <div>
                    <div class="card-label" style="font-size:0.72rem">لا يرد / متأخرة</div>
                    <div class="card-value" style="font-size:1.4rem">{{ $stats['unreachable'] + $stats['overdue'] }}</div>
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
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold">بحث (العميل / الأوردر / الجهاز)</label>
                <input type="text" name="q" class="form-control" placeholder="بحث..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">الحالة</label>
                <select name="status" class="form-select">
                    <option value="">الكل</option>
                    <option value="pending"       {{ request('status') === 'pending'       ? 'selected' : '' }}>لم يُتواصل</option>
                    <option value="in_progress"   {{ request('status') === 'in_progress'   ? 'selected' : '' }}>قيد المتابعة</option>
                    <option value="satisfied"     {{ request('status') === 'satisfied'     ? 'selected' : '' }}>راضٍ</option>
                    <option value="not_satisfied" {{ request('status') === 'not_satisfied' ? 'selected' : '' }}>غير راضٍ</option>
                    <option value="unreachable"   {{ request('status') === 'unreachable'   ? 'selected' : '' }}>لا يرد</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">ترتيب</label>
                <select name="sort" class="form-select">
                    <option value="newest">الأحدث أولاً</option>
                    <option value="oldest">الأقدم أولاً</option>
                    <option value="scheduled">الموعد القادم</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="ti tabler-filter me-1"></i> تصفية
                </button>
                <a href="{{ route('dashboard.follow-ups.index') }}" class="btn btn-label-secondary" title="إعادة تعيين">
                    <i class="ti tabler-refresh"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     FOLLOW-UPS TABLE
════════════════════════════════════════════════════════ --}}
<div class="card glass-card overflow-hidden">
    <div class="card-header border-bottom d-flex align-items-center">
        <h5 class="card-title mb-0"><i class="ti tabler-users me-2 text-primary"></i>قائمة العملاء للمتابعة</h5>
        <span class="badge bg-label-primary ms-2">{{ count($followUps) }}</span>
    </div>
    <div class="card-datatable table-responsive">
        <table class="table table-hover fixit-table border-top mb-0">
            <thead>
                <tr>
                    <th>العميل</th>
                    <th>الأوردر / الجهاز</th>
                    <th>المركز</th>
                    <th>تاريخ الإنجاز</th>
                    <th>المحاولات</th>
                    <th>آخر تواصل</th>
                    <th>الموعد القادم</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($followUps as $f)
                @php
                    $statusMap = [
                        'pending'       => ['bg-label-secondary', 'لم يُتواصل'],
                        'in_progress'   => ['bg-label-warning',   'قيد المتابعة'],
                        'satisfied'     => ['bg-label-success',   'راضٍ'],
                        'not_satisfied' => ['bg-label-danger',    'غير راضٍ'],
                        'unreachable'   => ['bg-label-dark',      'لا يرد'],
                    ];
                    [$statusClass, $statusLabel] = $statusMap[$f['status']] ?? ['bg-label-secondary', $f['status']];

                    $typeIcons = ['call'=>'tabler-phone','whatsapp'=>'tabler-brand-whatsapp','sms'=>'tabler-message','email'=>'tabler-mail'];
                    $resultColors = ['answered'=>'success','no_answer'=>'danger','busy'=>'warning','message_sent'=>'primary','left_voicemail'=>'info'];

                    $isOverdue = $f['scheduled_at'] && $f['scheduled_at'] < now()->toDateTimeString() && !in_array($f['status'], ['satisfied']);
                @endphp
                <tr class="{{ $isOverdue ? 'table-warning-soft' : '' }}">
                    {{-- Customer --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm">
                                <span class="avatar-initial rounded-circle bg-label-primary fw-bold">
                                    {{ mb_substr($f['customer_name'], 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $f['customer_name'] }}</div>
                                <a href="tel:{{ $f['customer_phone'] }}" class="small text-muted d-flex align-items-center gap-1">
                                    <i class="ti tabler-phone" style="font-size:0.7rem"></i>
                                    {{ $f['customer_phone'] }}
                                </a>
                            </div>
                        </div>
                    </td>

                    {{-- Order & Device --}}
                    <td>
                        <span class="fw-bold font-monospace text-primary">{{ $f['order_reference'] }}</span>
                        <div class="small text-muted">{{ $f['device_name'] }}</div>
                    </td>

                    {{-- Center --}}
                    <td class="small">{{ $f['center_name'] }}</td>

                    {{-- Completed at --}}
                    <td class="small text-muted">{{ $f['completed_at'] }}</td>

                    {{-- Attempts --}}
                    <td>
                        @if($f['attempts_count'] > 0)
                            <span class="badge bg-label-info">{{ $f['attempts_count'] }} محاولة</span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>

                    {{-- Last contact --}}
                    <td>
                        @if($f['last_attempt_at'])
                            <div class="d-flex align-items-center gap-1">
                                <i class="ti {{ $typeIcons[$f['last_attempt_type']] ?? 'tabler-phone' }} small
                                   text-{{ $resultColors[$f['last_result']] ?? 'secondary' }}"></i>
                                <span class="small">{{ $f['last_attempt_at'] }}</span>
                            </div>
                            @php $rc = $resultColors[$f['last_result']] ?? 'secondary'; @endphp
                            <span class="badge bg-label-{{ $rc }} mt-1" style="font-size:0.65rem">
                                {{ ['answered'=>'رد','no_answer'=>'لم يرد','busy'=>'مشغول','message_sent'=>'أُرسلت','left_voicemail'=>'رسالة صوتية'][$f['last_result']] ?? $f['last_result'] }}
                            </span>
                        @else
                            <span class="text-muted small">لا يوجد</span>
                        @endif
                    </td>

                    {{-- Scheduled --}}
                    <td>
                        @if($f['scheduled_at'])
                            <span class="scheduled-badge {{ $isOverdue ? 'overdue' : '' }}">
                                <i class="ti tabler-calendar-event me-1"></i>
                                {{ \Carbon\Carbon::parse($f['scheduled_at'])->format('d M - h:i A') }}
                                @if($isOverdue)
                                    <span class="badge bg-danger ms-1" style="font-size:0.6rem">متأخرة!</span>
                                @endif
                            </span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td>
                        <span class="badge {{ $statusClass }} status-badge">{{ $statusLabel }}</span>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('dashboard.follow-ups.show', $f['id']) }}"
                               class="btn btn-sm btn-icon btn-label-primary" title="عرض / تسجيل محاولة">
                                <i class="ti tabler-eye"></i>
                            </a>
                            @if($f['status'] !== 'satisfied')
                                <button class="btn btn-sm btn-icon btn-label-success btn-quick-log"
                                        data-id="{{ $f['id'] }}"
                                        data-name="{{ $f['customer_name'] }}"
                                        title="تسجيل محاولة سريعة">
                                    <i class="ti tabler-phone-plus"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="ti tabler-mood-happy ti-lg mb-2 d-block text-success"></i>
                        لا توجد متابعات معلقة 🎉
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     QUICK LOG OFFCANVAS (القائمة الجانبية السريعة)
════════════════════════════════════════════════════════ --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="quickLogOffcanvas" aria-labelledby="quickLogOffcanvasLabel" style="width: 450px;">
    <div class="offcanvas-header border-bottom">
        <h5 id="quickLogOffcanvasLabel" class="offcanvas-title">
            <i class="ti tabler-phone-plus me-2 text-success"></i>
            تسجيل محاولة — <span id="ql-customer-name"></span>
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <input type="hidden" id="ql-followup-id">

        {{-- Type --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">نوع التواصل <span class="text-danger">*</span></label>
            <div class="d-flex gap-2 flex-wrap">
                <label class="contact-type-btn flex-grow-1">
                    <input type="radio" name="ql_type" value="call" checked>
                    <span class="w-100 justify-content-center"><i class="ti tabler-phone me-1"></i>مكالمة</span>
                </label>
                <label class="contact-type-btn flex-grow-1">
                    <input type="radio" name="ql_type" value="whatsapp">
                    <span class="w-100 justify-content-center"><i class="ti tabler-brand-whatsapp me-1"></i>واتساب</span>
                </label>
                <label class="contact-type-btn flex-grow-1">
                    <input type="radio" name="ql_type" value="sms">
                    <span class="w-100 justify-content-center"><i class="ti tabler-message me-1"></i>SMS</span>
                </label>
            </div>
        </div>

        {{-- Result --}}
        <div class="mb-3" id="call-results">
            <label class="form-label fw-semibold">النتيجة <span class="text-danger">*</span></label>
            <select id="ql_result" class="form-select">
                <option value="answered">✅ رد</option>
                <option value="no_answer">❌ لم يرد</option>
                <option value="busy">📵 مشغول</option>
                <option value="left_voicemail">🔉 ترك رسالة صوتية</option>
            </select>
        </div>

        {{-- Reaction (shown only if answered) --}}
        <div class="mb-3 d-none" id="reaction-row">
            <label class="form-label fw-semibold">رأي العميل</label>
            <select id="ql_reaction" class="form-select">
                <option value="">— اختياري —</option>
                <option value="satisfied">😊 راضٍ جداً</option>
                <option value="neutral">😐 محايد</option>
                <option value="not_satisfied">😞 غير راضٍ</option>
                <option value="needs_revisit">🔧 يحتاج زيارة مرة أخرى</option>
            </select>
        </div>

        {{-- Note --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">ملاحظة</label>
            <textarea id="ql_note" class="form-control" rows="4" placeholder="مثال: العميل قال إن المكيف يعمل بشكل ممتاز..."></textarea>
        </div>

        {{-- Schedule next --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">جدولة متابعة قادمة (اختياري)</label>
            <input type="datetime-local" id="ql_scheduled" class="form-control">
        </div>

        {{-- Actions --}}
        <div class="mt-auto border-top pt-3 d-flex gap-2">
            <button type="button" class="btn btn-label-secondary flex-grow-1" data-bs-dismiss="offcanvas">إلغاء</button>
            <button type="button" id="btn-save-attempt" class="btn btn-success flex-grow-1">
                <i class="ti tabler-device-floppy me-1"></i> حفظ
            </button>
        </div>
    </div>
</div>

<style>
/* ── Stat pills ────────────────────────────────────────── */
.followup-stats-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.followup-stat-pill {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    border-radius: 14px;
    border: 1px solid transparent;
    flex: 1;
    min-width: 120px;
    font-size: 1.4rem;
    transition: transform 0.2s;
}
.followup-stat-pill:hover { transform: translateY(-2px); }
.followup-stat-pill.total        { background: rgba(115,103,240,0.08); border-color: rgba(115,103,240,0.2); color: #7367f0; }
.followup-stat-pill.pending      { background: rgba(130,134,139,0.08); border-color: rgba(130,134,139,0.2); color: #82868b; }
.followup-stat-pill.in-progress  { background: rgba(255,159,67,0.08);  border-color: rgba(255,159,67,0.2);  color: #ff9f43; }
.followup-stat-pill.satisfied    { background: rgba(40,199,111,0.08);  border-color: rgba(40,199,111,0.2);  color: #28c76f; }
.followup-stat-pill.not-satisfied{ background: rgba(234,84,85,0.08);   border-color: rgba(234,84,85,0.2);   color: #ea5455; }
.followup-stat-pill.unreachable  { background: rgba(75,75,75,0.08);    border-color: rgba(75,75,75,0.2);    color: #4b4b4b; }
.followup-stat-pill.overdue      { background: rgba(234,84,85,0.15);   border-color: #ea5455;               color: #ea5455; animation: pulse-red 1.5s infinite; }
.fsp-content .fsp-value { font-size: 1.4rem; font-weight: 700; line-height: 1; }
.fsp-content .fsp-label { font-size: 0.72rem; opacity: 0.75; margin-top: 2px; }

@keyframes pulse-red {
    0%, 100% { box-shadow: 0 0 0 0 rgba(234,84,85,0.4); }
    50%       { box-shadow: 0 0 0 6px rgba(234,84,85,0); }
}

/* ── Scheduled badge ──────────────────────────────────── */
.scheduled-badge {
    display: inline-flex;
    align-items: center;
    font-size: 0.78rem;
    padding: 3px 10px;
    border-radius: 20px;
    background: rgba(115,103,240,0.1);
    color: #7367f0;
    font-weight: 600;
}
.scheduled-badge.overdue {
    background: rgba(234,84,85,0.1);
    color: #ea5455;
}

/* ── Contact type buttons ─────────────────────────────── */
.contact-type-btn input { display: none; }
.contact-type-btn span {
    display: inline-flex;
    align-items: center;
    padding: 6px 16px;
    border-radius: 20px;
    border: 1.5px solid #d9d9d9;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    color: #6e6b7b;
}
.contact-type-btn input:checked + span {
    border-color: #7367f0;
    background: rgba(115,103,240,0.1);
    color: #7367f0;
}

.table-warning-soft { background: rgba(255,159,67,0.05) !important; }
</style>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Quick Log Offcanvas ──────────────────────────────────
    const quickOffcanvas = new bootstrap.Offcanvas(document.getElementById('quickLogOffcanvas'));

    document.querySelectorAll('.btn-quick-log').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('ql-followup-id').value    = this.dataset.id;
            document.getElementById('ql-customer-name').textContent = this.dataset.name;
            document.getElementById('ql_note').value           = '';
            document.getElementById('ql_scheduled').value      = '';
            quickOffcanvas.show();
        });
    });

    // Show reaction field only if "answered"
    document.getElementById('ql_result')?.addEventListener('change', function () {
        document.getElementById('reaction-row').classList.toggle('d-none', this.value !== 'answered');
    });

    // Switch result options based on type
    document.querySelectorAll('[name="ql_type"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const resultSel = document.getElementById('ql_result');
            if (this.value === 'call') {
                resultSel.innerHTML = `
                    <option value="answered">✅ رد</option>
                    <option value="no_answer">❌ لم يرد</option>
                    <option value="busy">📵 مشغول</option>
                    <option value="left_voicemail">🔉 ترك رسالة صوتية</option>`;
                document.getElementById('call-results').classList.remove('d-none');
            } else {
                resultSel.innerHTML = `<option value="message_sent">✅ أُرسلت الرسالة</option><option value="bounced">❌ لم تصل</option>`;
                document.getElementById('call-results').classList.remove('d-none');
                document.getElementById('reaction-row').classList.add('d-none');
            }
        });
    });

    // ── Save attempt ─────────────────────────────────────
    document.getElementById('btn-save-attempt')?.addEventListener('click', function () {
        const id       = document.getElementById('ql-followup-id').value;
        const type     = document.querySelector('[name="ql_type"]:checked').value;
        const result   = document.getElementById('ql_result').value;
        const reaction = document.getElementById('ql_reaction').value;
        const note     = document.getElementById('ql_note').value;
        const sched    = document.getElementById('ql_scheduled').value;

        fetch(`/dashboard/follow-ups/${id}/log-attempt`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ type, result, customer_reaction: reaction, note, scheduled_at: sched }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                quickOffcanvas.hide();
                Swal.fire({ icon: 'success', title: 'تم', text: data.message, timer: 2000, showConfirmButton: false })
                    .then(() => location.reload());
            }
        });
    });

});
</script>
@endsection
