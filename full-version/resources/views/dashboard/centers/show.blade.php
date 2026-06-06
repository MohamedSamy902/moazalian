@extends('layouts/layoutMaster')
@section('title', __('page.centers.details') . ' - Fix-It')
@section('content')
<div class="row g-4">
    <!-- Header -->
    <div class="col-12">
        <div class="card glass-card border-0 shadow-none bg-transparent">
            <div class="card-body p-0">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl me-3 shadow-sm bg-white rounded-circle p-1">
                            <span class="avatar-initial rounded-circle bg-label-warning"><i class="ti tabler-building-store ti-lg"></i></span>
                        </div>
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $center['name'] }}</h3>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-label-success"><i class="ti tabler-circle-check me-1"></i> {{ __('page.centers.verified') }}</span>
                                <span class="text-muted"><i class="ti tabler-map-pin me-1"></i> {{ $center['location'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-label-secondary"><i class="ti tabler-edit me-1"></i> {{ __('page.centers.edit_data') }}</button>
                        <button class="btn btn-primary"><i class="ti tabler-phone me-1"></i> {{ __('page.centers.direct_call') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="col-lg-3 col-sm-6">
        <div class="card glass-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="badge rounded-pill bg-label-primary p-2 me-3"><i class="ti tabler-users-group ti-sm"></i></div>
                    <h6 class="mb-0">{{ __('stat.total_technicians') }}</h6>
                </div>
                <h4 class="mb-1 fw-bold">{{ number_format($center['total_technicians']) }}</h4>
                <p class="mb-0 text-success small"><i class="ti tabler-trending-up me-1"></i> +2 {{ __('common.this_month') }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card glass-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="badge rounded-pill bg-label-success p-2 me-3"><i class="ti tabler-briefcase ti-sm"></i></div>
                    <h6 class="mb-0">{{ __('stat.total_orders') }}</h6>
                </div>
                <h4 class="mb-1 fw-bold">{{ number_format($center['total_orders']) }}</h4>
                <p class="mb-0 text-muted small">{{ __('stat.completion_rate') }}: 94%</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card glass-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="badge rounded-pill bg-label-info p-2 me-3"><i class="ti tabler-star-filled ti-sm"></i></div>
                    <h6 class="mb-0">{{ __('stat.avg_rating') }}</h6>
                </div>
                <h4 class="mb-1 fw-bold">{{ $center['rating'] }} / 5.0</h4>
                <div class="text-warning">
                    <i class="ti tabler-star-filled small"></i><i class="ti tabler-star-filled small"></i>
                    <i class="ti tabler-star-filled small"></i><i class="ti tabler-star-filled small"></i>
                    <i class="ti tabler-star-half-filled small"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card glass-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="badge rounded-pill bg-label-danger p-2 me-3"><i class="ti tabler-coin ti-sm"></i></div>
                    <h6 class="mb-0">{{ __('stat.total_revenue') }}</h6>
                </div>
                <h4 class="mb-1 fw-bold text-success">{{ number_format(154200) }} <small>EGP</small></h4>
                <p class="mb-0 text-muted small">{{ __('page.centers.net_profit') }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-xl-8">
        <!-- Technicians Table -->
        <div class="card glass-card border-0 shadow-sm mb-4">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ __('page.centers.working_technicians') }}</h5>
                <button class="btn btn-sm btn-label-primary"><i class="ti tabler-plus me-1"></i> {{ __('page.centers.add_technician') }}</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover border-top">
                    <thead>
                        <tr>
                            <th>{{ app()->getLocale()==='ar' ? 'الفني' : 'Technician' }}</th>
                            <th>{{ __('page.centers.specialty') }}</th>
                            <th>{{ app()->getLocale()==='ar' ? 'التقييم' : 'Rating' }}</th>
                            <th>{{ __('page.centers.tasks') }}</th>
                            <th>{{ app()->getLocale()==='ar' ? 'الحالة' : 'Status' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><div class="d-flex align-items-center"><div class="avatar avatar-sm me-2"><span class="avatar-initial rounded-circle bg-label-info">ي</span></div><span class="fw-bold">ياسر فهد</span></div></td>
                            <td>{{ app()->getLocale()==='ar' ? 'مكيفات' : 'AC Units' }}</td>
                            <td><i class="ti tabler-star-filled text-warning small"></i> 4.9</td>
                            <td><span class="badge bg-label-secondary">124</span></td>
                            <td><span class="badge bg-label-success">{{ __('common.active') }}</span></td>
                        </tr>
                        <tr>
                            <td><div class="d-flex align-items-center"><div class="avatar avatar-sm me-2"><span class="avatar-initial rounded-circle bg-label-info">م</span></div><span class="fw-bold">محمد كمال</span></div></td>
                            <td>{{ app()->getLocale()==='ar' ? 'ثلاجات' : 'Refrigerators' }}</td>
                            <td><i class="ti tabler-star-filled text-warning small"></i> 4.7</td>
                            <td><span class="badge bg-label-secondary">86</span></td>
                            <td><span class="badge bg-label-warning">{{ __('common.on_mission') }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer border-top py-2 d-flex justify-content-center">
                <nav><ul class="pagination pagination-sm mb-0"><li class="page-item active"><a class="page-link" href="#">1</a></li><li class="page-item"><a class="page-link" href="#">2</a></li></ul></nav>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card glass-card border-0 shadow-sm">
            <h5 class="card-header border-bottom">{{ __('page.centers.recent_orders') }}</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ app()->getLocale()==='ar' ? 'رقم الطلب' : 'Order #' }}</th>
                            <th>{{ __('page.orders.customer') }}</th>
                            <th>{{ __('page.orders.device') }}</th>
                            <th>{{ __('page.orders.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($center['recent_orders'] as $order)
                        <tr>
                            <td><span class="fw-bold text-primary">{{ $order['id'] }}</span></td>
                            <td>{{ $order['customer'] }}</td>
                            <td>{{ $order['device'] ?? (app()->getLocale()==='ar' ? 'مكيف سبليت LG' : 'LG Split AC') }}</td>
                            <td>{{ number_format($order['amount']) }} EGP</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-xl-4">
        <!-- Wallet / Financial Status -->
        <div class="card glass-card border-0 shadow-sm mb-4" style="border-right:3px solid #28c76f!important">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="ti tabler-wallet me-2 text-success"></i>{{ app()->getLocale()==='ar' ? 'المحفظة المالية' : 'Financial Wallet' }}</h5>
            </div>
            <div class="card-body pt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small fw-semibold"><i class="ti tabler-trending-up text-success me-1"></i> {{ app()->getLocale()==='ar' ? 'مستحق للمركز' : 'Due to Center' }}</span>
                    <span class="fw-bold fs-5 text-success">+ 4,200 EGP</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small fw-semibold"><i class="ti tabler-percentage text-danger me-1"></i> {{ app()->getLocale()==='ar' ? 'عمولة المنصة' : 'Platform Fee' }}</span>
                    <span class="fw-bold fs-5 text-danger">- 1,150 EGP</span>
                </div>
                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold">{{ app()->getLocale()==='ar' ? 'الصافي' : 'Net Balance' }}</span>
                    <span class="fw-800 fs-4 text-primary" style="letter-spacing: 0.5px;">3,050 EGP</span>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-success flex-grow-1"><i class="ti tabler-cash me-1"></i> {{ app()->getLocale()==='ar' ? 'إنشاء تسوية' : 'Settle' }}</button>
                    <a href="{{ route('dashboard.settlements.index') }}" class="btn btn-label-secondary flex-grow-1"><i class="ti tabler-history me-1"></i> {{ app()->getLocale()==='ar' ? 'السجل' : 'History' }}</a>
                </div>
            </div>
        </div>

        <!-- Map -->
        <div class="card glass-card border-0 shadow-sm mb-4">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0"><i class="ti tabler-map-2 me-1"></i> {{ __('page.centers.map') }}</h5>
            </div>
            <div class="card-body pt-4 p-0">
                <div class="bg-light d-flex align-items-center justify-content-center" style="height:250px;">
                    <div class="text-center">
                        <i class="ti tabler-map-pin text-danger display-4"></i>
                        <p class="text-muted mt-2">{{ app()->getLocale()==='ar' ? 'خريطة تفاعلية لموقع المركز' : 'Interactive map of center location' }}</p>
                    </div>
                </div>
                <div class="p-3 border-top"><p class="mb-0 small text-muted">{{ $center['location'] }}</p></div>
            </div>
        </div>

        <!-- Owner Info -->
        <div class="card glass-card border-0 shadow-sm mb-4" style="border-right:3px solid #7367f0!important">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="ti tabler-shield-lock me-2 text-primary"></i>{{ __('page.centers.owner_info') }}</h5>
                <span class="badge bg-label-danger" style="font-size:0.65rem">{{ __('page.centers.admin_only') }}</span>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label small text-muted mb-1">{{ __('page.centers.owner') }}</label>
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar avatar-sm"><span class="avatar-initial rounded-circle bg-label-primary">{{ mb_substr($center['owner_name'],0,1) }}</span></div>
                        <span class="fw-semibold">{{ $center['owner_name'] }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small text-muted mb-1">{{ __('page.settings.phone') }}</label>
                    <a href="tel:{{ $center['owner_phone'] }}" class="d-flex align-items-center gap-1 fw-semibold text-primary" style="font-size:0.9rem">
                        <i class="ti tabler-phone small"></i> {{ $center['owner_phone'] }}
                    </a>
                </div>
                <div class="mb-3">
                    <label class="form-label small text-muted mb-1">{{ __('page.settings.email') ?? 'Email' }}</label>
                    <span class="fw-semibold small">{{ $center['owner_email'] }}</span>
                </div>
                <div class="mb-3">
                    <label class="form-label small text-muted mb-1">{{ __('page.centers.national_id') }}</label>
                    <code class="fw-semibold">{{ $center['owner_national_id'] }}</code>
                </div>
                <hr class="my-3">
                <div class="mb-2">
                    <label class="form-label small text-muted mb-1">{{ __('page.centers.commission') }}</label>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-label-warning fw-bold fs-6" id="commission-display">{{ $center['commission_rate'] }}%</span>
                        <button class="btn btn-xs btn-icon btn-label-secondary" id="btn-edit-commission" style="padding:2px 6px;font-size:0.75rem">
                            <i class="ti tabler-edit"></i>
                        </button>
                    </div>
                    <div class="d-none mt-2" id="commission-edit-form">
                        <div class="input-group input-group-sm" style="max-width:160px">
                            <input type="number" class="form-control" id="commission-input" value="{{ $center['commission_rate'] }}" min="0" max="50" step="0.5">
                            <span class="input-group-text">%</span>
                        </div>
                        <div class="d-flex gap-1 mt-1">
                            <button class="btn btn-xs btn-success" id="btn-save-commission" style="font-size:0.75rem;padding:2px 10px"><i class="ti tabler-check me-1"></i>{{ __('common.save') }}</button>
                            <button class="btn btn-xs btn-label-secondary" id="btn-cancel-commission" style="font-size:0.75rem;padding:2px 10px">{{ __('common.cancel') }}</button>
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted mb-1">{{ __('page.centers.settlement') }}</label>
                    <span class="fw-semibold">{{ $center['settlement_period'] }}</span>
                </div>
                <div class="mb-0">
                    <label class="form-label small text-muted mb-1">{{ __('page.centers.join_date') }}</label>
                    <span class="fw-semibold">{{ $center['join_date'] }}</span>
                </div>
            </div>
        </div>

        <!-- Contract -->
        <div class="card glass-card border-0 shadow-sm" style="border-right:3px solid #ff9f43!important">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="ti tabler-file-contract me-2 text-warning"></i>{{ __('page.centers.contract_doc') }}</h5>
                <span class="badge bg-label-danger" style="font-size:0.65rem">{{ __('page.centers.admin_only') }}</span>
            </div>
            <div class="card-body">
                @if($center['contract_file'])
                    <div class="contract-preview mb-3 position-relative" style="border-radius:12px;overflow:hidden;border:2px solid #7367f040">
                        <img src="{{ asset('storage/'.$center['contract_file']) }}" class="img-fluid w-100" alt="{{ __('page.centers.contract_doc') }}">
                        <div class="position-absolute top-0 end-0 m-2">
                            <a href="{{ asset('storage/'.$center['contract_file']) }}" target="_blank" class="btn btn-sm btn-primary"><i class="ti tabler-zoom-in"></i></a>
                        </div>
                    </div>
                @else
                    <div class="contract-upload-area mb-3" id="contract-drop-zone"
                         style="border:2px dashed rgba(115,103,240,0.35);border-radius:12px;padding:24px;text-align:center;cursor:pointer;transition:all 0.2s"
                         onclick="document.getElementById('contract-input').click()">
                        <i class="ti tabler-upload" style="font-size:2rem;color:#b0aee8"></i>
                        <div class="fw-semibold mt-2" style="color:#7367f0">{{ __('page.centers.click_upload_contract') }}</div>
                        <div class="small text-muted mt-1">{{ __('page.centers.upload_hint') }}</div>
                    </div>
                    <img id="contract-preview-img" src="" class="img-fluid w-100 d-none mb-3" style="border-radius:10px;border:1.5px solid #7367f040">
                    <input type="file" id="contract-input" class="d-none" accept="image/*,.pdf" onchange="previewContract(this)">
                @endif
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-muted">{{ __('page.centers.valid_until') }}</div>
                        <div class="fw-semibold {{ strtotime($center['contract_expiry']) < time() ? 'text-danger' : 'text-success' }}">
                            {{ $center['contract_expiry'] }}
                            @if(strtotime($center['contract_expiry']) < time())
                                <span class="badge bg-danger ms-1">{{ __('page.centers.expired') }}</span>
                            @elseif(strtotime($center['contract_expiry']) - time() < 30*86400)
                                <span class="badge bg-warning ms-1">{{ __('page.centers.expiring_soon') }}</span>
                            @endif
                        </div>
                    </div>
                    @if($center['contract_file'])
                    <button class="btn btn-sm btn-label-warning"><i class="ti tabler-refresh me-1"></i>{{ __('page.centers.update') }}</button>
                    @else
                    <button class="btn btn-sm btn-primary" onclick="document.getElementById('contract-input').click()"><i class="ti tabler-upload me-1"></i>{{ __('page.centers.upload_contract') }}</button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card glass-card border-0 shadow-sm mt-4">
            <h5 class="card-header border-bottom">{{ __('page.centers.contact') }}</h5>
            <div class="card-body pt-3">
                <div class="mb-3">
                    <label class="form-label small text-muted">{{ __('page.settings.phone') }}</label>
                    <h6 class="mb-0">{{ $center['phone'] }}</h6>
                </div>
                <hr>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-primary flex-grow-1"><i class="ti tabler-file-text me-1"></i> {{ __('page.centers.trade_reg') }}</button>
                    <button class="btn btn-sm btn-outline-primary flex-grow-1"><i class="ti tabler-certificate me-1"></i> {{ __('page.centers.certificates') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
function previewContract(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('contract-preview-img');
                img.src = e.target.result; img.classList.remove('d-none');
                document.getElementById('contract-drop-zone').classList.add('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('contract-drop-zone').innerHTML =
                `<i class='ti tabler-file-type-pdf' style='font-size:2rem;color:#ea5455'></i><div class='fw-semibold mt-2 text-success'>${file.name}</div>`;
        }
    }
}
const dropZone = document.getElementById('contract-drop-zone');
if (dropZone) {
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.borderColor='#7367f0'; dropZone.style.background='rgba(115,103,240,0.06)'; });
    dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor='rgba(115,103,240,0.35)'; dropZone.style.background=''; });
    dropZone.addEventListener('drop', e => {
        e.preventDefault(); const dt = new DataTransfer(); dt.items.add(e.dataTransfer.files[0]);
        document.getElementById('contract-input').files = dt.files;
        previewContract(document.getElementById('contract-input'));
    });
}
const btnEdit=document.getElementById('btn-edit-commission'), editForm=document.getElementById('commission-edit-form'),
      btnSave=document.getElementById('btn-save-commission'), btnCancel=document.getElementById('btn-cancel-commission'),
      display=document.getElementById('commission-display'), input=document.getElementById('commission-input');
if (btnEdit) {
    btnEdit.addEventListener('click', () => { editForm.classList.remove('d-none'); btnEdit.classList.add('d-none'); input.focus(); });
    btnCancel.addEventListener('click', () => { editForm.classList.add('d-none'); btnEdit.classList.remove('d-none'); input.value=parseFloat(display.textContent); });
    btnSave.addEventListener('click', () => {
        const val=parseFloat(input.value);
        if(isNaN(val)||val<0||val>50){Swal.fire({icon:'warning',title:'{{ app()->getLocale()==="ar" ? "قيمة غير صحيحة" : "Invalid value" }}',text:'0-50',timer:2000,showConfirmButton:false});return;}
        display.textContent=val+'%'; editForm.classList.add('d-none'); btnEdit.classList.remove('d-none');
        Swal.fire({icon:'success',title:'{{ app()->getLocale()==="ar" ? "تم التحديث" : "Updated" }}',text:`${val}%`,timer:1800,showConfirmButton:false});
    });
}
</script>
@endsection
