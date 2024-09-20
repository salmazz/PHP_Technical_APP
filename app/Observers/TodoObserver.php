<?php

namespace App\Observers;

use App\Jobs\SendTodoNotification;
use App\Models\Todo;
use Illuminate\Support\Facades\Log;

class TodoObserver
{
    /**
     * Handle the Todo "created" event.
     */
    public function created(Todo $todo): void
    {
        SendTodoNotification::dispatch($todo, 'created');
    }

    /**
     * Handle the Todo "updated" event.
     */
    public function updated(Todo $todo): void
    {
        SendTodoNotification::dispatch($todo, 'updated');
    }

    /**
     * Handle the Todo "deleted" event.
     */
    public function deleted(Todo $todo): void
    {
        //
    }

    /**
     * Handle the Todo "restored" event.
     */
    public function restored(Todo $todo): void
    {
        //
    }

    /**
     * Handle the Todo "force deleted" event.
     */
    public function forceDeleted(Todo $todo): void
    {
        //
    }
}
