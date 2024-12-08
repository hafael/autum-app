<?php

namespace App\Repositories;

use App\Contracts\AbstractRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends AbstractRepository
{

    /**
     * @var Model $model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

}
