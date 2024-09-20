<?php

namespace App\Jobs;

use App\Mail\TodoNotification;
use App\Models\Todo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTodoNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $todo, $action;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Todo $todo, $action)
    {
        $this->todo = $todo;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->todo->user;
        if ($user) {
            Mail::to($user->email)->send(new TodoNotification($this->todo, $this->action));
        }
    }
}
