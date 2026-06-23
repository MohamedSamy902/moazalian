<?php

namespace App\Services\Frontend;

use App\Models\Book;

class BookService
{
    public function getPaginated(array $filters = [])
    {
        $query = Book::ordered();

        if (!empty($filters['search'])) {
            $search = mb_substr(strip_tags($filters['search']), 0, 100);
            $query->where(function ($q) use ($search) {
                $q->where('title->ar', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        return $query->paginate(12)->withQueryString();
    }

    public function getFeatured(int $n = 3)
    {
        return Book::ordered()->take($n)->get();
    }
}
