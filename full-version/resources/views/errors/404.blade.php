@extends('layouts/layoutMaster')
@section('title', '404 — الصفحة غير موجودة | Fix-It')
@section('content')
<div class="d-flex flex-column align-items-center justify-content-center text-center" style="min-height:65vh">
    <div style="font-size:7rem;font-weight:900;background:linear-gradient(135deg,#7367f0,#ce9ffc);-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1">404</div>
    <h3 class="mt-3 mb-2 fw-bold">الصفحة غير موجودة</h3>
    <p class="text-muted mb-4" style="max-width:420px">عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها أو حذفها.</p>
    <div class="d-flex gap-3">
        <a href="{{ url()->previous() }}" class="btn btn-label-secondary"><i class="ti tabler-arrow-right me-1"></i> العودة للخلف</a>
        <a href="{{ route('dashboard.home') }}" class="btn btn-primary"><i class="ti tabler-smart-home me-1"></i> الرئيسية</a>
    </div>
    <div class="mt-5 opacity-25">
        <i class="ti tabler-file-search" style="font-size:5rem;color:#7367f0"></i>
    </div>
</div>
@endsection

