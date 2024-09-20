<?php
namespace App\Services\Todo;

use App\Repositories\Todo\TodoRepositoryInterface;

class TodoService implements TodoServiceInterface
{
    protected $todoRepository;

    public function __construct(TodoRepositoryInterface $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function getAllTodos(array $filters = [], $perPage = 10)
    {
        return $this->todoRepository->all($filters, $perPage);
    }

    public function createTodo(array $data)
    {
        return $this->todoRepository->create($data);
    }

    public function getTodoById($id)
    {
        return $this->todoRepository->find($id);
    }

    public function updateTodo($id, array $data)
    {
        return $this->todoRepository->update($id, $data);
    }

    public function deleteTodo($id)
    {
        return $this->todoRepository->delete($id);
    }

    public function updateTodoStatus(int $id, string $status): ?\App\Models\Todo
    {
        return $this->todoRepository->updateStatus($id, $status);
    }

}
