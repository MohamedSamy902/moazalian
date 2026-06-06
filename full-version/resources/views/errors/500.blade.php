@extends('layouts/layoutMaster')
@section('title', '500 — خطأ في الخادم | Fix-It')
@section('content')
<div class="d-flex flex-column align-items-center justify-content-center text-center" style="min-height:65vh">
    <div style="font-size:7rem;font-weight:900;background:linear-gradient(135deg,#ea5455,#f08182);-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1">500</div>
    <h3 class="mt-3 mb-2 fw-bold">خطأ في الخادم</h3>
    <p class="text-muted mb-4" style="max-width:420px">حدث خطأ داخلي في الخادم. تم إبلاغ الفريق الفني تلقائياً وسيتم إصلاحه قريباً.</p>
    <div class="d-flex gap-3">
        <a href="{{ url()->previous() }}" class="btn btn-label-secondary"><i class="ti tabler-arrow-right me-1"></i> العودة للخلف</a>
        <a href="{{ route('dashboard.home') }}" class="btn btn-primary"><i class="ti tabler-smart-home me-1"></i> الرئيسية</a>
    </div>
    <div class="mt-5 opacity-25">
        <i class="ti tabler-server-off" style="font-size:5rem;color:#ea5455"></i>
    </div>
</div>
@endsection
