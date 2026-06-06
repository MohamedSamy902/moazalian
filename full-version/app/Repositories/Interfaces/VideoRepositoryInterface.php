<?php

namespace App\Repositories\Interfaces;

interface VideoRepositoryInterface extends BaseRepositoryInterface
{
    public function getPublishedPaginated(int $perPage = 10);
}
