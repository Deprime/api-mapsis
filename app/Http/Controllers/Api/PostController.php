<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostCreateRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Storage;

// use App\Helpers\SmsAero;
// use App\Services\ProfileService;

// use \App\Http\Requests\post\{
//   postCreateRequest,
// };

use App\Models\{PostType, User, Post, PostStatus};

class PostController extends Controller
{
  protected const LIST_RELATIONS = ['author', 'participants', 'status'];
  protected const ITEM_RELATIONS = ['author', 'participants', 'status'];

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
  public  function create(PostCreateRequest $request): JsonResponse
  {
    $user = $request->user();
    $draftStatus  = PostStatus::findOrFail(1); // Draft
    $draftType  = PostType::findOrFail(2);     // Service


    $input = $request->validated();

    $input['author_id'] = $user->id;
    $input['status_id'] = $draftStatus->id;
    $input['type_id'] = $draftType->id;
    $input['coords'] = explode(",", $request->coords);

    $post = Post::create($input);

    return response()->json($post);
  }

  /**
   * Update
   * @param PostUpdateRequest $request
   * @param int $post_id
   * @return JsonResponse
   */
  public  function update(PostUpdateRequest $request,int $post_id): JsonResponse
  {
    $user = $request->user();
    $post  = Post::where("id", $post_id)->where("author_id", $user->id)->firstOrFail();

    $post = $post->fill($request->all());

    $post->save();

    return response()->json($post);
  }

  /**
   * Delete
   * @param Request $request
   * @return JsonResponse
   */
  public  function delete(Request $request, int $post_id): JsonResponse
  {
    $user = $request->user();
    $post  = Post::where("id", $post_id)->where("author_id", $user->id)->firstOrFail();

    $post->delete();

    return response()->json();
  }
}
