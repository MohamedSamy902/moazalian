@extends('layouts/layoutMaster')
@section('title', __('page.settings.title') . ' - Fix-It')

@section('content')
<div class="page-header">
  <div>
    <h4><i class="ti tabler-settings"></i> {{ __('page.settings.title') }}</h4>
    <div class="fixit-breadcrumb mt-1">
      <a href="{{ route('dashboard.home') }}"><i class="ti tabler-smart-home" style="font-size:0.85rem"></i> {{ __('breadcrumb.home') }}</a>
      <i class="ti tabler-chevron-left mx-1" style="font-size:0.6rem;opacity:0.45;color:#7367f0"></i>
      <span>{{ __('breadcrumb.settings') }}</span>
    </div>
  </div>
</div>

<div class="row g-4">
  {{-- Sidebar Navigation --}}
  <div class="col-md-3">
    <div class="card glass-card">
      <div class="card-body p-2">
        <nav class="nav flex-column settings-nav">
          <a class="nav-link d-flex align-items-center gap-2 active rounded-2 mb-1 px-3 py-2" data-bs-toggle="tab" href="#profile">
            <i class="ti tabler-user-circle" style="font-size:1.1rem"></i> {{ __('page.settings.profile') }}
          </a>
          <a class="nav-link d-flex align-items-center gap-2 rounded-2 mb-1 px-3 py-2" data-bs-toggle="tab" href="#system">
            <i class="ti tabler-adjustments" style="font-size:1.1rem"></i> {{ __('page.settings.general') }}
          </a>
          <a class="nav-link d-flex align-items-center gap-2 rounded-2 mb-1 px-3 py-2" data-bs-toggle="tab" href="#notifications">
            <i class="ti tabler-bell" style="font-size:1.1rem"></i> {{ __('page.settings.notifications') }}
          </a>
          <a class="nav-link d-flex align-items-center gap-2 rounded-2 mb-1 px-3 py-2" data-bs-toggle="tab" href="#security">
            <i class="ti tabler-lock" style="font-size:1.1rem"></i> {{ __('page.settings.security') }}
          </a>
          <a class="nav-link d-flex align-items-center gap-2 rounded-2 mb-1 px-3 py-2" data-bs-toggle="tab" href="#integrations">
            <i class="ti tabler-plug" style="font-size:1.1rem"></i> {{ __('page.settings.integrations') }}
          </a>
          <a class="nav-link d-flex align-items-center gap-2 rounded-2 px-3 py-2" data-bs-toggle="tab" href="#commissions">
            <i class="ti tabler-percentage" style="font-size:1.1rem"></i> {{ __('page.settings.commissions') }}
          </a>
        </nav>
      </div>
    </div>
  </div>

  {{-- Content --}}
  <div class="col-md-9">
    <div class="tab-content">

      {{-- Profile Tab --}}
      <div class="tab-pane fade show active" id="profile">
        <div class="card glass-card">
          <div class="card-header border-0 pt-4 pb-0 px-4">
            <h5 class="card-title mb-0 fw-bold">{{ __('page.settings.profile_title') }}</h5>
            <p class="text-muted small mt-1 mb-0">{{ __('page.settings.profile_desc') }}</p>
          </div>
          <div class="card-body px-4 pb-4 pt-3">
            <div class="d-flex align-items-center gap-4 mb-4 pb-4 border-bottom">
              <div class="avatar avatar-xl">
                <span class="avatar-initial rounded-circle bg-label-primary" style="font-size:2rem">م</span>
              </div>
              <div>
                <button class="btn btn-sm btn-primary mb-1"><i class="ti tabler-upload me-1"></i> {{ __('page.settings.upload_photo') }}</button>
                <p class="text-muted small mb-0">{{ __('page.settings.photo_hint') }}</p>
              </div>
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('page.settings.full_name') }}</label>
                <input type="text" class="form-control" value="مدير النظام" placeholder="{{ __('page.settings.full_name') }}">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('page.settings.email') ?? 'Email' }}</label>
                <input type="email" class="form-control" value="admin@fixit.com">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('page.settings.phone') }}</label>
                <input type="text" class="form-control" placeholder="05XXXXXXXX">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('page.settings.job_title') }}</label>
                <input type="text" class="form-control" value="مدير عام">
              </div>
            </div>
          </div>
          <div class="card-footer border-top px-4 py-3 d-flex justify-content-end gap-2">
            <button class="btn btn-label-secondary">{{ __('common.cancel') }}</button>
            <button class="btn btn-primary"><i class="ti tabler-device-floppy me-1"></i> {{ __('page.settings.save_changes') }}</button>
          </div>
        </div>
      </div>

      {{-- System Tab --}}
      <div class="tab-pane fade" id="system">
        <div class="card glass-card">
          <div class="card-header border-0 pt-4 pb-0 px-4">
            <h5 class="card-title mb-0 fw-bold">{{ __('page.settings.general_title') }}</h5>
            <p class="text-muted small mt-1 mb-0">{{ __('page.settings.general_desc') }}</p>
          </div>
          <div class="card-body px-4 pb-4 pt-3">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('page.settings.platform_name') }}</label>
                <input type="text" class="form-control" value="Fix-It">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('page.settings.currency') }}</label>
                <select class="form-select">
                  <option selected>EGP - {{ app()->getLocale()==='ar' ? 'جنيه مصري' : 'Egyptian Pound' }}</option>
                  <option>USD - {{ app()->getLocale()==='ar' ? 'دولار أمريكي' : 'US Dollar' }}</option>
                  <option>SAR - {{ app()->getLocale()==='ar' ? 'ريال سعودي' : 'Saudi Riyal' }}</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('page.settings.timezone') }}</label>
                <select class="form-select">
                  <option selected>Africa/Cairo (GMT+2)</option>
                  <option>Asia/Riyadh (GMT+3)</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-footer border-top px-4 py-3 d-flex justify-content-end gap-2">
            <button class="btn btn-label-secondary">{{ __('common.cancel') }}</button>
            <button class="btn btn-primary"><i class="ti tabler-device-floppy me-1"></i> {{ __('page.settings.save_changes') }}</button>
          </div>
        </div>
      </div>

      {{-- Notifications Tab --}}
      <div class="tab-pane fade" id="notifications">
        <div class="card glass-card">
          <div class="card-header border-0 pt-4 pb-0 px-4">
            <h5 class="card-title mb-0 fw-bold">{{ __('page.settings.notif_title') }}</h5>
            <p class="text-muted small mt-1 mb-0">{{ __('page.settings.notif_desc') }}</p>
          </div>
          <div class="card-body px-4 pb-4 pt-3">
            @php
            $notifications = [
              ['title_key'=>'page.settings.email_alerts','desc_key'=>'page.settings.email_alerts_desc','checked'=>true,'icon'=>'tabler-mail'],
              ['title_key'=>'page.settings.browser_notif','desc_key'=>'page.settings.browser_notif_desc','checked'=>true,'icon'=>'tabler-bell'],
              ['title_key'=>'page.settings.new_order_alert','desc_key'=>'page.settings.new_order_alert_desc','checked'=>true,'icon'=>'tabler-briefcase-plus'],
              ['title_key'=>'page.settings.promo_expiry_alert','desc_key'=>'page.settings.promo_expiry_alert_desc','checked'=>false,'icon'=>'tabler-ticket'],
              ['title_key'=>'page.settings.weekly_reports','desc_key'=>'page.settings.weekly_reports_desc','checked'=>false,'icon'=>'tabler-chart-bar'],
            ];
            @endphp
            @foreach($notifications as $notif)
            <div class="notification-item d-flex align-items-center gap-3">
              <div class="stat-icon bg-label-primary" style="width:40px;height:40px;min-width:40px;border-radius:10px">
                <i class="ti {{ $notif['icon'] }} text-primary"></i>
              </div>
              <div class="flex-grow-1">
                <div class="fw-semibold">{{ __($notif['title_key']) }}</div>
                <small class="text-muted">{{ __($notif['desc_key']) }}</small>
              </div>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" style="width:2.2rem;height:1.1rem" {{ $notif['checked'] ? 'checked' : '' }}>
              </div>
            </div>
            @endforeach
          </div>
          <div class="card-footer border-top px-4 py-3 d-flex justify-content-end">
            <button class="btn btn-primary"><i class="ti tabler-device-floppy me-1"></i> {{ __('page.settings.save_settings') }}</button>
          </div>
        </div>
      </div>

      {{-- Security Tab --}}
      <div class="tab-pane fade" id="security">
        <div class="card glass-card">
          <div class="card-header border-0 pt-4 pb-0 px-4">
            <h5 class="card-title mb-0 fw-bold">{{ __('page.settings.security_title') }}</h5>
            <p class="text-muted small mt-1 mb-0">{{ __('page.settings.security_desc') }}</p>
          </div>
          <div class="card-body px-4 pb-4 pt-3">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label fw-semibold">{{ __('page.settings.current_password') }}</label>
                <input type="password" class="form-control" placeholder="••••••••">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('page.settings.new_password') }}</label>
                <input type="password" class="form-control" placeholder="••••••••">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('page.settings.confirm_password') }}</label>
                <input type="password" class="form-control" placeholder="••••••••">
              </div>
            </div>
          </div>
          <div class="card-footer border-top px-4 py-3 d-flex justify-content-end gap-2">
            <button class="btn btn-label-secondary">{{ __('common.cancel') }}</button>
            <button class="btn btn-primary"><i class="ti tabler-lock me-1"></i> {{ __('page.settings.change_password') }}</button>
          </div>
        </div>
      </div>

      {{-- Integrations Tab --}}
      <div class="tab-pane fade" id="integrations">
        <div class="card glass-card">
          <div class="card-header border-0 pt-4 pb-0 px-4">
            <h5 class="card-title mb-0 fw-bold">{{ __('page.settings.integrations_title') }}</h5>
            <p class="text-muted small mt-1 mb-0">{{ __('page.settings.integrations_desc') }}</p>
          </div>
          <div class="card-body px-4 pb-4 pt-3">
            {{-- WhatsApp --}}
            <div class="integration-section mb-4 pb-4 border-bottom">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon bg-label-success flex-shrink-0" style="width:44px;height:44px;border-radius:12px">
                  <i class="ti tabler-brand-whatsapp text-success" style="font-size:1.3rem"></i>
                </div>
                <div>
                  <div class="fw-bold">WhatsApp Business API (Meta)</div>
                  <small class="text-muted">Meta Cloud API - WABA Integration</small>
                </div>
                <span class="badge bg-label-secondary ms-auto">{{ __('page.settings.not_connected') }}</span>
              </div>
              <div class="row g-3">
                <div class="col-md-6"><label class="form-label fw-semibold">Phone Number ID</label><input type="text" class="form-control" placeholder="1234567890"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">WABA ID</label><input type="text" class="form-control" placeholder="1234567890"></div>
                <div class="col-12">
                  <label class="form-label fw-semibold">Access Token</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="wa-token" placeholder="EAAG...">
                    <button class="btn btn-label-secondary" type="button" onclick="togglePass('wa-token')"><i class="ti tabler-eye"></i></button>
                  </div>
                </div>
              </div>
            </div>
            {{-- SMS --}}
            <div class="integration-section">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon bg-label-info flex-shrink-0" style="width:44px;height:44px;border-radius:12px">
                  <i class="ti tabler-message text-info" style="font-size:1.3rem"></i>
                </div>
                <div>
                  <div class="fw-bold">SMS Gateway</div>
                  <small class="text-muted">Unifonic / Taqnyat / Msegat</small>
                </div>
                <span class="badge bg-label-secondary ms-auto">{{ __('page.settings.not_connected') }}</span>
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">{{ __('page.settings.provider') }}</label>
                  <select class="form-select"><option>Unifonic</option><option>Taqnyat</option><option>Msegat</option></select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">{{ __('page.settings.sender_name') }}</label>
                  <input type="text" class="form-control" placeholder="Fix-It">
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold">API Key</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="sms-key" placeholder="sk_live_...">
                    <button class="btn btn-label-secondary" type="button" onclick="togglePass('sms-key')"><i class="ti tabler-eye"></i></button>
                  </div>
                </div>
              </div>
            </div>

            {{-- Environment --}}
            <div class="integration-section mt-4 pt-4 border-top">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon bg-label-warning flex-shrink-0" style="width:44px;height:44px;border-radius:12px">
                  <i class="ti tabler-server text-warning" style="font-size:1.3rem"></i>
                </div>
                <div>
                  <div class="fw-bold">{{ __('page.settings.env_title') }}</div>
                  <small class="text-muted">{{ __('page.settings.env_desc') }}</small>
                </div>
              </div>
              <div class="row g-3 align-items-center">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">{{ __('page.settings.active_env') }}</label>
                  <div class="d-flex gap-2">
                    <label class="env-btn" id="env-live-label">
                      <input type="radio" name="env_mode" value="live" id="env-live">
                      <span><i class="ti tabler-broadcast me-1"></i>Live</span>
                    </label>
                    <label class="env-btn" id="env-dev-label">
                      <input type="radio" name="env_mode" value="dev" id="env-dev" checked>
                      <span><i class="ti tabler-code me-1"></i>Dev</span>
                    </label>
                  </div>
                  <div class="form-text mt-2">{{ __('page.settings.current_env') }}: <span id="env-badge" class="badge bg-label-warning">Development</span></div>
                </div>
              </div>
            </div>

            {{-- Tracking Pixels --}}
            <div class="integration-section mt-4 pt-4 border-top">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon bg-label-primary flex-shrink-0" style="width:44px;height:44px;border-radius:12px">
                  <i class="ti tabler-radar text-primary" style="font-size:1.3rem"></i>
                </div>
                <div>
                  <div class="fw-bold">{{ __('page.settings.tracking_pixels') }}</div>
                  <small class="text-muted">{{ __('page.settings.tracking_desc') }}</small>
                </div>
              </div>
              <div class="row g-3">
                <div class="col-md-4">
                  <div class="card border" style="border-radius:12px">
                    <div class="card-body p-3">
                      <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="ti tabler-brand-tiktok text-dark" style="font-size:1.2rem"></i>
                        <span class="fw-bold small">TikTok Pixel</span>
                        <div class="form-check form-switch ms-auto mb-0"><input class="form-check-input" type="checkbox" id="tt-pixel-toggle" style="width:2rem;height:1rem"></div>
                      </div>
                      <label class="form-label small fw-semibold">Pixel ID</label>
                      <input type="text" class="form-control form-control-sm" id="tt-pixel-id" placeholder="CXXXXXXXXXXXXXXXXXX" disabled>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card border" style="border-radius:12px">
                    <div class="card-body p-3">
                      <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="ti tabler-brand-meta text-primary" style="font-size:1.2rem"></i>
                        <span class="fw-bold small">Meta Pixel</span>
                        <div class="form-check form-switch ms-auto mb-0"><input class="form-check-input" type="checkbox" id="meta-pixel-toggle" style="width:2rem;height:1rem"></div>
                      </div>
                      <label class="form-label small fw-semibold">Pixel ID</label>
                      <input type="text" class="form-control form-control-sm" id="meta-pixel-id" placeholder="1234567890" disabled>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card border" style="border-radius:12px">
                    <div class="card-body p-3">
                      <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="ti tabler-brand-google text-danger" style="font-size:1.2rem"></i>
                        <span class="fw-bold small">Google Ads Tag</span>
                        <div class="form-check form-switch ms-auto mb-0"><input class="form-check-input" type="checkbox" id="google-tag-toggle" style="width:2rem;height:1rem"></div>
                      </div>
                      <label class="form-label small fw-semibold">Conversion ID</label>
                      <input type="text" class="form-control form-control-sm" id="google-tag-id" placeholder="AW-XXXXXXXXXX" disabled>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Google Analytics --}}
            <div class="integration-section mt-4 pt-4 border-top">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon bg-label-danger flex-shrink-0" style="width:44px;height:44px;border-radius:12px">
                  <i class="ti tabler-chart-line text-danger" style="font-size:1.3rem"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="fw-bold">Google Analytics 4</div>
                  <small class="text-muted">{{ app()->getLocale()==='ar' ? 'تتبع تفاعلات المستخدمين وتحليلات الأداء' : 'Track user interactions and performance analytics' }}</small>
                </div>
                <div class="form-check form-switch mb-0">
                  <input class="form-check-input" type="checkbox" id="ga4-toggle" style="width:2.2rem;height:1.1rem">
                </div>
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Measurement ID</label>
                  <input type="text" class="form-control" id="ga4-id" placeholder="G-XXXXXXXXXX" disabled>
                  <div class="form-text">{{ app()->getLocale()==='ar' ? 'مثال: G-ABC123XYZ' : 'Example: G-ABC123XYZ' }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer border-top px-4 py-3 d-flex justify-content-between align-items-center">
            <button class="btn btn-label-info btn-sm"><i class="ti tabler-plug me-1"></i> {{ __('page.settings.test_connection') }}</button>
            <button class="btn btn-primary"><i class="ti tabler-device-floppy me-1"></i> {{ __('page.settings.save_changes') }}</button>
          </div>
        </div>
      </div>

      {{-- Commissions Tab --}}
      <div class="tab-pane fade" id="commissions">
        <div class="card glass-card">
          <div class="card-header border-0 pt-4 pb-0 px-4">
            <h5 class="card-title mb-0 fw-bold">{{ __('page.settings.commissions_title') }}</h5>
            <p class="text-muted small mt-1 mb-0">{{ __('page.settings.commissions_desc') }}</p>
          </div>
          <div class="card-body px-4 pb-4 pt-3">
            <div class="alert alert-primary d-flex align-items-center gap-2 mb-4">
              <i class="ti tabler-info-circle fs-5"></i>
              <span>{{ __('page.settings.commission_info') }} <a href="{{ route('dashboard.centers.index') }}" class="alert-link">{{ __('page.settings.center_page') }}</a> {{ __('page.settings.commission_info_end') }}</span>
            </div>
            <div class="row g-4">
              <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('page.settings.default_commission') }}</label>
                <div class="input-group">
                  <input type="number" class="form-control" value="10" min="0" max="50">
                  <span class="input-group-text">%</span>
                </div>
                <div class="form-text">{{ __('page.settings.default_commission_desc') }}</div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('page.settings.default_visit_fee') }}</label>
                <div class="input-group">
                  <input type="number" class="form-control" value="50">
                  <span class="input-group-text">EGP</span>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer border-top px-4 py-3 d-flex justify-content-end">
            <button class="btn btn-primary"><i class="ti tabler-device-floppy me-1"></i> {{ __('page.settings.save_changes') }}</button>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
