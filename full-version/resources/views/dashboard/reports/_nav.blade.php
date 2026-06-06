{{-- Shared Navigation for Report Sub-Pages --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
  <a href="{{ route('dashboard.reports.overview') }}"
     class="btn btn-sm {{ $active === 'overview' ? 'btn-primary' : 'btn-label-secondary' }}">
    <i class="ti tabler-chart-line me-1"></i> نظرة عامة
  </a>
  <a href="{{ route('dashboard.reports.centers') }}"
     class="btn btn-sm {{ $active === 'centers' ? 'btn-primary' : 'btn-label-secondary' }}">
    <i class="ti tabler-building-store me-1"></i> المراكز
  </a>
  <a href="{{ route('dashboard.reports.technicians') }}"
     class="btn btn-sm {{ $active === 'technicians' ? 'btn-primary' : 'btn-label-secondary' }}">
    <i class="ti tabler-tools me-1"></i> الفنيون
  </a>
  <a href="{{ route('dashboard.reports.hr') }}"
     class="btn btn-sm {{ $active === 'hr' ? 'btn-primary' : 'btn-label-secondary' }}">
    <i class="ti tabler-users me-1"></i> الموارد البشرية
  </a>
  <a href="{{ route('dashboard.reports.finance') }}"
     class="btn btn-sm {{ $active === 'finance' ? 'btn-primary' : 'btn-label-secondary' }}">
    <i class="ti tabler-transfer-in me-1"></i> الحسابات
  </a>
</div>
