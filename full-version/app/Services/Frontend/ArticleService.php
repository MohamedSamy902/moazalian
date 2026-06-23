<?php

namespace App\Services\Frontend;

use App\Models\Article;

class ArticleService
{
    public function getLatest(int $n = 3)
    {
        return Article::published()->ordered()->take($n)->get();
    }

    public function getPaginated(array $filters = [])
    {
        $query = Article::published()->ordered();

        if (!empty($filters['search'])) {
            $search = mb_substr(strip_tags($filters['search']), 0, 100);
            $query->where(function ($q) use ($search) {
                $q->where('title->ar', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%")
                  ->orWhere('body->ar', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        return $query->paginate(12)->withQueryString();
    }

    public function getBySlug(string $slug): ?Article
    {
        return Article::published()->where('slug', $slug)->first();
    }

    public function getRelated(Article $article, int $n = 3)
    {
        return Article::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->ordered()
            ->take($n)
            ->get(['id', 'title', 'slug', 'excerpt', 'thumbnail', 'published_at', 'read_time', 'category']);
    }
}
