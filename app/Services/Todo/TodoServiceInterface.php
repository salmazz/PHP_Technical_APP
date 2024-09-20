<?php
namespace App\Services\Todo;

use App\Models\Todo;

interface TodoServiceInterface
{
    public function getAllTodos();
    public function getTodoById($id);
    public function createTodo(array $data);
    public function updateTodo($id, array $data);
    public function deleteTodo($id);
    public function updateTodoStatus(int $id, string $status): ?Todo;
}
