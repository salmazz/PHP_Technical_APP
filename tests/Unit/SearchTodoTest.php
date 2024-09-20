<?php

namespace Tests\Unit;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SearchTodoTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_searches_todos_by_title()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Todo::factory()->create(['title' => 'Buy Groceries', 'user_id' => $user->id]);
        Todo::factory()->create(['title' => 'Fix Car', 'user_id' => $user->id]);

        $response = $this->getJson('/api/todos?title=Buy');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Buy Groceries']);
    }

    #[Test]
    public function it_filters_todos_by_status()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // إنشاء تودوز لاختبار الفلترة حسب الحالة
        Todo::factory()->create(['status' => 'completed', 'user_id' => $user->id]);
        Todo::factory()->create(['status' => 'pending', 'user_id' => $user->id]);

        $response = $this->getJson('/api/todos?status=completed');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['status' => 'completed']);
    }

    #[Test]
    public function it_filters_todos_by_created_at_date()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Todo::factory()->create(['created_at' => '2024-09-10', 'user_id' => $user->id]);
        Todo::factory()->create(['created_at' => '2024-09-11', 'user_id' => $user->id]);

        $response = $this->getJson('/api/todos?created_at=2024-09-11');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['created_at' => '2024-09-11 00:00:00']);
    }

    #[Test]
    public function it_gets_paginated_todos()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Todo::factory()->count(15)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/todos?per_page=10');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure(['data', 'links', 'meta']);
    }
}
