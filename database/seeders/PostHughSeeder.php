<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class PostHughSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Post::factory()->count(70)->state(new Sequence(
        fn ($sequence) => [
          'category_id' => Category::all()->random(),
          'currency_id' => Currency::all()->random(),
        ],
      ))->create();
    }
}
