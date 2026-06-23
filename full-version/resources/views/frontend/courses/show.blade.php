@extends('frontend.layouts.master')

@section('title', $course->getTranslation('title', app()->getLocale()) . ' | معاذ عليان')
@section('meta_description', \Illuminate\Support\Str::limit($course->getTranslation('description', app()->getLocale()), 160))

@section('content')
<div class="container" style="margin-top: 100px; margin-bottom: 60px;">
    <div class="row g-5">

        {{-- Main Content --}}
        <div class="col-lg-8">

            {{-- Course Header --}}
            <div class="card-x mb-4 overflow-hidden">
                @if($course->thumbnail)
                    <img src="{{ asset($course->thumbnail) }}" alt="{{ $course->getTranslation('title', app()->getLocale()) }}"
                        style="width:100%; aspect-ratio:16/9; object-fit:cover">
                @else
                    <div style="width:100%;aspect-ratio:16/9;background:linear-gradient(135deg,#0d1a2d,#1a2d0d);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-mortarboard" style="font-size:4rem;color:var(--gold);opacity:.4"></i>
                    </div>
                @endif
                <div class="p-4">
                    <h1 style="font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:var(--text);line-height:1.4">
                        {{ $course->getTranslation('title', app()->getLocale()) }}
                    </h1>
                    <p style="color:var(--text2);line-height:1.8;font-size:.95rem;margin-top:.8rem">
                        {{ $course->getTranslation('description', app()->getLocale()) }}
                    </p>
                    <div class="d-flex gap-4 flex-wrap" style="font-size:.82rem;color:var(--text3);margin-top:1rem">
                        <span><i class="bi bi-collection-play me-1"></i>{{ $course->lessons->count() }} درس</span>
                        <span><i class="bi bi-calendar3 me-1"></i>{{ $course->created_at->translatedFormat('F Y') }}</span>
                        <span class="badge-x badge-gold">مجاني 100%</span>
                    </div>
                </div>
            </div>

            {{-- Lessons List --}}
            <div class="card-x p-4">
                <h2 style="font-size:1.2rem;font-weight:700;color:var(--text);margin-bottom:1.5rem">
                    <i class="bi bi-list-ol me-2" style="color:var(--gold)"></i>محتوى الكورس
                </h2>
                <div class="d-flex flex-column gap-2">
                    @forelse($course->lessons as $index => $lesson)
                        <div class="d-flex align-items-center gap-3 p-3"
                            style="background:var(--bg2);border-radius:10px;border:1px solid var(--border);transition:all .2s">
                            <span class="ic ic-md ic-teal flex-shrink-0" style="font-size:.85rem;font-weight:700">
                                {{ str_pad($lesson->number ?? $index + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <div class="flex-1">
                                <div style="font-weight:600;color:var(--text);font-size:.92rem">
                                    {{ $lesson->getTranslation('title', app()->getLocale()) }}
                                </div>
                                @if($lesson->duration)
                                    <div style="font-size:.75rem;color:var(--text3);margin-top:.2rem">
                                        <i class="bi bi-clock me-1"></i>{{ $lesson->duration }}
                                    </div>
                                @endif
                            </div>
                            @if($lesson->video_url)
                                <a href="{{ $lesson->video_url }}" target="_blank"
                                    class="btn btn-gold btn-sm flex-shrink-0" style="border-radius:20px">
                                    <i class="bi bi-play-fill me-1"></i>مشاهدة
                                </a>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">لم تُضف دروس بعد لهذا الكورس</p>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card-x p-4 position-sticky" style="top: 100px;">
                <h3 style="font-size:1rem;font-weight:700;color:var(--gold);margin-bottom:1rem">ابدأ التعلم الآن</h3>
                @if($course->lessons->isNotEmpty() && $course->lessons->first()->video_url)
                    <a href="{{ $course->lessons->first()->video_url }}" target="_blank"
                        class="btn btn-gold w-100 mb-3" style="border-radius:30px;padding:.8rem">
                        <i class="bi bi-play-circle me-2"></i>ابدأ الدرس الأول
                    </a>
                @endif
                <div class="d-flex flex-column gap-2" style="font-size:.85rem;color:var(--text2)">
                    <div><i class="bi bi-collection-play me-2" style="color:var(--gold)"></i>{{ $course->lessons->count() }} درس</div>
                    <div><i class="bi bi-infinity me-2" style="color:var(--teal)"></i>وصول مفتوح مجاني</div>
                    <div><i class="bi bi-phone me-2" style="color:var(--green)"></i>يعمل على جميع الأجهزة</div>
                </div>
                <hr style="border-color:var(--border);margin:1.2rem 0">
                <a href="{{ route('courses.index') }}" class="btn btn-outline-gold w-100" style="border-radius:30px">
                    <i class="bi bi-grid me-1"></i>كل الكورسات
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
