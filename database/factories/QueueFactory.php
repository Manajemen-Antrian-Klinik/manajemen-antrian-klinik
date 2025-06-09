<?php

namespace Database\Factories;

use App\Models\Queue;
use Illuminate\Database\Eloquent\Factories\Factory;

class QueueFactory extends Factory
{
    protected $model = Queue::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),  // Menggunakan factory User
            'queue_number' => $this->faker->randomNumber(),
            'complaint' => $this->faker->optional()->sentence(),
            'date' => $this->faker->date(),
            'status' => 'Menunggu',
        ];
    }
}