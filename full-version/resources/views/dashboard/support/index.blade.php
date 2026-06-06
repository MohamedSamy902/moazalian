@extends('layouts/layoutMaster')
@section('title', 'الدعم والدردشة - Fix-It')
@section('content')
<div class="app-chat card glass-card overflow-hidden">
  <div class="row g-0 h-100" style="min-height: 70vh;">
    <!-- قائمة المحادثات -->
    <div class="col-md-4 border-end h-100 d-flex flex-column">
      <div class="p-3 border-bottom d-flex align-items-center">
        <div class="flex-grow-1">
          <h5 class="mb-0">المحادثات</h5>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-icon btn-label-primary dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti tabler-filter"></i></button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">العملاء</a></li>
                <li><a class="dropdown-item" href="#">مراكز الصيانة</a></li>
            </ul>
        </div>
      </div>
      <div class="flex-grow-1 overflow-auto">
        <ul class="list-group list-group-flush chat-list">
          <!-- محادثة نشطة -->
          <li class="list-group-item list-group-item-action border-0 p-3 active">
            <div class="d-flex align-items-center">
              <div class="avatar avatar-md me-3">
                <span class="avatar-initial rounded-circle bg-label-info">أ</span>
                <span class="avatar-status-online"></span>
              </div>
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between mb-1">
                  <h6 class="mb-0">أحمد محمد (عميل)</h6>
                  <small class="text-muted">الآن</small>
                </div>
                <p class="mb-0 text-truncate text-muted small">هل تم استلام الجهاز؟</p>
              </div>
            </div>
          </li>
          <!-- محادثات أخرى -->
          <li class="list-group-item list-group-item-action border-0 p-3">
            <div class="d-flex align-items-center">
              <div class="avatar avatar-md me-3">
                <span class="avatar-initial rounded-circle bg-label-warning">م</span>
              </div>
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between mb-1">
                  <h6 class="mb-0">مركز النخبة (مركز)</h6>
                  <small class="text-muted">منذ ساعة</small>
                </div>
                <p class="mb-0 text-truncate text-muted small">نحتاج قطع غيار إضافية</p>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- نافذة الدردشة -->
    <div class="col-md-8 h-100 d-flex flex-column bg-white bg-opacity-50">
      <div class="p-3 border-bottom d-flex align-items-center bg-white">
        <div class="avatar avatar-md me-3">
          <span class="avatar-initial rounded-circle bg-label-info">أ</span>
        </div>
        <div class="flex-grow-1">
          <h6 class="mb-0">أحمد محمد</h6>
          <small class="text-success">متصل الآن</small>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-icon btn-label-secondary"><i class="ti tabler-phone"></i></button>
            <button class="btn btn-sm btn-icon btn-label-secondary"><i class="ti tabler-dots-vertical"></i></button>
        </div>
      </div>

      <!-- الرسائل -->
      <div class="flex-grow-1 p-4 overflow-auto chat-messages d-flex flex-column gap-3">
        <!-- رسالة مستلمة -->
        <div class="chat-message-container d-flex">
          <div class="chat-message-content bg-white p-3 rounded-3 shadow-sm max-w-75">
            <p class="mb-0">السلام عليكم، هل تم استلام جهاز التكييف الخاص بي؟</p>
            <small class="text-muted float-end mt-1">10:00 AM</small>
          </div>
        </div>

        <!-- رسالة مرسلة -->
        <div class="chat-message-container d-flex flex-row-reverse">
          <div class="chat-message-content bg-primary text-white p-3 rounded-3 shadow-sm max-w-75">
            <p class="mb-0">وعليكم السلام، نعم يا سيد أحمد. الفني في طريقه إليك الآن.</p>
            <small class="text-white-50 float-start mt-1">10:05 AM <i class="ti tabler-checks ms-1"></i></small>
          </div>
        </div>

        <!-- رسالة مستلمة -->
        <div class="chat-message-container d-flex">
          <div class="chat-message-content bg-white p-3 rounded-3 shadow-sm max-w-75">
            <p class="mb-0">ممتاز، شكراً جزيلاً لكم.</p>
            <small class="text-muted float-end mt-1">10:06 AM</small>
          </div>
        </div>
      </div>

      <!-- مدخل النص -->
      <div class="p-3 border-top bg-white">
        <form class="d-flex align-items-center gap-2">
          <button type="button" class="btn btn-icon btn-label-secondary"><i class="ti tabler-paperclip"></i></button>
          <input type="text" class="form-control" placeholder="اكتب رسالتك هنا...">
          <button type="submit" class="btn btn-primary"><i class="ti tabler-send"></i></button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-style')
<style>
.max-w-75 { max-width: 75%; }
.chat-list .active { background: rgba(115, 103, 240, 0.1) !important; color: inherit !important; border-left: 3px solid #7367f0 !important; }
.chat-messages { background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%237367f0' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2v-4h4v-2h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2v-4h4v-2H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
</style>
@endsection
