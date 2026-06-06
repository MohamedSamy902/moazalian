@extends('layouts/layoutMaster')
@section('title', 'الإشعارات - Fix-It')
@section('content')

<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-bell me-2"></i>الإشعارات</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}">الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>الإشعارات</span>
        </div>
    </div>
    <div class="d-flex gap-2 align-items-center">
        @if($unread > 0)
        <span class="badge bg-danger rounded-pill">{{ $unread }} غير مقروءة</span>
        @endif
        <button class="btn btn-label-secondary btn-sm" id="mark-all-btn">
            <i class="ti tabler-checks me-1"></i> تعليم الكل كمقروء
        </button>
    </div>
</div>

{{-- Filter Tabs --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    <button class="btn btn-primary btn-sm notif-filter active" data-filter="all">الكل ({{ count($notifications) }})</button>
    <button class="btn btn-label-secondary btn-sm notif-filter" data-filter="unread">غير مقروءة ({{ $unread }})</button>
    <button class="btn btn-label-secondary btn-sm notif-filter" data-filter="order">الطلبات</button>
    <button class="btn btn-label-secondary btn-sm notif-filter" data-filter="settlement">التسويات</button>
    <button class="btn btn-label-secondary btn-sm notif-filter" data-filter="followup">المتابعات</button>
</div>

<div class="d-flex flex-column gap-3" id="notif-list">
    @foreach($notifications as $n)
    <div class="notif-card card glass-card border-0 {{ !$n['is_read'] ? 'notif-unread' : '' }}"
         data-type="{{ $n['type'] }}" data-read="{{ $n['is_read'] ? 'read':'unread' }}">
        <div class="card-body d-flex align-items-start gap-3 py-3">
            <div class="notif-icon-wrap bg-label-{{ $n['color'] }} flex-shrink-0">
                <i class="ti {{ $n['icon'] }} text-{{ $n['color'] }}"></i>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-semibold {{ !$n['is_read'] ? 'text-heading' : '' }}">{{ $n['title'] }}</div>
                        <div class="small text-muted mt-1">{{ $n['body'] }}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2 ms-3 flex-shrink-0">
                        @if(!$n['is_read'])
                        <span class="badge-dot bg-primary"></span>
                        @endif
                        <span class="small text-muted text-nowrap">{{ $n['time'] }}</span>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ $n['url'] }}" class="btn btn-xs btn-label-{{ $n['color'] }}">
                        <i class="ti tabler-external-link me-1" style="font-size:0.7rem"></i>عرض التفاصيل
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Empty State --}}
<div id="empty-notif" class="d-none text-center py-5">
    <div class="empty-state-icon mb-3"><i class="ti tabler-bell-off" style="font-size:3rem;color:#b8b8ff"></i></div>
    <h5 class="text-muted">لا توجد إشعارات</h5>
    <p class="text-muted small">لا توجد إشعارات تطابق الفلتر المحدد</p>
</div>

<style>
.notif-icon-wrap{width:44px;height:44px;min-width:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;}
.notif-unread{border-right:3px solid #7367f0!important;}
.badge-dot{width:8px;height:8px;border-radius:50%;display:inline-block;}
.btn-xs{font-size:0.72rem;padding:2px 8px;}
.notif-filter.active{background:#7367f0!important;color:#fff!important;border-color:#7367f0!important;}
</style>
@endsection

@section('page-script')
<script>
document.querySelectorAll('.notif-filter').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.notif-filter').forEach(b => b.classList.remove('active','btn-primary'));
        document.querySelectorAll('.notif-filter').forEach(b => { b.classList.add('btn-label-secondary'); });
        this.classList.add('active','btn-primary');
        this.classList.remove('btn-label-secondary');

        const filter = this.dataset.filter;
        let visible = 0;
        document.querySelectorAll('.notif-card').forEach(card => {
            let show = filter === 'all'
                || (filter === 'unread' && card.dataset.read === 'unread')
                || card.dataset.type === filter;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        document.getElementById('empty-notif').classList.toggle('d-none', visible > 0);
    });
});

document.getElementById('mark-all-btn')?.addEventListener('click', function() {
    fetch("{{ route('dashboard.notifications.mark-all-read') }}", {
        method:'POST', headers:{'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type':'application/json'}
    }).then(() => {
        document.querySelectorAll('.notif-unread').forEach(el => el.classList.remove('notif-unread'));
        document.querySelectorAll('.badge-dot').forEach(el => el.remove());
        this.closest('div')?.querySelector('.badge.bg-danger')?.remove();
        Swal.fire({icon:'success',title:'تم',text:'تم تعليم جميع الإشعارات كمقروءة',timer:1500,showConfirmButton:false});
    });
});
</script>
@endsection
