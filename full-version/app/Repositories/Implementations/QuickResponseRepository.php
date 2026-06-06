<?php

namespace App\Repositories\Implementations;

use App\Models\QuickResponse;
use App\Repositories\Interfaces\QuickResponseRepositoryInterface;

class QuickResponseRepository extends BaseRepository implements QuickResponseRepositoryInterface
{
    public function __construct(QuickResponse $model)
    {
      parent::__construct($model);
    }
}
