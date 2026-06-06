<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Services\Dashboard\VideoService;
use App\Http\Requests\Dashboard\Videos\StoreVideoRequest;
use App\Http\Requests\Dashboard\Videos\UpdateVideoRequest;

class VideoController extends Controller
{
    public function __construct(
        private readonly VideoService $videoService
    ) {}

    public function index()
    {
        $videos = $this->videoService->getVideos();
        return view('dashboard.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('dashboard.videos.form-modal');
    }

    public function store(StoreVideoRequest $request)
    {
        $this->videoService->createVideo($request->validated());
        return response()->json(['success' => true, 'message' => __('Added successfully')]);
    }

    public function edit(Video $video)
    {
        return view('dashboard.videos.form-modal', compact('video'));
    }

    public function update(UpdateVideoRequest $request, Video $video)
    {
        $this->videoService->updateVideo($video, $request->validated());
        return response()->json(['success' => true, 'message' => __('Updated successfully')]);
    }

    public function destroy(Video $video)
    {
        $this->videoService->deleteVideo($video);
        return redirect()->route('admin.videos.index')->with('success', __('Deleted successfully'));
    }
}
