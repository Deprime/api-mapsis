<?php

namespace App\Services;

use App\Models\Photo;
use App\Models\User;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Image;

class ThumbnailService {


  /**
   * Create and Upload image thumbnail to CDN
   *
   * @param int $post_id  Post Id for CDN path generation
   * @param int $width    Image width
   * @param int $height   Image height
   * @param string $path  Internal path of Image
   * @param string $name  New file name for uploading to CDN
   * @param int $quality  Image quality
   *
   * @return string
   */
  public static function upload(int $post_id, int $width, int $height, string $path, string $name, int $quality = 90): string
  {
    $img = Image::make($path);
    $full_path = public_path('/thumbs') . DIRECTORY_SEPARATOR . $name;

    $file = $img->resize($width, $height, function ($const) {
      $const->aspectRatio();
    })->save($full_path, $quality);
    Log::debug($path);
    Log::debug($name);
    $bunny_path = "posts/{$post_id}/{$name}";
    Storage::disk('bunnycdn')->put($bunny_path, $file);

    File::delete($full_path);

    return $bunny_path;
  }

}
