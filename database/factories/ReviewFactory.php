<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      return [
        'user_id' => function () {
          return User::factory()->create()->id;
        },
        'author_id' => function () {
          return User::factory()->create()->id;
        },
        'content' => $this->faker->paragraph,
        'mark' => $this->faker->numberBetween(1, 5),
      ];
    }
}
