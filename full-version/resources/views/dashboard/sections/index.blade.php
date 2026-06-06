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
                        <span class="badge bg-label-primary p-3 rounded"><i class="ti ti-layout-dashboard fs-3"></i></span>
                    </div>
                    <h5 class="card-title mb-1">{{ $section->name_ar }}</h5>
                    <p class="text-muted mb-3">{{ $section->count }} {{ __('Editable Items') }}</p>
                    <a href="{{ route('admin.sections.edit', $section->section_name) }}" class="btn btn-primary w-100">
                        <i class="ti ti-edit me-1"></i> {{ __('Edit Content') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
