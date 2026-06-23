<?php

namespace App\Services\Frontend;

use App\Models\Debate;

class DebateService
{
    public function getPaginated(array $filters = [])
    {
        $query = Debate::latest();

        if (!empty($filters['search'])) {
            $search = mb_substr(strip_tags($filters['search']), 0, 100);
            $query->where(function ($q) use ($search) {
                $q->where('title->ar', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%");
            });
        }

        return $query->paginate(9)->withQueryString();
    }

    public function getFeatured(int $n = 3)
    {
        return Debate::featured()->take($n)->get();
    }

    public function getBySlug(string $slug): ?Debate
    {
        return Debate::where('slug', $slug)->first();
    }
}
