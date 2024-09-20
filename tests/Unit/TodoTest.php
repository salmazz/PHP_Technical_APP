<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Jobs\SendTodoNotification;
use App\Mail\TodoNotification;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_todo_and_dispatches_an_email_job()
    {
        Mail::fake();
        Queue::fake();

        Mail::assertNothingSent();

        $user = User::factory()->create();

        $data = [
            'title' => 'Test Todo',
            'description' => 'Test Description',
            'status' => 'pending',
            'user_id' => $user->id,
        ];

        $this->actingAs($user);

        $todo = Todo::create($data);

        Queue::assertPushed(SendTodoNotification::class, function ($job) use ($todo) {
            return $job->todo->id === $todo->id && $job->action === 'created';
        });
    }

    #[Test]
    public function it_updates_a_todo_and_dispatches_an_email_job()
    {
        Mail::fake();
        Queue::fake();

        $user = User::factory()->create();

        $todo = Todo::factory()->create(['user_id' => $user->id]);

        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => 'completed',
        ];

        $this->actingAs($user);

        $todo->update($data);

        Queue::assertPushed(SendTodoNotification::class, function ($job) use ($todo) {
            return $job->todo->id === $todo->id && $job->action === 'updated';
        });
    }
}
