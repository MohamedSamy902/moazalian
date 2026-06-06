<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SearchRequest;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(SearchRequest $request)
    {
        $query = Article::query()->published()->ordered();

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

        $articles = $query->paginate(12)->withQueryString();

        return view('frontend.articles.index', compact('articles'));
    }

    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)->published()->firstOrFail();

        return view('frontend.articles.show', compact('article'));
    }
}
