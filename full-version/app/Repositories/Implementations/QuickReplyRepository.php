<?php

namespace App\Repositories\Implementations;

use App\Models\QuickReply;
use App\Repositories\Interfaces\QuickReplyRepositoryInterface;

class QuickReplyRepository extends BaseRepository implements QuickReplyRepositoryInterface
{
    public function __construct(QuickReply $model)
    {
        parent::__construct($model);
    }
}
