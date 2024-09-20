<?php
namespace App\Providers;

use App\Services\Todo\TodoService;
use App\Services\Todo\TodoServiceInterface;
use App\Services\User\AuthService;
use App\Services\User\AuthServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Todo\TodoRepositoryInterface;
use App\Repositories\Todo\TodoRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(TodoRepositoryInterface::class, TodoRepository::class);
        $this->app->bind(TodoServiceInterface::class, TodoService::class);
    }
}
