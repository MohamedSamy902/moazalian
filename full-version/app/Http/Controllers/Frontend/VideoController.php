<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SearchRequest;
use App\Models\Video;

class VideoController extends Controller
{
    public function index(SearchRequest $request)
    {
        $query = Video::query()->whereNotNull('published_at')->ordered();

        if ($request->filled('search')) {
            $search = $request->validated()['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title->ar', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->validated()['category']);
        }

        $videos = $query->paginate(12)->withQueryString();

        return view('frontend.videos.index', compact('videos'));
    }

    public function show(string $slug)
    {
        $video = Video::with('references')
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        // Increment views — avoid race conditions with increment()
        $video->increment('views');

        // Related videos: same category, excluding current, max 3
        $relatedVideos = Video::where('category', $video->category)
            ->where('id', '!=', $video->id)
            ->whereNotNull('published_at')
            ->ordered()
            ->take(3)
            ->get(['id', 'title', 'slug', 'thumbnail', 'duration', 'views', 'published_at', 'category']);

        return view('frontend.videos.show', compact('video', 'relatedVideos'));
    }
}
