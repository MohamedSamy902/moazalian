@extends('layouts/layoutMaster')
@section('title', 'الأجهزة والفئات - Fix-It')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0"><i class="ti tabler-devices"></i> الأجهزة المشمولة بالصيانة</h4>
        <div class="fixit-breadcrumb mt-1">
            <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> الرئيسية</a>
            <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
            <span>الأجهزة والفئات</span>
        </div>
    </div>
    <a href="{{ route('dashboard.devices.create') }}" data-ajax-modal class="btn btn-primary ms-auto">
        <i class="ti tabler-plus me-1"></i> إضافة جهاز جديد
    </a>
</div>

{{-- Quick Stats --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card glass-card border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon gradient-primary flex-shrink-0"><i class="ti tabler-devices text-white ti-md"></i></div>
                <div>
                    <div class="card-label">إجمالي فئات الأجهزة</div>
                    <div class="card-value">12</div>
                    <div class="small text-muted mt-1"><span class="text-success fw-semibold">+2</span> هذا الشهر</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card glass-card border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon gradient-success flex-shrink-0"><i class="ti tabler-circle-check text-white ti-md"></i></div>
                <div>
                    <div class="card-label">أجهزة مفعلة</div>
                    <div class="card-value">10</div>
                    <div class="small text-muted mt-1"><span class="text-success fw-semibold">85%</span> نسبة التغطية</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card glass-card border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon gradient-warning flex-shrink-0"><i class="ti tabler-tags text-white ti-md"></i></div>
                <div>
                    <div class="card-label">إجمالي الماركات</div>
                    <div class="card-value">45</div>
                    <div class="small text-muted mt-1"><span class="text-warning fw-semibold">4.2</span> متوسط لكل جهاز</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card glass-card border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon gradient-info flex-shrink-0"><i class="ti tabler-chart-pie text-white ti-md"></i></div>
                <div>
                    <div class="card-label">طلبات الصيانة</div>
                    <div class="card-value">1.2k</div>
                    <div class="small text-muted mt-1"><span class="text-info fw-semibold">↑ 12%</span> زيادة في الطلبات</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card glass-card border-0 mb-4">
    <div class="card-body py-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted mb-1">بحث باسم الجهاز أو الفئة</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="ti tabler-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0 ajax-filter" id="device-search" placeholder="مثلاً: مكيف سبليت، ثلاجة...">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted mb-1">الحالة</label>
                <select class="form-select ajax-filter" id="device-status">
                    <option value="">الكل</option>
                    <option value="active">مفعل</option>
                    <option value="inactive">غير مفعل</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-icon btn-label-secondary w-100" id="reset-filters" title="إعادة ضبط">
                    <i class="ti tabler-refresh"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card glass-card">
    <div class="card-datatable table-responsive">
        <table class="table table-hover fixit-table border-top">
            <thead>
                <tr>
                    <th>صورة الجهاز</th>
                    <th>الفئة (الجهاز)</th>
                    <th>الماركات المدعومة</th>
                    <th>حالة التفعيل</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="avatar avatar-lg">
                            <div class="avatar-initial rounded bg-label-primary"><i class="ti tabler-air-conditioning"></i></div>
                        </div>
                    </td>
                    <td>
                        <span class="fw-bold d-block">مكيف سبليت</span>
                        <small class="text-muted">تبريد وتكييف</small>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge bg-label-secondary">LG</span>
                            <span class="badge bg-label-secondary">Samsung</span>
                            <span class="badge bg-label-secondary">Gree</span>
                            <span class="badge bg-label-info">+5</span>
                        </div>
                    </td>
                    <td><span class="badge bg-label-success status-badge"><i class="ti tabler-circle-check me-1"></i> مفعل</span></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-sm btn-icon btn-label-secondary" title="تعديل"><i class="ti tabler-edit"></i></button>
                            <button class="btn btn-sm btn-icon btn-label-danger" title="حذف"><i class="ti tabler-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="avatar avatar-lg">
                            <div class="avatar-initial rounded bg-label-info"><i class="ti tabler-fridge"></i></div>
                        </div>
                    </td>
                    <td>
                        <span class="fw-bold d-block">ثلاجة منزلية</span>
                        <small class="text-muted">أجهزة مطبخ</small>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge bg-label-secondary">Sharp</span>
                            <span class="badge bg-label-secondary">Toshiba</span>
                            <span class="badge bg-label-secondary">Beko</span>
                        </div>
                    </td>
                    <td><span class="badge bg-label-success status-badge"><i class="ti tabler-circle-check me-1"></i> مفعل</span></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-sm btn-icon btn-label-secondary" title="تعديل"><i class="ti tabler-edit"></i></button>
                            <button class="btn btn-sm btn-icon btn-label-danger" title="حذف"><i class="ti tabler-trash"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center border-top py-3">
        <div class="small text-muted">عرض 1 إلى 2 من أصل 12 جهاز</div>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#"><i class="ti tabler-chevron-right"></i></a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#"><i class="ti tabler-chevron-left"></i></a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection