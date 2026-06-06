@extends('layouts.layoutMaster')
@section('title', __('Videos'))

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/quill/typography.scss',
  'resources/assets/vendor/libs/quill/katex.scss',
  'resources/assets/vendor/libs/quill/editor.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/quill/katex.js',
  'resources/assets/vendor/libs/quill/quill.js'
])
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">{{ __('Content & Interfaces') }} /</span> {{ __('Videos') }}
        </h4>
        <button class="btn btn-primary" onclick="openUniversalModal('{{ route('admin.videos.create') }}', 'modal-xl')">
            <i class="ti ti-plus me-1"></i> {{ __('Add Video') }}
        </button>
    </div>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Video') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Published') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($videos as $video)
                    <tr>
                        <td>{{ $video->id }}</td>
                        <td>{{ $video->getTranslation('title', app()->getLocale()) ?? '—' }}</td>
                        <td>
                            @if($video->video_url)
                                <a href="{{ asset($video->video_url) }}" target="_blank" class="text-danger"><i class="ti ti-brand-youtube"></i></a>
                            @else
                                —
                            @endif
                        </td>
                        <td><span class="badge bg-label-info">{{ $video->category ? $video->category->label() : '—' }}</span></td>
                        <td>
                            @if($video->is_published)
                                <span class="badge bg-success">{{ __('Yes') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('No') }}</span>
                            @endif
                        </td>
                        <td>
                            <button onclick="openUniversalModal('{{ route('admin.videos.edit', $video) }}', 'modal-xl')" class="btn btn-sm btn-icon btn-label-primary"><i class="ti ti-pencil"></i></button>
                            <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon btn-label-danger"><i class="ti ti-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">{{ __('No videos found') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $videos->links() }}
    </div>
</div>

{{-- ══ Universal Modal ═══════════════════════════════════════════ --}}
<div class="modal fade" id="universalModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" id="universalModalContent">
      <div class="d-flex justify-content-center p-4">
        <div class="spinner-border text-primary" role="status"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
function openUniversalModal(url, sizeClass = '') {
  const contentDiv = document.getElementById('universalModalContent');
  const dialogDiv = contentDiv.parentElement;
  
  // reset size classes
  dialogDiv.className = 'modal-dialog modal-dialog-centered ' + sizeClass;

  contentDiv.innerHTML = '<div class="d-flex justify-content-center p-5"><div class="spinner-border text-primary" role="status"></div></div>';
  window.bsShow('universalModal');

  fetch(url, {
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'text/html'
    }
  })
  .then(response => {
    if (!response.ok) throw new Error('Network error');
    return response.text();
  })
  .then(html => {
    contentDiv.innerHTML = html;
    const scripts = contentDiv.getElementsByTagName('script');
    for (let i = 0; i < scripts.length; i++) {
      eval(scripts[i].innerText);
    }
  })
  .catch(error => {
    contentDiv.innerHTML = `<div class="p-5 text-center text-danger"><i class="ti tabler-alert-circle mb-2" style="font-size: 2rem"></i><p>حدث خطأ أثناء تحميل البيانات</p></div>`;
  });
}
</script>
@endsection
