<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = $this->faker->randomElement([Task::PENDING, Task::WORKING, Task::FINISHED]);
        $user = User::query()->count();
        return [
            'user_id' => $this->faker->numberBetween(1, $user),
            'name' => $this->faker->sentence(3),
            'status' => $status,
            'finished_at' => $this->faker->date()
        ];
    }
}
