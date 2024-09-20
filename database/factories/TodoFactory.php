<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $files = Storage::files('public/todos');

        if (count($files) === 0) {
            Storage::put('public/todos/placeholder1.jpg', '');
            Storage::put('public/todos/placeholder2.jpg', '');
            $files = Storage::files('public/todos');
        }

        $imagePath = count($files) > 0 ? collect($files)->random() : null;

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'image' => $imagePath ? Storage::url($imagePath) : null,
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'on_hold', 'canceled', 'archived']),
            'user_id' => User::factory(),
        ];
    }
}
