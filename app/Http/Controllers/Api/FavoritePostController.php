<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FavoritePostController extends Controller
{

  public function list(Request $request): JsonResponse
  {
    $user  = $request->user();

    return response()->json($user->favoritePosts);
  }

  public function create(Request $request, int $post_id): JsonResponse
  {
    $user  = $request->user();
    $post = Post::find($post_id);

    if (!$post) {
      return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
    }

    if ($user->favoritePosts()->where('post_id', $post_id)->exists()) {
      return response()->json(['message' => 'Post already in favorites'], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    $user->favoritePosts()->attach($post_id);
    return response()->json(['message' => 'Added to favorites']);
  }

  public function delete(Request $request, int $post_id): JsonResponse
  {
    $user  = $request->user();
    $post = Post::find($post_id);

    if (!$post) {
      return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
    }

    if (!$user->favoritePosts()->where('post_id', $post_id)->exists()) {
      return response()->json(['message' => 'Post is not in favorites'], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    $user->favoritePosts()->detach($post_id);
    return response()->json(['message' => 'Removed from favorites']);
  }
}
