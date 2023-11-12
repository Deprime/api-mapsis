<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\ReviewComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReviewComment>
 */
class ReviewCommentFactory extends Factory
{

  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = ReviewComment::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'review_id' => function () {
        return Review::factory()->create()->id;
      },
      'author_id' => function () {
        return User::factory()->create()->id;
      },
      'content' => $this->faker->paragraph,
    ];
  }
}
