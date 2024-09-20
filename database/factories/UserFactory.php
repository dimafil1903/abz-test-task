<?php

namespace Database\Factories;

use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => '+380' . $this->faker->unique()->numberBetween(100000000, 999999999),
            'position_id' => Position::inRandomOrder()->first()->id ?? Position::factory(),
            'photo' => 'images/default.jpg',
        ];
    }

}
