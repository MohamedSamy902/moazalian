@extends('layouts/layoutMaster')
@section('title', 'تقييمات العملاء - Fix-It')
@section('content')

  <div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="mb-0"><i class="ti tabler-star me-2"></i>تقييمات العملاء</h4>
      <div class="fixit-breadcrumb mt-1">
        <a href="{{ route('dashboard.home') }}">الرئيسية</a>
        <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
        <span>التقييمات</span>
      </div>
    </div>
  </div>

  {{-- Overview Card --}}
  <div class="row g-4 mb-4">
    <div class="col-md-4">
      <div class="card glass-card border-0 h-100">
        <div class="card-body text-center py-4">
          <div class="rating-big">{{ $stats['avg'] }}</div>
          <div class="stars-display mb-2">
            @for ($i = 1; $i <= 5; $i++)
              <i class="ti tabler-star{{ $i <= $stats['avg'] ? '-filled' : '' }} text-warning"></i>
            @endfor
          </div>
          <div class="text-muted small">من {{ number_format($stats['total']) }} تقييم</div>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card glass-card border-0 h-100">
        <div class="card-body">
          @foreach (array_reverse(array_keys($stats['dist']), true) as $star)
            @php
              $cnt = $stats['dist'][$star];
              $pct = round(($cnt / $stats['total']) * 100);
            @endphp
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="d-flex align-items-center gap-1 flex-shrink-0" style="width:70px">
                @for ($i = 1; $i <= $star; $i++)
                  <i class="ti tabler-star-filled text-warning small"></i>
                @endfor
              </div>
              <div class="progress flex-grow-1" style="height:10px;border-radius:10px">
                <div class="progress-bar {{ $star >= 4 ? 'bg-success' : ($star === 3 ? 'bg-warning' : 'bg-danger') }}"
                  style="width:{{ $pct }}%"></div>
              </div>
              <span class="small text-muted flex-shrink-0" style="width:60px">{{ $cnt }}
                ({{ $pct }}%)</span>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  {{-- KPI Row --}}
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-md-3">
      <div class="card glass-card border-0">
        <div class="card-body text-center">
          <div class="fs-3 fw-bold text-success">{{ $stats['dist'][5] + $stats['dist'][4] }}</div>
          <div class="small text-muted mt-1">تقييمات إيجابية (4-5 ⭐)</div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card glass-card border-0">
        <div class="card-body text-center">
          <div class="fs-3 fw-bold text-warning">{{ $stats['dist'][3] }}</div>
          <div class="small text-muted mt-1">تقييمات محايدة (3 ⭐)</div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card glass-card border-0">
        <div class="card-body text-center">
          <div class="fs-3 fw-bold text-danger">{{ $stats['dist'][2] + $stats['dist'][1] }}</div>
          <div class="small text-muted mt-1">تقييمات سلبية (1-2 ⭐)</div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card glass-card border-0">
        <div class="card-body text-center">
          <div class="fs-3 fw-bold text-primary">{{ $stats['avg'] }}/5</div>
          <div class="small text-muted mt-1">متوسط التقييم العام</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Ratings Table --}}
  <div class="card glass-card overflow-hidden">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0"><i class="ti tabler-messages me-2 text-primary"></i>تفاصيل التقييمات</h5>
      <div class="d-flex gap-2">
        <select class="form-select form-select-sm w-auto">
          <option>كل التقييمات</option>
          <option>5 نجوم فقط</option>
          <option>سلبية فقط</option>
        </select>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-hover fixit-table border-top mb-0">
        <thead>
          <tr>
            <th>العميل</th>
            <th>الأوردر</th>
            <th>الجهاز</th>
            <th>المركز/الفني</th>
            <th>التقييم</th>
            <th>التعليق</th>
            <th>التاريخ</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($ratings as $r)
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="avatar avatar-sm">
                    <span
                      class="avatar-initial rounded-circle bg-label-primary">{{ mb_substr($r['customer'], 0, 1) }}</span>
                  </div>
                  <span class="fw-semibold small">{{ $r['customer'] }}</span>
                </div>
              </td>
              <td><span class="text-primary fw-semibold small">{{ $r['order_id'] }}</span></td>
              <td class="small text-muted">{{ $r['device'] }}</td>
              <td>
                <div class="small fw-semibold">{{ $r['center'] }}</div>
                <div class="small text-muted">{{ $r['technician'] }}</div>
              </td>
              <td>
                <div class="d-flex gap-1">
                  @for ($i = 1; $i <= 5; $i++)
                    <i class="ti tabler-star{{ $i <= $r['score'] ? '-filled' : '' }} text-warning small"></i>
                  @endfor
                </div>
                <span
                  class="badge bg-label-{{ $r['score'] >= 4 ? 'success' : ($r['score'] === 3 ? 'warning' : 'danger') }} mt-1">
                  {{ $r['score'] }}/5
                </span>
              </td>
              <td><span
                  class="small text-muted">{{ mb_substr($r['comment'], 0, 50) }}{{ mb_strlen($r['comment']) > 50 ? '...' : '' }}</span>
              </td>
              <td class="small text-muted">{{ $r['date'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <style>
    .rating-big {
      font-size: 4rem;
      font-weight: 800;
      color: #7367f0;
      line-height: 1
    }

    .stars-display .ti {
      font-size: 1.4rem
    }
  </style>
@endsection
