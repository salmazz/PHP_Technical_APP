<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use Database\Factories\TodoFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Todo::factory()->count(1000)->create();
    }
}
