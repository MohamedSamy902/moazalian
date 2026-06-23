<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SearchRequest;
use App\Models\Video;

class VideoController extends Controller
{
    public function index(SearchRequest $request)
    {
        // Only show published videos (whereNotNull('published_at'))
        $query = Video::whereNotNull('published_at');

        // Search — use request->get() to avoid validated() empty array edge cases
        $search = trim($request->get('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title->ar', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%");
            });
        }

        // Category filter
        $category = $request->get('category', '');
        if ($category !== '') {
            $query->where('category', $category);
        }

        // Sort — default: newest first (by published_at DESC)
        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'oldest'   => $query->orderBy('published_at', 'asc'),
            'popular'  => $query->orderByDesc('views'),
            'duration' => $query->orderByDesc('duration'),
            default    => $query->orderByDesc('published_at'),
        };

        $videos = $query->paginate(12)->withQueryString();

        return view('frontend.videos.index', compact('videos', 'sort'));
    }

    public function show(string $slug)
    {
        // Only publicly accessible if published
        $video = Video::with('references')
            ->whereNotNull('published_at')
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views
        $video->increment('views');

        // Related videos: same category, published only
        $relatedVideos = Video::whereNotNull('published_at')
            ->when($video->category,
                fn($q) => $q->where('category', $video->category),
                fn($q) => $q->whereNull('category')
            )
            ->where('id', '!=', $video->id)
            ->orderByDesc('published_at')
            ->take(6)
            ->get(['id', 'title', 'slug', 'thumbnail', 'duration', 'views', 'published_at', 'category', 'video_url']);

        return view('frontend.videos.show', compact('video', 'relatedVideos'));
    }
}
