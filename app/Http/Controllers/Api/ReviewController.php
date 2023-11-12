<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewCommentCreateRequest;
use App\Http\Requests\Review\ReviewCreateRequest;
use App\Http\Requests\Review\UpdateReviewCommentRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Models\Review;
use App\Models\ReviewComment;
use Illuminate\Http\{
  Request,
  JsonResponse,
};
use Symfony\Component\HttpFoundation\Response;
use function League\Flysystem\type;

class ReviewController extends Controller
{
  protected const REVIEW_RELATIONS = ['author', 'user', 'comment'];

  /**
   * Get revs by selected profile
   * @param Request $request
   * @param int $profile_id
   * @return JsonResponse
   */
  public function getReviewsByProfile(Request $request,int $profile_id): JsonResponse
  {
    $reviews = Review::query()
      ->with(static::REVIEW_RELATIONS)
      ->where('user_id', $profile_id)
      ->get();

    if ($reviews) {
      return response()->json($reviews);
    }

    return response()->json([], Response::HTTP_NOT_FOUND);
  }

  /**
   * Get my revs
   * @param Request $request
   * @return JsonResponse
   */
  public function getMyReviews(Request $request): JsonResponse
  {
    $user = $request->user();
    $reviews = Review::query()
      ->with(static::REVIEW_RELATIONS)
      ->where('user_id', $user->id)
      ->get();

    if ($reviews) {
      return response()->json($reviews);
    }

    return response()->json([], Response::HTTP_NOT_FOUND);
  }

  /**
   * Create Review
   * @param ReviewCreateRequest $request
   * @return JsonResponse
   */
  public function createReview(ReviewCreateRequest $request): JsonResponse
  {
    $input = $request->validated();
    $input['author_id'] = $request->user()->id;

    $review = Review::create($input);

    return response()->json($review);
  }

  /**
   * Update Review
   * @param UpdateReviewRequest $request
   * @param int $review_id
   * @return JsonResponse
   */
  public function updateMyReview(UpdateReviewRequest $request, int $review_id)
  {
    $user = $request->user();

    $review = Review::query()->where('id', $review_id)->where('author_id', $user->id)->firstOrFail();

    $review->update(array_filter($request->all(), fn ($value) => $value !== null));

    return response()->json($review);
  }

  /**
   * Delete Review
   * @param Request $request
   * @param int $review_id
   * @return JsonResponse
   */
  public function deleteMyReview(Request $request, int $review_id)
  {
    $user = $request->user();

    $review = Review::query()->where('id', $review_id)->where('author_id', $user->id)->firstOrFail();

    $review->delete();

    return response()->json(['message' => 'Review deleted.']);
  }


}
