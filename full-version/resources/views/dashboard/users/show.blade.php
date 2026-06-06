@extends('layouts/layoutMaster')
@section('title', 'ملف العميل - Fix-It')
@section('content')
<div class="row">
    <!-- User Sidebar -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card glass-card h-100">
            <div class="card-body">
                <div class="user-avatar-section text-center mb-4">
                    <div class="avatar avatar-xl mb-3 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-primary" style="font-size: 2.5rem;">{{ mb_substr($user['name'], 0, 1) }}</span>
                    </div>
                    <h4 class="mb-1">{{ $user['name'] }}</h4>
                    <span class="badge bg-label-secondary">عميل مميز</span>
                </div>
                <div class="d-flex justify-content-around border-top border-bottom py-3 mb-4">
                    <div class="text-center">
                        <h5 class="mb-0">{{ $user['orders_count'] }}</h5>
                        <small class="text-muted">طلب</small>
                    </div>
                    <div class="text-center">
                        <h5 class="mb-0">{{ number_format($user['total_spent']) }}</h5>
                        <small class="text-muted">EGP</small>
                    </div>
                    <div class="text-center">
                        <h5 class="mb-0">4.5</h5>
                        <small class="text-muted">تقييم</small>
                    </div>
                </div>
                <h6 class="text-muted text-uppercase small mb-3">بيانات التواصل</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-3 d-flex align-items-center">
                        <i class="ti tabler-phone me-2 text-primary"></i>
                        <span>{{ $user['phone'] }}</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="ti tabler-mail me-2 text-primary"></i>
                        <span>{{ $user['email'] }}</span>
                    </li>
                    <li class="mb-0 d-flex align-items-center">
                        <i class="ti tabler-calendar me-2 text-primary"></i>
                        <span>انضم في: {{ $user['joined_at'] }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- User Content -->
    <div class="col-xl-8 col-lg-7">
        <!-- إدارة العناوين (Multiple Addresses) -->
        <div class="card glass-card mb-4">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">عناوين العميل</h5>
                <button class="btn btn-sm btn-label-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal"><i class="ti tabler-plus me-1"></i> إضافة عنوان جديد</button>
            </div>
            <div class="card-body pt-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="address-card border rounded p-3 position-relative border-primary bg-label-primary bg-opacity-10">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="mb-0"><i class="ti tabler-home me-1"></i> المنزل (الرئيسي)</h6>
                                <span class="badge bg-primary">افتراضي</span>
                            </div>
                            <p class="small text-muted mb-2">شارع التحرير، الدقي، الجيزة. بجوار سينما التحرير.</p>
                            <button class="btn btn-xs btn-outline-primary"><i class="ti tabler-map-2 me-1"></i> عرض على الخريطة</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="address-card border rounded p-3 position-relative">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="mb-0"><i class="ti tabler-building me-1"></i> العمل</h6>
                            </div>
                            <p class="small text-muted mb-2">مبنى القرية الذكية، طريق الإسكندرية الصحراوي.</p>
                            <button class="btn btn-xs btn-outline-secondary"><i class="ti tabler-map-2 me-1"></i> عرض على الخريطة</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- سجل الطلبات -->
        <div class="card glass-card">
            <h5 class="card-header border-bottom">سجل الطلبات الأخير</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>الجهاز</th>
                            <th>المبلغ</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user['recent_orders'] as $order)
                        <tr>
                            <td><span class="fw-bold">{{ $order['id'] }}</span></td>
                            <td>{{ $order['device'] }}</td>
                            <td>{{ number_format($order['amount']) }} EGP</td>
                            <td>{{ $order['date'] }}</td>
                            <td><span class="badge bg-label-success">مكتمل</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal إضافة عنوان مع خريطة جوجل (Placeholder) -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">إضافة عنوان جديد للعميل</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-12">
                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px; border: 2px dashed #ddd;">
                    <div class="text-center">
                        <i class="ti tabler-map-pin text-primary display-4 mb-2"></i>
                        <h5>خريطة جوجل التفاعلية</h5>
                        <p class="text-muted">هنا سيتمكن العميل من تحديد اللوكيشن بدقة</p>
                        <button class="btn btn-sm btn-primary">فتح الخريطة</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">اسم العنوان (مثلاً: المنزل، العمل)</label>
                <input type="text" class="form-control" placeholder="اسم العنوان">
            </div>
            <div class="col-md-6">
                <label class="form-label">رقم الهاتف للعنوان</label>
                <input type="text" class="form-control" placeholder="رقم الهاتف">
            </div>
            <div class="col-12">
                <label class="form-label">العنوان التفصيلي</label>
                <textarea class="form-control" rows="2" placeholder="الشارع، رقم المبنى، الشقة..."></textarea>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="button" class="btn btn-primary">حفظ العنوان</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-style')
<style>
.address-card { transition: all 0.2s ease-in-out; cursor: pointer; }
.address-card:hover { transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
</style>
@endsection
