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
      return response()->json($photos);
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

    // Files processing
    $file_path  = public_path('/thumbs');
    $path_list  = [];
    $photo_list = [];

    foreach ($photos as $k => $image) {
      $image_name = $post->id . '-' . time(). $k . '.' . $image->extension();
      $image_sm_name = $post->id . '-' . time(). $k . '-sm.' . $image->extension();

      $img = Image::make($image->path());
      $imgSm = Image::make($image->path());

      $full_path = $file_path . '/' . $image_name;
      $imageFile = $img->resize(1200, 1200, function ($const) {
        $const->aspectRatio();
      })->save($full_path);

      $imageSmFile = $imgSm->resize(96, 96, function ($const) {
        $const->aspectRatio();
      })->save($full_path);

      // Add full file path to array for deletion
      $path_list[] = $full_path;

      $bunny_dir_path = "posts/{$post_id}";
      $bunny_path = "posts/{$post_id}/{$image_name}";
      $bunny_sm_path = "posts/{$post_id}/{$image_sm_name}";

      Storage::disk('bunnycdn')->makeDirectory($bunny_dir_path);
      Storage::disk('bunnycdn')->put($bunny_path, $imageFile);
      Storage::disk('bunnycdn')->put($bunny_sm_path, $imageSmFile);

      $is_poster = ($k === 0 && !$poster) ? 1 : 0;
      $photo_list[] = Photo::create([
        'post_id'   => $post->id,
        'author_id' => $user->id,
        'extension' => $image->extension(),
        'is_poster' => $is_poster,
        'name'      => $image_name,
        'url'       => $bunny_path,
      ]);
    }

    File::delete($path_list);
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