function togglePass(id) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}
document.querySelectorAll('[name="env_mode"]').forEach(radio => {
    radio.addEventListener('change', function () {
        const badge = document.getElementById('env-badge');
        if (this.value === 'live') { badge.className='badge bg-label-success'; badge.textContent='Live (Production)'; }
        else { badge.className='badge bg-label-warning'; badge.textContent='Development'; }
    });
});
['tt-pixel','meta-pixel','google-tag'].forEach(key => {
    const t=document.getElementById(key+'-toggle'), i=document.getElementById(key+'-id');
    if(t&&i) t.addEventListener('change',()=>{i.disabled=!t.checked;});
});
const ga4t=document.getElementById('ga4-toggle'),ga4i=document.getElementById('ga4-id');
if(ga4t&&ga4i) ga4t.addEventListener('change',()=>{ga4i.disabled=!ga4t.checked;});
</script>
@endsection

@section('page-style')
<style>
.settings-nav .nav-link { color:#6f6b7d; font-weight:600; transition:all 0.2s ease; }
.settings-nav .nav-link:hover { color:#7367f0; background:rgba(115,103,240,0.08); }
.settings-nav .nav-link.active { color:#7367f0; background:rgba(115,103,240,0.1); }
.env-btn input { display:none; }
.env-btn span { display:inline-flex; align-items:center; padding:7px 18px; border-radius:20px; border:1.5px solid #d9d9d9; font-size:0.85rem; font-weight:600; cursor:pointer; transition:all 0.2s; color:#6e6b7b; }
.env-btn input:checked + span { border-color:#7367f0; background:rgba(115,103,240,0.1); color:#7367f0; }
#env-live:checked + span { border-color:#28c76f; background:rgba(40,199,111,0.1); color:#28c76f; }
</style>
@endsection
