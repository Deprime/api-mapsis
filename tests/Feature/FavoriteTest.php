<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Database\Factories\PostFactory;
use Database\Factories\UserFactory;
use Database\Seeders\PostStatusTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->seed(PostStatusTableSeeder::class);
  }

  public function testItCanAddPostToFavorites()
  {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $response = $this->actingAs($user)
      ->post("api/v1/app/posts/favorites/{$post->id}");

    $response->assertJson(['message' => 'Added to favorites']);
    $this->assertTrue($user->favoritePosts->contains($post));
  }

  public function testItCannotAddDuplicatePostToFavorites()
  {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    // Add the post to favorites first
    $user->favoritePosts()->attach($post->id);

    $response = $this->actingAs($user)->post("api/v1/app/posts/favorites/{$post->id}");

    $response->assertJson(['message' => 'Post already in favorites']);
  }

  public function testItCanRemovePostFromFavorites()
  {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $user->favoritePosts()->attach($post->id);

    $response = $this->actingAs($user)->delete("api/v1/app/posts/favorites/{$post->id}");

    $response->assertJson(['message' => 'Removed from favorites']);
    $this->assertFalse($user->favoritePosts->contains($post));
  }

  public function testItCanGetUserFavorites()
  {
    $user = User::factory()->create();
    $posts = Post::factory()->count(3)->create();

    $user->favoritePosts()->attach($posts->pluck('id'));

    $response = $this->actingAs($user)
      ->get("api/v1/app/posts/favorites/");

    $response->assertJsonStructure([
      '*' => [
        'id',
        'type_id',
        'category_id',
      ],
    ]);
  }
}
