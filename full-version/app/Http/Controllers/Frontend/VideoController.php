<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\VideoRepositoryInterface;

class VideoController extends Controller
{
    public function __construct(
        private readonly VideoRepositoryInterface $videoRepo
    ) {}

    public function index(Request $request)
    {
        $query = Video::query()->whereNotNull('published_at')->ordered();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $videos = $query->paginate(12)->withQueryString();

        return view('frontend.videos.index', compact('videos'));
    }

    public function show($slug)
    {
        $video = Video::with('references')->where('slug', $slug)->whereNotNull('published_at')->firstOrFail();
        
        // زيادة عدد المشاهدات
        $video->increment('views');

        // مقترحات لـ 3 فيديوهات من نفس القسم
        $relatedVideos = Video::where('category', $video->category)
            ->where('id', '!=', $video->id)
            ->whereNotNull('published_at')
            ->ordered()
            ->take(3)
            ->get();

        return view('frontend.videos.show', compact('video', 'relatedVideos'));
    }
}
