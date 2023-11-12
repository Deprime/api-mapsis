<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\ReviewComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $users = User::factory()->count(10)->create();

      $users->each(function ($user) {
        $reviews = Review::factory()->count(2)->create(['user_id' => $user->id]);

        $reviews->each(function ($review) {
          ReviewComment::factory()->count(1)->create([
            'review_id' => $review->id,
            'author_id' => User::factory()->create()->id,
          ]);
        });
      });
    }
}
