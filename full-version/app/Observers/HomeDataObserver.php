<?php

namespace App\Observers;

use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

/**
 * Automatically invalidates the homepage cache whenever
 * any content model (Video, Article, Debate, Book, QuickResponse) is changed.
 *
 * Register in AppServiceProvider::boot() or EventServiceProvider.
 */
class HomeDataObserver
{
    public function created($model): void   { $this->flush(); }
    public function updated($model): void   { $this->flush(); }
    public function deleted($model): void   { $this->flush(); }
    public function restored($model): void  { $this->flush(); }

    private function flush(): void
    {
        Cache::forget(CacheKeys::HOME_DATA);
    }
}
