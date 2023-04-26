<?php

namespace Database\Factories;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => fake()->title,
            'description' => fake()->text(200),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
