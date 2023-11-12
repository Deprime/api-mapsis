<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewCommentCreateRequest;
use App\Http\Requests\Review\UpdateReviewCommentRequest;
use App\Models\Review;
use App\Models\ReviewComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewCommentController extends Controller
{

  /**
   * Create Review comment
   * @param ReviewCommentCreateRequest $request
   * @param int $review_id
   * @return JsonResponse
   */
  public function create(ReviewCommentCreateRequest $request, int $review_id): JsonResponse
  {
    $input = $request->validated();

    $review = Review::query()->where('id', $review_id)->firstOrFail();

    $reviewComment = ReviewComment::query()->where('review_id', $review_id)->first();

    if(!is_null($reviewComment)){
      return response()->json([], Response::HTTP_PRECONDITION_FAILED);
    }

    $input['author_id'] = $request->user()->id;
    $input['review_id'] = $review->id;

    $review = ReviewComment::create($input);

    return response()->json($review);
  }

  /**
   * Update Review Comment
   * @param UpdateReviewCommentRequest $request
   * @param int $comment_id
   * @return JsonResponse
   */
  public function updateMyComment(UpdateReviewCommentRequest $request, int $comment_id)
  {
    $user = $request->user();

    $review = ReviewComment::query()->where('id', $comment_id)->where('author_id', $user->id)->firstOrFail();

    $deleteFlag = $request->input('delete', false);

    if ($deleteFlag) {
      $review->delete();
      return response()->json(['message' => 'Comment deleted.']);
    }

    $review->update(array_filter($request->all(), fn ($value) => $value !== null));

    return response()->json($review);
  }

  /**
   * Delete Review Comment
   * @param Request $request
   * @param int $comment_id
   * @return JsonResponse
   */
  public function deleteMyComment(Request $request, int $comment_id)
  {
    $user = $request->user();

    $review = ReviewComment::query()->where('id', $comment_id)->where('author_id', $user->id)->firstOrFail();

    $review->delete();

    return response()->json(['message' => 'Comment deleted.']);
  }
}
