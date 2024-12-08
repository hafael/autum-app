<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all()
    {
        return $this->userRepository->all();
    }

    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    public function findBy($attribute, $value)
    {
        return $this->userRepository->findBy($attribute, $value);
    }

    public function paginated($perPage = 15)
    {
        return $this->userRepository->paginated($perPage);
    }

    public function simplePaginated($perPage = 15)
    {
        return $this->userRepository->simplePaginated($perPage);
    }

    public function search($attribute, $value)
    {
        return $this->userRepository->search($attribute, $value);
    }

    public function searchPaginated($attribute, $value, $perPage = 15)
    {
        return $this->userRepository->searchPaginated($attribute, $value, $perPage);
    }

    public function create($data)
    {
        return $this->userRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->userRepository->update($id, $data);
    }

    public function updateBy($attribute, $value, $data)
    {
        return $this->userRepository->updateBy($attribute, $value, $data);
    }

    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }

    public function deleteBy($attribute, $value)
    {
        return $this->userRepository->deleteBy($attribute, $value);
    }
}
