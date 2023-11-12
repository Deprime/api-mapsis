<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{
  Request,
  JsonResponse,
};
use Symfony\Component\HttpFoundation\Response;

use \App\Http\Requests\Post\{
  PostCreateRequest,
  PostUpdateRequest,
  PostUpdateStatusRequest
};

use App\Models\{
  PostType,
  User,
  Post,
  PostStatus
};

class PostController extends Controller
{
  protected const LIST_RELATIONS = ['author', 'status', 'poster'];
  protected const ITEM_RELATIONS = ['author', 'status', 'photos', 'category'];

  /**
   * List
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function list(Request $request): JsonResponse
  {
    $user = $request->user();
    $post_list = Post::query()
                      ->with(static::LIST_RELATIONS)
                      ->where('author_id', $user->id)
                      ->get();

    return response()->json([
      'post_list' => $post_list,
    ]);
  }

  /**
   * Get
   * @param  int $post_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function get(Request $request,int $post_id): JsonResponse
  {
    $user = $request->user();
    $post = Post::query()
                      ->with(static::ITEM_RELATIONS)
                      ->where('id', $post_id)
                      ->where('author_id', $user->id)
                      ->first();

    if ($post) {
      return response()->json($post);
    }
    return response()->json([], Response::HTTP_NOT_FOUND);
  }

  /**
   * Create
   * @param PostCreateRequest $request
   * @return JsonResponse
   */
  public function create(PostCreateRequest $request): JsonResponse
  {
    $input = $request->validated();
    $user  = $request->user();
    $draftStatus = PostStatus::findOrFail(1); // Draft
    $draftType   = PostType::findOrFail(2);     // Service

    $input['author_id'] = $user->id;
    $input['status_id'] = $draftStatus->id;
    $input['type_id'] = $draftType->id;

    $post = Post::create($input);

    return response()->json($post);
  }

  /**
   * Update
   * @param PostUpdateRequest $request
   * @param int $post_id
   * @return JsonResponse
   */
  public function update(PostUpdateRequest $request, int $post_id): JsonResponse
  {
    $input = $request->validated();
    $user  = $request->user();

    $post  = Post::where("id", $post_id)
                 ->where("author_id", $user->id)
                 ->firstOrFail();

    $post->update($input);
    return response()->json($post);
  }

  /**
   * Set status
   * @param  int $post_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function setStatus(PostUpdateStatusRequest $request, $post_id): JsonResponse
  {
    $user  = $request->user();
    $input = $request->validated();

    $post = Post::query()
                      ->with(["status"])
                      ->where('id', $post_id)
                      ->where('author_id', $user->id)
                      ->first();

    if ($post) {
      $post_status = PostStatus::find($input['status']);

      if ($post_status) {
        $data = ['status_id' => $post_status->id];

        // If it's first publishing
        if ($post_status->id === 2 && !$post->published_at) {
          $data['published_at'] = date("Y-m-d H:i:s");
        }

        $post->update($data);
        return response()->json($post_status);
      }
    }
    return response()->json([], Response::HTTP_NOT_FOUND);
  }

  /**
   * Delete
   * @param Request $request
   * @return JsonResponse
   */
  public function delete(Request $request, int $post_id): JsonResponse
  {
    $user = $request->user();
    $post  = Post::where("id", $post_id)->where("author_id", $user->id)->firstOrFail();

    $post->delete();

    return response()->json();
  }
}
