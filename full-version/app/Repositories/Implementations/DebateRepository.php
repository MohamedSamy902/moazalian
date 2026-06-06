<?php

namespace App\Repositories\Implementations;

use App\Models\Debate;
use App\Repositories\Interfaces\DebateRepositoryInterface;

class DebateRepository extends BaseRepository implements DebateRepositoryInterface
{
    public function __construct(Debate $model)
    {
        parent::__construct($model);
    }
}
