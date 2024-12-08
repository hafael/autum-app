<?php

namespace App\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface AbstractRepositoryContract
{
    /**
     * Get all records from the database
     * 
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection<int, static>
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Get paginated records from the database
     * 
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginated(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Get paginated records from the database
     * 
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginated(int $perPage = 15, array $columns = ['*']): Paginator;

    
    /**
     * Find a record by its ID column attribute and return a last single result
     * 
     * @param string|int $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, $columns = ['*']): Model;

    /**
     * Find a record by a specific attribute and value and return a last single result
     * 
     * @param string $attribute
     * @param string $value
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findBy(string $attribute, mixed $value, array $columns = ['*']): Model;

    /**
     * Search for a record by a specific attribute and value and return multiple results
     * 
     * @param string $attribute
     * @param mixed $value
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection<int, static>
     */
    public function search(string $attribute, mixed $value, array $columns = ['*']): Collection;

    /**
     * Search for a record by a specific attribute and value and return a paginated result
     * 
     * @param string $attribute
     * @param string $value
     * @param int $perPage
     * @param array $columns
     * @return @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchPaginated(string $attribute, mixed $value, int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Create a new record in the database and return the model
     * 
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model;

    /**
     * Update a record in the database and return the updated model
     * 
     * @param array $data
     * @param int|string $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(array $data, $id): Model;

    /**
     * Update a record in the database by attribute/value and return boolean
     * 
     * @param string $attribute
     * @param mixed $value
     * @param array $data
     * @return void
     */
    public function updateBy(string $attribute, mixed $value, array $data): void;

    /**
     * Delete a record from the database by ID and return boolean.
     * 
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Delete a record from the database by attribute/value and return void.
     * 
     * @param string $attribute
     * @param mixed $value
     * @param string $operator
     * @return void
     */
    public function deleteBy(string $attribute, mixed $value, string $operator = '='): void;

    
}