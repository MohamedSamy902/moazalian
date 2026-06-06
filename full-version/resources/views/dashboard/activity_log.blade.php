@extends('layouts/layoutMaster')
@section('title', 'سجل النشاط - Fix-It')
@section('content')

<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="ti tabler-history me-2"></i>سجل النشاط</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}">الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>سجل النشاط</span>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card glass-card border-0 mb-4">
    <div class="card-body py-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">البحث</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="ti tabler-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" id="log-search" placeholder="اسم المستخدم أو الإجراء...">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">القسم</label>
                <select class="form-select" id="log-module">
                    <option value="">كل الأقسام</option>
                    @foreach($modules as $m)<option>{{ $m }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">التاريخ</label>
                <input type="date" class="form-control" id="log-date">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-icon btn-label-secondary" id="reset-filters" title="إعادة ضبط"><i class="ti tabler-refresh"></i></button>
                <button class="btn btn-label-success flex-fill"><i class="ti tabler-file-export me-1"></i> تصدير</button>
            </div>
        </div>
    </div>
</div>

{{-- Timeline Log --}}
<div class="card glass-card overflow-hidden">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><i class="ti tabler-list-details me-2 text-primary"></i>الأحداث الأخيرة</h5>
        <span class="badge bg-label-primary">{{ count($logs) }} حدث</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover fixit-table border-top mb-0" id="log-table">
            <thead>
                <tr>
                    <th>المستخدم</th>
                    <th>الإجراء</th>
                    <th>العنصر المتأثر</th>
                    <th>القسم</th>
                    <th>IP</th>
                    <th>الوقت</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $l)
                <tr class="log-row" data-module="{{ $l['module'] }}" data-search="{{ strtolower($l['user'].' '.$l['action']) }}">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm">
                                <span class="avatar-initial rounded-circle bg-label-primary">{{ $l['avatar'] }}</span>
                            </div>
                            <div>
                                <div class="fw-semibold small">{{ $l['user'] }}</div>
                                <div class="text-muted" style="font-size:0.7rem">{{ $l['role'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-label-{{ $l['color'] }}">
                            <i class="ti {{ $l['icon'] }} me-1"></i>{{ $l['action'] }}
                        </span>
                    </td>
                    <td class="fw-semibold text-primary small">{{ $l['subject'] }}</td>
                    <td><span class="text-muted small">{{ $l['module'] }}</span></td>
                    <td><code class="small">{{ $l['ip'] }}</code></td>
                    <td class="text-muted small">{{ $l['time'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Empty State --}}
    <div id="log-empty" class="d-none text-center py-5">
        <i class="ti tabler-search-off" style="font-size:2.5rem;color:#c4c4ff"></i>
        <h6 class="text-muted mt-2">لا توجد نتائج مطابقة</h6>
    </div>
</div>
@endsection

@section('page-script')
<script>
function filterLogs() {
    const search = document.getElementById('log-search').value.toLowerCase();
    const module = document.getElementById('log-module').value;
    let visible = 0;
    document.querySelectorAll('.log-row').forEach(row => {
        const matchSearch = !search || row.dataset.search.includes(search);
        const matchModule = !module || row.dataset.module === module;
        row.style.display = (matchSearch && matchModule) ? '' : 'none';
        if (matchSearch && matchModule) visible++;
    });
    document.getElementById('log-empty').classList.toggle('d-none', visible > 0);
}
document.getElementById('log-search').addEventListener('input', filterLogs);
document.getElementById('log-module').addEventListener('change', filterLogs);
document.getElementById('reset-filters').addEventListener('click', () => {
    document.getElementById('log-search').value = '';
    document.getElementById('log-module').value = '';
    document.getElementById('log-date').value = '';
    filterLogs();
});
</script>
@endsection
