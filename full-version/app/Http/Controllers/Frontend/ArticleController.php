<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SearchRequest;
use App\Services\Frontend\ArticleService;

class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleService $articleService,
    ) {}

    public function index(SearchRequest $request)
    {
        $articles = $this->articleService->getPaginated($request->validated());

        return view('frontend.articles.index', compact('articles'));
    }

    public function show(string $slug)
    {
        $article = $this->articleService->getBySlug($slug);
        abort_if(!$article, 404);

        $relatedArticles = $this->articleService->getRelated($article);

        return view('frontend.articles.show', compact('article', 'relatedArticles'));
    }
}
