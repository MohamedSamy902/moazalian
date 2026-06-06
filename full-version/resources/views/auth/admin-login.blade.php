@php
$customizerHidden = 'customizer-hide';
$configData = App\Helpers\Helpers::appClasses();
@endphp

@extends('layouts/blankLayout')

@section('title', 'تسجيل دخول الإدارة - معاذ عليان')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('page-script')
@vite(['resources/assets/js/pages-auth.js'])
@endsection

@section('content')
<div class="authentication-wrapper authentication-cover">
  <!-- Logo -->
  <a href="{{ url('/') }}" class="app-brand auth-cover-brand">
    <span class="app-brand-text demo text-heading fw-bold" style="font-family: 'Tajawal', sans-serif;">لوحة تحكم منصة معاذ عليان</span>
  </a>
  <!-- /Logo -->
  <div class="authentication-inner row m-0">
    <!-- /Left Text -->
    <div class="d-none d-xl-flex col-xl-8 p-0">
      <div class="auth-cover-bg d-flex justify-content-center align-items-center" style="background-color: #0b1120;">
        <h1 class="text-white">أكاديمية معاذ عليان</h1>
      </div>
    </div>
    <!-- /Left Text -->

    <!-- Login -->
    <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6" dir="rtl">
      <div class="w-px-400 mx-auto mt-12 pt-5">
        <h4 class="mb-1 text-right">أهلاً بك في لوحة الإدارة 👋</h4>
        <p class="mb-6 text-right">يرجى تسجيل الدخول للوصول إلى لوحة التحكم.</p>

        @if($errors->any())
        <div class="alert alert-danger text-right">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="formAuthentication" class="mb-6 text-right" action="{{ route('admin.login') }}" method="POST">
          @csrf
          <div class="mb-6 form-control-validation">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
              placeholder="admin@moazalian.com" autofocus required />
          </div>
          <div class="mb-6 form-password-toggle form-control-validation">
            <label class="form-label" for="password">كلمة المرور</label>
            <div class="input-group input-group-merge">
              <input type="password" id="password" class="form-control" name="password"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="password" required />
              <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
            </div>
          </div>
          <div class="my-8">
            <div class="d-flex justify-content-between">
              <div class="form-check mb-0 ms-2">
                <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                <label class="form-check-label" for="remember-me"> تذكرني </label>
              </div>
            </div>
          </div>
          <button class="btn btn-primary d-grid w-100">تسجيل الدخول</button>
        </form>

      </div>
    </div>
    <!-- /Login -->
  </div>
</div>
@endsection
