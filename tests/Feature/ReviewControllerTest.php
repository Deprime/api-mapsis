<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\ReviewComment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
  use DatabaseTransactions;

  protected function setUp(): void
  {
    parent::setUp();
  }

  public function testGetReviewsByProfile()
  {
    $user = User::factory()->create();
    $author = User::factory()->create();
    $review = Review::factory()->create(['user_id' => $user->id, 'author_id' => $author->id]);

    $response = $this->actingAs($user)->get('api/v1/app/profile/' . $user->id . '/reviews');

    $response->assertStatus(200);
    $response->assertJsonStructure([
      '*' => [
        'id',
        'user_id',
        'author_id',
        'content',
        'mark',
        'created_at',
        'updated_at',
        'deleted_at',
      ],
    ]);
  }

  public function testGetMyReviews()
  {
    $user = User::factory()->create();
    $author = User::factory()->create();
    $review = Review::factory()->create(['user_id' => $user->id, 'author_id' => $user->id]);

    $response = $this->actingAs($user)->get('api/v1/app/profile/reviews');

    $response->assertStatus(200);
    $response->assertJsonStructure([
      '*' => [
        'id',
        'user_id',
        'author_id',
        'content',
        'mark',
        'created_at',
        'updated_at',
        'deleted_at',
      ],
    ]);
  }

  public function testCreateReview()
  {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->post('api/v1/app/profile/reviews', [
      'user_id' => $user->id,
      'content' => 'Test review content',
      'mark' => 5,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
      'id',
      'user_id',
      'author_id',
      'content',
      'mark',
      'created_at',
      'updated_at',
    ]);
  }

  public function testCreateReviewComment()
  {
    $user = User::factory()->create();
    $author = User::factory()->create();
    $review = Review::factory()->create(['user_id' => $user->id, 'author_id' => $author->id]);

    $response = $this->actingAs($user)->post('api/v1/app/profile/reviews/comment/' . $review->id, [
      'content' => 'Test review comment',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
      'id',
      'review_id',
      'author_id',
      'content',
      'created_at',
      'updated_at',
    ]);
  }

  public function testUpdateMyReview()
  {
    $user = User::factory()->create();
    $review = Review::factory()->create(['author_id' => $user->id]);

    $response = $this->actingAs($user)->put("api/v1/app/profile/reviews/{$review->id}", [
      'content' => 'Updated content',
      'mark' => 5,
    ]);

    $response->assertStatus(200)
      ->assertJsonStructure(['id', 'content', 'mark', /* ... другие поля */]);

    $this->assertDatabaseHas('review', [
      'id' => $review->id,
      'content' => 'Updated content',
      'mark' => 5,
    ]);
  }

  public function testDeleteMyReview()
  {
    $user = User::factory()->create();
    $review = Review::factory()->create(['author_id' => $user->id]);

    $response = $this->actingAs($user)->delete("api/v1/app/profile/reviews/{$review->id}");

    $response->assertStatus(200)
      ->assertJson(['message' => 'Review deleted.']);

    $this->assertSoftDeleted('review', ['id' => $review->id]);
  }

  public function testUpdateMyReviewComment()
  {
    $user = User::factory()->create();
    $comment = ReviewComment::factory()->create(['author_id' => $user->id]);

    $response = $this->actingAs($user)->put("api/v1/app/profile/reviews/comment/{$comment->id}", [
      'content' => 'Updated comment content',
    ]);

    $response->assertStatus(200)
      ->assertJsonStructure(['id', 'content']);

    $this->assertDatabaseHas('review_comment', [
      'id' => $comment->id,
      'content' => 'Updated comment content',
    ]);
  }

  public function testDeleteMyReviewComment()
  {
    $user = User::factory()->create();
    $comment = ReviewComment::factory()->create(['author_id' => $user->id]);

    $response = $this->actingAs($user)->delete("api/v1/app/profile/reviews/comment/{$comment->id}");

    $response->assertStatus(200)
      ->assertJson(['message' => 'Comment deleted.']);

    $this->assertSoftDeleted('review_comment', ['id' => $comment->id]);
  }

}
