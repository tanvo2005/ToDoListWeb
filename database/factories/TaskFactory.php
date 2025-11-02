<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::first();// lấy user đầu tiên và để truyền cho biến user
        return [
            'title'=>$this->faker->sentence(3),
            'description'=>$this->faker->paragraph(),
            'status'=>$this->faker->randomElement([
                Task::CHUA_LAM,
                Task::DANG_LAM,
                Task::DA_XONG
            ]),
            'due_date'=>$this->faker->dateTimeBetween('now', '+1 month'),
            'user_id'=> $user->id
        ];
    }
}
