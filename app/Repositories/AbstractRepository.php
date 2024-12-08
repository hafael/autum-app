<?php

namespace App\Repositories;

use App\Contracts\AbstractRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AbstractRepository implements AbstractRepositoryContract
{

    /**
     * @var Model $model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     * 
     * Inherited from AbstractRepositoryContract
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    /**
     * @inheritDoc
     * 
     * Inherited from AbstractRepositoryContract
     * 
     */
    public function paginated(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @inheritDoc
     * 
     * Inherited from AbstractRepositoryContract
     */
    public function simplePaginated(int $perPage = 15, array $columns = ['*']): Paginator
    {
        return $this->model->simplePaginate($perPage, $columns);
    }

    
    /**
     * @inheritDoc
     * 
     * Inherited from AbstractRepositoryContract
     */
    public function find($id, $columns = ['*']): Model
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @inheritDoc
     * 
     * Inherited from AbstractRepositoryContract
     */
    public function findBy(string $attribute, mixed $value, array $columns = ['*']): Model
    {
        return $this->model->where($attribute, $value)->first($columns);
    }

    /**
     * @inheritDoc
     * 
     * Inherited from AbstractRepositoryContract
     */
    public function search(string $attribute, mixed $value, array $columns = ['*']): Collection
    {
        return $this->model->where($attribute, $value)->get($columns);
    }

    /**
     * @inheritDoc
     * 
     * Inherited from AbstractRepositoryContract
     */
    public function searchPaginated(string $attribute, mixed $value, int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->where($attribute, $value)->paginate($perPage, $columns);
    }

    /**
     * @inheritDoc
     * 
     * Inherited from AbstractRepositoryContract
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * @inheritDoc
     * 
     * Inherited from AbstractRepositoryContract
     */
    public function update(array $data, $id): Model
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    /**
     * @inheritDoc
     * 
     * Inherited from AbstractRepositoryContract
     */
    public function updateBy(string $attribute, mixed $value, array $data): void 
    {
        $this->model->where($attribute, $value)->update($data);
    }

    /**
     * @inheritDoc
     * 
     * Inherited from AbstractRepositoryContract
     */
    public function delete($id): bool
    {
        return $this->model->destroy($id);
    }

    /**
     * @inheritDoc
     * 
     * Inherited from AbstractRepositoryContract
     */
    public function deleteBy(string $attribute, mixed $value, string $operator = '='): void
    {
        $this->model->where($attribute, $operator, $value)->delete();
    }

}
