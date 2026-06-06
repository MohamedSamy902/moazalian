@extends('layouts/layoutMaster')
@section('title', 'متابعة ' . $followUp['customer_name'] . ' - Fix-It')

@section('content')

{{-- ═══════════════════════════════════════════════════════
     PAGE HEADER
════════════════════════════════════════════════════════ --}}
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">
            <i class="ti tabler-user-check me-2"></i>
            متابعة: <span class="text-primary">{{ $followUp['customer_name'] }}</span>
            <small class="text-muted font-monospace fs-6 ms-2">{{ $followUp['order_reference'] }}</small>
        </h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <a href="{{ route('dashboard.follow-ups.index') }}">متابعة العملاء</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>{{ $followUp['customer_name'] }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        @if($followUp['status'] !== 'satisfied')
            <button id="btn-log-attempt" class="btn btn-primary">
                <i class="ti tabler-phone-plus me-1"></i> تسجيل محاولة
            </button>
            <button id="btn-schedule" class="btn btn-label-warning">
                <i class="ti tabler-calendar-plus me-1"></i> جدولة موعد
            </button>
            <button id="btn-mark-satisfied" class="btn btn-label-success">
                <i class="ti tabler-mood-happy me-1"></i> تأكيد رضا العميل
            </button>
        @endif
        <a href="{{ route('dashboard.follow-ups.index') }}" class="btn btn-label-secondary">
            <i class="ti tabler-arrow-right me-1"></i> رجوع
        </a>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     INFO CARDS ROW
════════════════════════════════════════════════════════ --}}
<div class="row g-4 mb-4">

    {{-- Customer info --}}
    <div class="col-lg-4">
        <div class="card glass-card border-0 h-100">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0"><i class="ti tabler-user me-2 text-primary"></i>بيانات العميل</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="avatar avatar-lg">
                        <span class="avatar-initial rounded-circle bg-label-primary fw-bold fs-4">
                            {{ mb_substr($followUp['customer_name'], 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <div class="fw-bold fs-5">{{ $followUp['customer_name'] }}</div>
                        <a href="tel:{{ $followUp['customer_phone'] }}" class="text-primary d-flex align-items-center gap-1 mt-1">
                            <i class="ti tabler-phone-call"></i> {{ $followUp['customer_phone'] }}
                        </a>
                    </div>
                </div>
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted small fw-semibold py-1">الجهاز</td>
                        <td class="fw-semibold py-1">{{ $followUp['device_name'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small fw-semibold py-1">المركز</td>
                        <td class="py-1">{{ $followUp['center_name'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small fw-semibold py-1">الفني</td>
                        <td class="py-1">{{ $followUp['technician_name'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small fw-semibold py-1">تاريخ الإنجاز</td>
                        <td class="py-1">{{ $followUp['completed_at'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small fw-semibold py-1">الموعد القادم</td>
                        <td class="py-1">
                            @if($followUp['scheduled_at'])
                                <span class="badge bg-label-warning">
                                    <i class="ti tabler-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($followUp['scheduled_at'])->format('d M Y - h:i A') }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Status + summary --}}
    <div class="col-lg-8">
        <div class="card glass-card border-0 h-100">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0"><i class="ti tabler-chart-bar me-2 text-warning"></i>ملخص المتابعة</h5>
            </div>
            <div class="card-body">
                {{-- Status big badge --}}
                @php
                    $statusMap = [
                        'pending'       => ['secondary', 'لم يُتواصل بعد',    'tabler-clock'],
                        'in_progress'   => ['warning',   'قيد المتابعة',      'tabler-phone-calling'],
                        'satisfied'     => ['success',   'العميل راضٍ ✓',     'tabler-mood-happy'],
                        'not_satisfied' => ['danger',    'العميل غير راضٍ',   'tabler-mood-sad'],
                        'unreachable'   => ['dark',      'لا يمكن الوصول إليه', 'tabler-phone-off'],
                    ];
                    [$sc, $sl, $si] = $statusMap[$followUp['status']] ?? ['secondary', $followUp['status'], 'tabler-circle'];
                @endphp
                <div class="followup-status-hero {{ $sc }} mb-4">
                    <i class="ti {{ $si }}"></i>
                    <span>{{ $sl }}</span>
                </div>

                {{-- Quick stats --}}
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="quick-stat">
                            <div class="qs-value">{{ count($attempts) }}</div>
                            <div class="qs-label">محاولة تواصل</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="quick-stat">
                            <div class="qs-value text-success">
                                {{ collect($attempts)->where('result', 'answered')->count() }}
                            </div>
                            <div class="qs-label">ردّ على المكالمة</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="quick-stat">
                            <div class="qs-value text-danger">
                                {{ collect($attempts)->whereIn('result', ['no_answer', 'busy'])->count() }}
                            </div>
                            <div class="qs-label">لم يرد</div>
                        </div>
                    </div>
                </div>

                @if($followUp['notes'])
                    <div class="alert alert-info mt-3 mb-0 d-flex align-items-start gap-2">
                        <i class="ti tabler-notes mt-1"></i>
                        <div>{{ $followUp['notes'] }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     ATTEMPTS TIMELINE
════════════════════════════════════════════════════════ --}}
<div class="card glass-card overflow-hidden">
    <div class="card-header border-bottom d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0"><i class="ti tabler-timeline me-2 text-primary"></i>سجل المحاولات</h5>
        <span class="badge bg-label-primary">{{ count($attempts) }} محاولة</span>
    </div>
    <div class="card-body">
        @forelse($attempts as $i => $attempt)
        @php
            $typeMap = [
                'call'     => ['primary', 'tabler-phone',           'مكالمة هاتفية'],
                'whatsapp' => ['success', 'tabler-brand-whatsapp',  'واتساب'],
                'sms'      => ['info',    'tabler-message',         'SMS'],
                'email'    => ['warning', 'tabler-mail',            'بريد إلكتروني'],
            ];
            [$tc, $ti, $tl] = $typeMap[$attempt['type']] ?? ['secondary', 'tabler-phone', $attempt['type']];

            $resultMap = [
                'answered'       => ['success', '✅ رد'],
                'no_answer'      => ['danger',  '❌ لم يرد'],
                'busy'           => ['warning', '📵 مشغول'],
                'left_voicemail' => ['info',    '🔉 رسالة صوتية'],
                'message_sent'   => ['primary', '✅ أُرسلت'],
                'bounced'        => ['secondary','❌ لم تصل'],
            ];
            [$rc, $rl] = $resultMap[$attempt['result']] ?? ['secondary', $attempt['result']];

            $reactionMap = [
                'satisfied'     => ['success', '😊 راضٍ'],
                'not_satisfied' => ['danger',  '😞 غير راضٍ'],
                'needs_revisit' => ['warning', '🔧 يحتاج زيارة'],
                'neutral'       => ['secondary','😐 محايد'],
            ];
        @endphp
        <div class="attempt-timeline-item">
            <div class="atl-connector"></div>
            <div class="atl-icon bg-label-{{ $tc }}">
                <i class="ti {{ $ti }} text-{{ $tc }}"></i>
            </div>
            <div class="atl-content">
                <div class="atl-header">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="fw-bold">المحاولة #{{ $i + 1 }}</span>
                        <span class="badge bg-label-{{ $tc }}">{{ $tl }}</span>
                        <span class="badge bg-label-{{ $rc }}">{{ $rl }}</span>
                        @if($attempt['customer_reaction'] && isset($reactionMap[$attempt['customer_reaction']]))
                            @php [$reac, $real] = $reactionMap[$attempt['customer_reaction']]; @endphp
                            <span class="badge bg-label-{{ $reac }}">{{ $real }}</span>
                        @endif
                    </div>
                    <div class="atl-meta">
                        <i class="ti tabler-clock me-1"></i>{{ $attempt['attempted_at'] }}
                        @if($attempt['attempted_by'])
                            &nbsp;|&nbsp;<i class="ti tabler-user me-1"></i>{{ $attempt['attempted_by'] }}
                        @endif
                    </div>
                </div>
                @if($attempt['note'])
                    <div class="atl-note">
                        <i class="ti tabler-message-circle me-1 text-muted"></i>
                        {{ $attempt['note'] }}
                    </div>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="ti tabler-phone-off ti-lg mb-2 d-block"></i>
            لا توجد محاولات مسجلة بعد
            <div class="mt-2">
                <button class="btn btn-sm btn-primary" id="btn-log-attempt-empty">
                    <i class="ti tabler-phone-plus me-1"></i> سجّل المحاولة الأولى
                </button>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     LOG ATTEMPT MODAL
════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="logAttemptModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title"><i class="ti tabler-phone-plus me-2 text-primary"></i>تسجيل محاولة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    {{-- Type --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">نوع التواصل <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 flex-wrap">
                            <label class="contact-type-btn">
                                <input type="radio" name="la_type" value="call" checked>
                                <span><i class="ti tabler-phone me-1"></i>مكالمة هاتفية</span>
                            </label>
                            <label class="contact-type-btn">
                                <input type="radio" name="la_type" value="whatsapp">
                                <span><i class="ti tabler-brand-whatsapp me-1"></i>واتساب</span>
                            </label>
                            <label class="contact-type-btn">
                                <input type="radio" name="la_type" value="sms">
                                <span><i class="ti tabler-message me-1"></i>رسالة SMS</span>
                            </label>
                            <label class="contact-type-btn">
                                <input type="radio" name="la_type" value="email">
                                <span><i class="ti tabler-mail me-1"></i>بريد إلكتروني</span>
                            </label>
                        </div>
                    </div>

                    {{-- Result --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">النتيجة <span class="text-danger">*</span></label>
                        <select id="la_result" class="form-select">
                            <option value="answered">✅ رد</option>
                            <option value="no_answer">❌ لم يرد</option>
                            <option value="busy">📵 مشغول</option>
                            <option value="left_voicemail">🔉 ترك رسالة صوتية</option>
                        </select>
                    </div>

                    {{-- Reaction --}}
                    <div class="col-md-6" id="la-reaction-row">
                        <label class="form-label fw-semibold">رأي العميل</label>
                        <select id="la_reaction" class="form-select">
                            <option value="">— لم يُسأل / لا يوجد —</option>
                            <option value="satisfied">😊 راضٍ جداً</option>
                            <option value="neutral">😐 محايد</option>
                            <option value="not_satisfied">😞 غير راضٍ</option>
                            <option value="needs_revisit">🔧 يحتاج زيارة مرة أخرى</option>
                        </select>
                    </div>

                    {{-- Note --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">ملاحظات تفصيلية</label>
                        <textarea id="la_note" class="form-control" rows="4"
                            placeholder="دوّن ما قاله العميل، مشاكل أُبلغت، أي تفاصيل مفيدة..."></textarea>
                    </div>

                    {{-- Schedule next --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">جدولة متابعة قادمة</label>
                        <input type="datetime-local" id="la_scheduled" class="form-control">
                        <div class="form-text">اتركه فارغاً لو مش محتاج متابعة قادمة</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="btn-save-la" class="btn btn-primary">
                    <i class="ti tabler-device-floppy me-1"></i> حفظ المحاولة
                </button>
            </div>
        </div>
    </div>
</div>

{{-- SCHEDULE MODAL --}}
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title"><i class="ti tabler-calendar-plus me-2 text-warning"></i>جدولة موعد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label fw-semibold">موعد المتابعة القادمة <span class="text-danger">*</span></label>
                <input type="datetime-local" id="sched_datetime" class="form-control">
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="btn-save-sched" class="btn btn-warning">
                    <i class="ti tabler-calendar-check me-1"></i> جدولة
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* ── Status hero ─────────────────────────────────────── */
.followup-status-hero {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 22px;
    border-radius: 14px;
    font-size: 1.1rem;
    font-weight: 700;
    border: 1.5px solid transparent;
}
.followup-status-hero.success   { background: rgba(40,199,111,0.1);  border-color: rgba(40,199,111,0.3);  color: #28c76f; }
.followup-status-hero.warning   { background: rgba(255,159,67,0.1);  border-color: rgba(255,159,67,0.3);  color: #ff9f43; }
.followup-status-hero.danger    { background: rgba(234,84,85,0.1);   border-color: rgba(234,84,85,0.3);   color: #ea5455; }
.followup-status-hero.secondary { background: rgba(130,134,139,0.1); border-color: rgba(130,134,139,0.3); color: #82868b; }
.followup-status-hero.dark      { background: rgba(75,75,75,0.1);    border-color: rgba(75,75,75,0.3);    color: #4b4b4b; }
.followup-status-hero i { font-size: 1.5rem; }

/* ── Quick stats ─────────────────────────────────────── */
.quick-stat { padding: 16px 10px; border-radius: 12px; background: rgba(115,103,240,0.05); }
.qs-value   { font-size: 1.8rem; font-weight: 700; line-height: 1; }
.qs-label   { font-size: 0.72rem; color: #8592a3; margin-top: 4px; }

/* ── Attempts timeline ───────────────────────────────── */
.attempt-timeline-item {
    display: flex;
    gap: 16px;
    position: relative;
    padding-bottom: 24px;
}
.attempt-timeline-item:last-child { padding-bottom: 0; }
.attempt-timeline-item:last-child .atl-connector { display: none; }
.atl-connector {
    position: absolute;
    top: 36px;
    right: 18px;
    width: 2px;
    height: calc(100% - 20px);
    background: linear-gradient(to bottom, rgba(115,103,240,0.2), transparent);
}
.atl-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.1rem;
    z-index: 1;
}
.atl-content { flex: 1; }
.atl-header { margin-bottom: 6px; }
.atl-meta { font-size: 0.76rem; color: #8592a3; margin-top: 4px; }
.atl-note {
    font-size: 0.85rem;
    background: rgba(115,103,240,0.04);
    border: 1px solid rgba(115,103,240,0.1);
    border-radius: 10px;
    padding: 10px 14px;
    margin-top: 8px;
    color: #6e6b7b;
}

/* ── Contact type buttons ─────────────────────────────── */
.contact-type-btn input { display: none; }
.contact-type-btn span {
    display: inline-flex;
    align-items: center;
    padding: 8px 18px;
    border-radius: 22px;
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
</style>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const followUpId  = {{ $followUp['id'] }};
    const logModal    = new bootstrap.Modal(document.getElementById('logAttemptModal'));
    const schedModal  = new bootstrap.Modal(document.getElementById('scheduleModal'));

    // ── Open modals ──────────────────────────────────────
    ['btn-log-attempt', 'btn-log-attempt-empty'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', () => logModal.show());
    });
    document.getElementById('btn-schedule')?.addEventListener('click', () => schedModal.show());

    // ── Show reaction only if answered ───────────────────
    document.getElementById('la_result')?.addEventListener('change', function () {
        document.getElementById('la-reaction-row').style.opacity = this.value === 'answered' ? '1' : '0.4';
    });

    // ── Switch result options by type ────────────────────
    document.querySelectorAll('[name="la_type"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const r = document.getElementById('la_result');
            if (this.value === 'call') {
                r.innerHTML = `<option value="answered">✅ رد</option><option value="no_answer">❌ لم يرد</option><option value="busy">📵 مشغول</option><option value="left_voicemail">🔉 رسالة صوتية</option>`;
            } else {
                r.innerHTML = `<option value="message_sent">✅ أُرسلت الرسالة</option><option value="bounced">❌ لم تصل</option>`;
            }
        });
    });

    // ── Save attempt ─────────────────────────────────────
    document.getElementById('btn-save-la')?.addEventListener('click', function () {
        const type     = document.querySelector('[name="la_type"]:checked').value;
        const result   = document.getElementById('la_result').value;
        const reaction = document.getElementById('la_reaction').value;
        const note     = document.getElementById('la_note').value;
        const sched    = document.getElementById('la_scheduled').value;

        fetch(`/dashboard/follow-ups/${followUpId}/log-attempt`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' },
            body: JSON.stringify({ type, result, customer_reaction: reaction, note, scheduled_at: sched }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                logModal.hide();
                Swal.fire({ icon: 'success', title: 'تم', text: data.message, timer: 2000, showConfirmButton: false }).then(() => location.reload());
            }
        });
    });

    // ── Save schedule ─────────────────────────────────────
    document.getElementById('btn-save-sched')?.addEventListener('click', function () {
        const dt = document.getElementById('sched_datetime').value;
        if (!dt) { Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'يرجى تحديد موعد.' }); return; }

        fetch(`/dashboard/follow-ups/${followUpId}/schedule`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' },
            body: JSON.stringify({ scheduled_at: dt }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                schedModal.hide();
                Swal.fire({ icon: 'success', title: 'تم', text: data.message, timer: 2000, showConfirmButton: false }).then(() => location.reload());
            }
        });
    });

    // ── Mark satisfied ────────────────────────────────────
    document.getElementById('btn-mark-satisfied')?.addEventListener('click', () => {
        Swal.fire({
            title: 'تأكيد رضا العميل؟',
            text: 'سيتم إغلاق ملف المتابعة لهذا العميل.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="ti tabler-mood-happy me-1"></i> نعم، العميل راضٍ',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#28c76f',
            reverseButtons: true,
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/dashboard/follow-ups/${followUpId}/mark-satisfied`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: '🎉 ممتاز!', text: data.message, timer: 2500, showConfirmButton: false }).then(() => location.reload());
                    }
                });
            }
        });
    });
});
</script>
@endsection
