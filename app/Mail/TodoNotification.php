<?php

namespace App\Mail;

use App\Models\Todo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TodoNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $todo , $action;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Todo $todo, $action)
    {
        $this->todo = $todo;
        $this->action = $action;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Todo {$this->action}")
            ->view('emails.todo-notification');
    }
}
