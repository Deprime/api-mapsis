<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Image;

use \App\Http\Requests\Photo\{
  PhotoCreateRequest,
};

use App\Models\{
  User,
  Post,
  Photo,
};

use App\Services\{
  ThumbnailService
};

class PhotoController extends Controller
{
  /**
   * List
   * @param  int $post_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function list(Request $request, $post_id): JsonResponse
  {
    $photos = Photo::query()
                      ->where('post_id', $post_id)
                      ->get();

    if ($photos) {
      return response()->json([
        'photos'=> $photos,
        'max_size' => config('image.max_size')
      ]);
    }
    return response()->json([], Response::HTTP_NOT_FOUND);
  }

  /**
   * Create
   * @param  int $post_id
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function create(PhotoCreateRequest $request, $post_id): JsonResponse
  {
    $input = $request->validated();
    $user  = $request->user();
    $photos = $input['photos'];

    // Checking the post
    $post = Post::query()
      ->where('id', $post_id)
      ->where('author_id', $user->id)
      ->first();

    if (!$post) {
      return response()->json([], Response::HTTP_NOT_FOUND);
    }

    // Searching poster
    $poster = Photo::query()
      ->where('post_id', $post->id)
      ->where('is_poster', 1)
      ->first();

    $bunny_dir_path = "posts/{$post_id}";
    Storage::disk('bunnycdn')->makeDirectory($bunny_dir_path);

    $photo_list = [];

    foreach ($photos as $k => $image) {

      $extension = $image->extension();
      $time      = time();

      // Files processing by service
      $name = $post_id . '-' . $time . $k . '.' . $extension;
      $bunny_path = ThumbnailService::upload($post->id, 1200, 1200, $image->path(), $name);

      // Small 96*96 File process
      $thumbnail_name = $post_id . '-' . $time . $k . '-sm.' . $extension;
      ThumbnailService::upload($post->id, 96, 96, $image->path(), $thumbnail_name);

      $is_poster = ($k === 0 && !$poster) ? 1 : 0;
      $photo_list[] = Photo::create([
        'post_id'   => $post->id,
        'author_id' => $user->id,
        'extension' => $image->extension(),
        'is_poster' => $is_poster,
        'name'      => $name,
        'url'       => $bunny_path,
      ]);
    }

    return response()->json($photo_list);
  }

  /**
   * Delete photo
   * @param  int $post_id
   * @param  int $photo_id
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function delete(Request $request, $post_id, $photo_id): JsonResponse
  {
    $user = $request->user();

    $post = Post::query()
      ->where('author_id', $user->id)
      ->where('id', $post_id)
      ->first();

    if ($post) {
      $photo = Photo::query()
        ->where('id', $photo_id)
        ->where('post_id', $post_id)
        ->first();

      if ($photo) {
        // If photo is poster
        if ($photo->is_poster) {
          $not_poster_photo = Photo::query()
            ->where('is_poster', 0)
            ->where('post_id', $post_id)
            ->first();

          if ($not_poster_photo) {
            $not_poster_photo->update(['is_poster' => 1]);
          }
          // else {
          //   return response()->json([], Response::HTTP_NOT_FOUND);
          // }
        }

        try {
          Storage::disk('bunnycdn')->delete($photo->url);
          $photo->delete();
          return response()->json([], Response::HTTP_ACCEPTED);
        }
        catch (Exception $e) {
          return response()->json([], Response::HTTP_SERVICE_UNAVAILABLE);
        }
      }
    }

    return response()->json([], Response::HTTP_NOT_FOUND);
  }

  /**
   * Set poster photo
   * @param  int $post_id
   * @param  int $photo_id
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function setPoster(Request $request, $post_id, $photo_id): JsonResponse
  {
    $user  = $request->user();

    $post = Post::query()
                    ->where('author_id', $user->id)
                    ->where('id', $post_id)
                    ->first();

    if ($post) {
      $photo = Photo::query()
                    ->where('post_id', $post_id)
                    ->where('id', $photo_id)
                    ->first();

      if ($photo) {
        Photo::where('post_id', $post_id)
              ->where('is_poster', 1)
              ->update(['is_poster' => 0]);

        $photo->update(['is_poster' => 1]);
        return response()->json($photo);
      }
    }

    return response()->json([], Response::HTTP_NOT_FOUND);
  }
}
