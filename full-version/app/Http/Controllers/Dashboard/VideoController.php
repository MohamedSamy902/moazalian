<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Videos\VideoRequest;
use App\Models\Video;
use App\Services\Dashboard\VideoService;
use App\Traits\ApiResponse;
use App\Traits\ChecksPermissions;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    use ChecksPermissions, ApiResponse;

    public function __construct(
        private readonly VideoService $videoService
    ) {}

    public function index(Request $request)
    {
        $this->checkPermission('videos.view');
        $filters = $request->only(['search', 'category', 'status']);
        $videos  = $this->videoService->getVideos($filters);
        return view('dashboard.videos.index', compact('videos'));
    }

    public function create()
    {
        $this->checkPermission('videos.create');
        return view('dashboard.videos.form-modal');
    }

    public function store(VideoRequest $request)
    {
        $this->checkPermission('videos.create');

        $this->videoService->createVideo($request->validated());

        return $this->successResponse(__('Added successfully'));
    }

    public function edit(Video $video)
    {
        $this->checkPermission('videos.edit');
        return view('dashboard.videos.form-modal', compact('video'));
    }

    public function update(VideoRequest $request, Video $video)
    {
        $this->checkPermission('videos.edit');

        $this->videoService->updateVideo($video, $request->validated());

        return $this->successResponse(__('Updated successfully'));
    }

    public function destroy(Video $video)
    {
        $this->checkPermission('videos.delete');

        try {
            $this->videoService->deleteVideo($video);
            return $this->successResponse(__('Deleted successfully'));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
