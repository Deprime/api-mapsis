<?php

namespace Database\Factories;

use App\Models\Photo;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Image;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    private int $k = 10000000000000000;

    private array $coords = [
      'yvn' => [
        'y' => [40.12490275956479,40.22174863994517],
        'x' => [44.44009713261995,44.54207243998157]
      ],
      'msc' => [
        'y' => [55.5152682150975,55.9511577416233],
        'x' => [37.3148211057577,37.8820957216494]
      ],
      'kiev' => [
        'y' => [50.3904641594226,50.5257680474503],
        'x' => [30.3283516615849,30.6704612232505]
      ],
      'bali' => [
        'y' => [-8.7171850112338540,-8.6154741168684410],
        'x' => [115.16697959005188,115.25977940259503]
      ]
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
      return [
        'type_id' => 2,
        'author_id' => 2,
        'status_id' => 2,
        'currency_id' => 1,
        'title' => fake()->sentence(3),
        'description' => fake()->text(256),
        'address' => fake()->address(),
        'suggested_address' => fake()->address(),
        'coords' => $this->randomCoords(),
        'price' => fake()->randomDigit(),
        'published_at' => now(),
        'start_at' => Date::yesterday(),
        'finish_at' => Date::tomorrow(),
        'promoted_at' => now(),
      ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
      return $this->afterCreating(function (Post $post) {

        $file_path  = public_path('thumbs');
        $image_name = $post->id . '-' . time(). 'FACTORY.jpg';

        $img = Image::make('https://picsum.photos/1200/1200.jpg');

        $imageFile = $img->resize(1200, 1200, function ($const) {
          $const->aspectRatio();
        })->save($file_path . DIRECTORY_SEPARATOR . $image_name);

        $bunny_path = "posts/{$post->id}/{$image_name}";

        Storage::disk('bunnycdn')->makeDirectory("posts/{$post->id}");
        Storage::disk('bunnycdn')->put($bunny_path, $imageFile);

        Photo::factory(1)->create([
          'post_id'   => $post->id,
          'name'      => $image_name,
          'url'       => $bunny_path,
        ]);
      });
    }

    /**
     * Define the model's default state.
     *
     * @return array<float, mixed>
     */
    public function randomCoords(string $name = 'bali')
    {
      $current = $this->coords[$name];
      $response = [];

      foreach ($current as $row){
        $response[] = (mt_rand($row[0] * $this->k , $row[1] * $this->k)) / $this->k;
      }

      return $response;
    }
}


