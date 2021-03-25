<?php

namespace Database\Factories;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingFactory extends Factory
{
    protected $model = Meeting::class;

    public function definition()
    {
        return [
            'name'          => $this->faker->sentence,
            'description'   => $this->faker->paragraph,
        ];
    }
}