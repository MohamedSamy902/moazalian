@extends('layouts.layoutMaster')
@section('title', __('Interface Settings'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">{{ __('Interface Settings') }} /</span> {{ __('Sections') }}
        </h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @foreach($sections as $section)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @php
                            $icon = match($section->section_name) {
                                'seo'       => 'ti-search',
                                'hero'      => 'ti-home',
                                'socials'   => 'ti-social',
                                'hero_stats'=> 'ti-chart-bar',
                                'why_moaz'  => 'ti-user-check',
                                'yt_cta'    => 'ti-brand-youtube',
                                'donations' => 'ti-heart',
                                default     => 'ti-layout-dashboard',
                            };
                        @endphp
                        <span class="badge bg-label-{{ $section->section_name === 'seo' ? 'warning' : 'primary' }} p-3 rounded">
                            <i class="ti {{ $icon }} fs-3"></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-1">{{ $section->name_ar }}</h5>
                    <p class="text-muted mb-3">{{ $section->count }} {{ __('Editable Items') }}</p>
                    <a href="{{ route('admin.sections.edit', ['page' => $section->page, 'section_name' => $section->section_name]) }}"
                       class="btn btn-{{ $section->section_name === 'seo' ? 'warning' : 'primary' }} w-100">
                        <i class="ti {{ $section->section_name === 'seo' ? 'ti-seo' : 'ti-edit' }} me-1"></i>
                        {{ $section->section_name === 'seo' ? 'تعديل SEO' : __('Edit Content') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
