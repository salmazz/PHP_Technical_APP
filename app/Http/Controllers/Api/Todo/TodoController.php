<?php

namespace App\Http\Controllers\Api\Todo;

use App\Common\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Todo\StoreTodoRequest;
use App\Http\Requests\Todo\UpdateStatusRequest;
use App\Http\Resources\Todo\TodoCollection;
use App\Http\Resources\Todo\TodoResource;
use App\Services\Todo\TodoServiceInterface;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Todos", description="API Endpoints for Managing Todos")
 */
class TodoController extends Controller
{
    protected $todoService;

    public function __construct(TodoServiceInterface $todoService)
    {
        $this->todoService = $todoService;
    }

    /**
     * @OA\Get(
     *     path="/api/todos",
     *     summary="Get all todos",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Filter by title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="created_at",
     *         in="query",
     *         description="Filter by created date",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of todos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Todo"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $filters = [
            'title' => $request->input('title'),
            'status' => $request->input('status'),
            'created_at' => $request->input('created_at'),
        ];

        $perPage = $request->input('per_page', 10);

        $todos = $this->todoService->getAllTodos(array_filter($filters), $perPage);

        return (new TodoCollection($todos))
            ->additional(['message' => 'Todos retrieved successfully']);
    }

    /**
     * @OA\Post(
     *     path="/api/todos",
     *     summary="Create a new todo",
     *     tags={"Todos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="image", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Todo created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Todo")
     *     )
     * )
     */
    public function store(StoreTodoRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('todos');
        }

        $todo = $this->todoService->createTodo($data);

        return (new TodoResource($todo))
            ->additional(['message' => 'Todo created successfully'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/api/todos/{id}",
     *     summary="Get a specific todo",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the todo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Todo retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Todo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Todo not found"
     *     )
     * )
     */
    public function show($id)
    {
        $todo = $this->todoService->getTodoById($id);

        if ($todo) {
            return (new TodoResource($todo))
                ->additional(['message' => 'Todo retrieved successfully']);
        }

        return ResponseHelper::error('Todo not found', 404);
    }

    /**
     * @OA\Put(
     *     path="/api/todos/{id}",
     *     summary="Update an existing todo",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the todo to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="image", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Todo updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Todo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Todo not found"
     *     )
     * )
     */
    public function update(StoreTodoRequest $request, $id)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('todos');
        }

        $todo = $this->todoService->updateTodo($id, $data);

        if ($todo) {
            return (new TodoResource($todo))
                ->additional(['message' => 'Todo updated successfully']);
        }

        return ResponseHelper::error('Unable to update Todo', 400);
    }

    /**
     * @OA\Delete(
     *     path="/api/todos/{id}",
     *     summary="Delete a specific todo",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the todo to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Todo deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Todo deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Todo not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $deleted = $this->todoService->deleteTodo($id);

        if ($deleted) {
            return ResponseHelper::success('Todo deleted successfully', [], 200);
        }

        return ResponseHelper::error('Unable to delete Todo', 400);
    }

    /**
     * @OA\Patch(
     *     path="/api/todos/{id}/status",
     *     summary="Update the status of a specific todo",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the todo to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 description="New status of the todo",
     *                 example="completed",
     *                 enum={"pending", "in_progress", "completed", "canceled"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Todo status updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Status of the response", example="success"),
     *             @OA\Property(property="message", type="string", description="Response message", example="Todo status updated successfully"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Todo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Status of the response", example="error"),
     *             @OA\Property(property="message", type="string", description="Response message", example="Unable to update Todo status")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Todo not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Status of the response", example="error"),
     *             @OA\Property(property="message", type="string", description="Response message", example="Todo not found")
     *         )
     *     )
     * )
     */
    public function updateStatus(UpdateStatusRequest $request,$id)
    {

        $todo = $this->todoService->updateTodoStatus($id, $request->input('status'));

        if ($todo) {
            return ResponseHelper::success('Todo status updated successfully', new TodoResource($todo));
        }

        return ResponseHelper::error('Unable to update Todo status', 400);

    }
}
