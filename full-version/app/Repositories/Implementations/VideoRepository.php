<?php

namespace App\Repositories\Implementations;

use App\Models\Video;
use App\Repositories\Interfaces\VideoRepositoryInterface;

class VideoRepository extends BaseRepository implements VideoRepositoryInterface
{
    public function __construct(Video $model)
    {
        parent::__construct($model);
    }

    public function getPublishedPaginated(int $perPage = 10)
    {
        return $this->model->whereNotNull('published_at')->ordered()->paginate($perPage);
    }
}
