@extends('layouts/layoutMaster')
@section('title', 'ملف الفني - Fix-It')
@section('content')
<div class="row">
    <!-- User Sidebar -->
    <div class="col-xl-4 col-lg-5 col-md-5 mb-4">
        <div class="card glass-card h-100">
            <div class="card-body">
                <div class="user-avatar-section text-center mb-4">
                    <div class="avatar avatar-xl mb-3 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-info" style="font-size: 2.5rem;">{{ mb_substr($tech['name'], 0, 1) }}</span>
                    </div>
                    <h4 class="mb-1">{{ $tech['name'] }}</h4>
                    <span class="badge bg-label-warning">{{ $tech['center'] }}</span>
                </div>
                <div class="d-flex justify-content-around border-top border-bottom py-3 mb-4">
                    <div class="text-center">
                        <h5 class="mb-0">{{ $tech['total_orders'] }}</h5>
                        <small class="text-muted">مهمة</small>
                    </div>
                    <div class="text-center">
                        <h5 class="mb-0">{{ $tech['rating'] }}</h5>
                        <small class="text-muted">التقييم</small>
                    </div>
                    <div class="text-center">
                        <h5 class="mb-0">{{ $tech['success_rate'] }}</h5>
                        <small class="text-muted">النجاح</small>
                    </div>
                </div>
                <h6 class="text-muted text-uppercase small mb-3">بيانات الفني</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-3 d-flex align-items-center">
                        <i class="ti tabler-phone me-2 text-primary"></i>
                        <span>{{ $tech['phone'] }}</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="ti tabler-mail me-2 text-primary"></i>
                        <span>{{ $tech['email'] }}</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="ti tabler-tool me-2 text-primary"></i>
                        <span class="badge bg-label-success">{{ $tech['specialty'] }}</span>
                    </li>
                    <li class="mb-0 d-flex align-items-center">
                        <i class="ti tabler-calendar me-2 text-primary"></i>
                        <span>تاريخ الانضمام: {{ $tech['joined_at'] }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- User Content -->
    <div class="col-xl-8 col-lg-7">
        <!-- تقييمات العملاء (Detailed Ratings) -->
        <div class="card glass-card mb-4">
            <h5 class="card-header border-bottom">تقييمات العملاء والتعليقات</h5>
            <div class="card-body pt-4">
                <div class="d-flex align-items-start mb-4">
                    <div class="avatar avatar-md me-3">
                        <span class="avatar-initial rounded-circle bg-label-primary text-uppercase">أ</span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between mb-1">
                            <h6 class="mb-0">أحمد محمد</h6>
                            <small class="text-muted">منذ يومين</small>
                        </div>
                        <div class="mb-2">
                            <i class="ti tabler-star-filled text-warning"></i>
                            <i class="ti tabler-star-filled text-warning"></i>
                            <i class="ti tabler-star-filled text-warning"></i>
                            <i class="ti tabler-star-filled text-warning"></i>
                            <i class="ti tabler-star-filled text-warning"></i>
                        </div>
                        <p class="mb-0 text-muted small">فني ممتاز ومحترف جداً، قام بإصلاح التكييف في وقت قياسي وبجودة عالية.</p>
                    </div>
                </div>
                <div class="d-flex align-items-start">
                    <div class="avatar avatar-md me-3">
                        <span class="avatar-initial rounded-circle bg-label-secondary text-uppercase">س</span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between mb-1">
                            <h6 class="mb-0">سارة علي</h6>
                            <small class="text-muted">منذ أسبوع</small>
                        </div>
                        <div class="mb-2">
                            <i class="ti tabler-star-filled text-warning"></i>
                            <i class="ti tabler-star-filled text-warning"></i>
                            <i class="ti tabler-star-filled text-warning"></i>
                            <i class="ti tabler-star-filled text-warning"></i>
                            <i class="ti tabler-star text-muted"></i>
                        </div>
                        <p class="mb-0 text-muted small">مهذب وملتزم بالمواعيد، الخدمة كانت جيدة جداً.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول المواعيد (Technician Schedule) -->
        <div class="card glass-card mb-4">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">جدول المواعيد - اليوم</h5>
                <div class="btn-group">
                   <button class="btn btn-xs btn-label-secondary"><i class="ti tabler-chevron-right"></i></button>
                   <button class="btn btn-xs btn-label-primary fw-bold">اليوم، 16 مايو</button>
                   <button class="btn btn-xs btn-label-secondary"><i class="ti tabler-chevron-left"></i></button>
                </div>
            </div>
            <div class="card-body pt-4">
                <div class="schedule-container position-relative">
                   {{-- Timeline Hours Labels --}}
                   <div class="d-flex justify-content-between text-muted small mb-2 border-bottom pb-1" style="font-size: 0.65rem;">
                      <span>09:00 ص</span>
                      <span>12:00 م</span>
                      <span>03:00 م</span>
                      <span>06:00 م</span>
                      <span>09:00 م</span>
                   </div>
                   
                   {{-- Visual Timeline --}}
                   <div class="progress rounded-pill bg-light position-relative overflow-visible mb-4" style="height: 35px;">
                      {{-- Slot 1: Busy --}}
                      <div class="progress-bar bg-warning rounded-pill position-absolute" 
                           role="progressbar" style="width: 25%; left: 10%; height: 25px; top: 5px; cursor: pointer;" 
                           title="طلب #ORD-2035 - حي العليا"
                           data-bs-toggle="tooltip">
                           <span class="small fw-bold px-2">#ORD-2035</span>
                      </div>
                      
                      {{-- Slot 2: Busy --}}
                      <div class="progress-bar bg-info rounded-pill position-absolute" 
                           role="progressbar" style="width: 20%; left: 55%; height: 25px; top: 5px; cursor: pointer;" 
                           title="طلب #ORD-2039 - حي النخيل"
                           data-bs-toggle="tooltip">
                           <span class="small fw-bold px-2">#ORD-2039</span>
                      </div>
                      
                      {{-- Current Time Marker --}}
                      <div class="position-absolute h-100 border-start border-primary border-2 shadow-sm" style="left: 45%; top: 0; z-index: 5;">
                         <div class="position-absolute top-0 start-50 translate-middle-x bg-primary rounded-circle" style="width: 8px; height: 8px; margin-top: -4px;"></div>
                         <span class="position-absolute bottom-100 start-50 translate-middle-x badge bg-primary small mb-1" style="font-size: 0.6rem;">الآن</span>
                      </div>
                   </div>
                   
                   <div class="d-flex flex-wrap gap-3 mt-2">
                      <div class="d-flex align-items-center gap-2 small">
                         <span class="badge badge-dot bg-warning"></span>
                         <span class="text-muted">مهمة مجدولة</span>
                      </div>
                      <div class="d-flex align-items-center gap-2 small">
                         <span class="badge badge-dot bg-info"></span>
                         <span class="text-muted">مهمة قيد التنفيذ</span>
                      </div>
                      <div class="d-flex align-items-center gap-2 small">
                         <span class="badge badge-dot bg-light border"></span>
                         <span class="text-muted">وقت متاح</span>
                      </div>
                   </div>
                </div>
            </div>
        </div>

        <!-- سجل المهام -->
        <div class="card glass-card">
            <h5 class="card-header border-bottom">أخر المهام المنجزة</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>الجهاز</th>
                            <th>المبلغ</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tech['recent_orders'] as $order)
                        <tr>
                            <td><span class="fw-bold">{{ $order['id'] }}</span></td>
                            <td>{{ $order['device'] }}</td>
                            <td>{{ number_format($order['amount']) }} EGP</td>
                            <td><span class="badge bg-label-success">مكتمل</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
