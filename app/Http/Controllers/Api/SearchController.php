<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostSearchRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Storage;

use App\Models\{
  Post,
};

class SearchController extends Controller
{
  protected const LIST_RELATIONS = ['author', 'status', 'poster'];
  protected const ITEM_RELATIONS = ['author', 'status', 'photos'];

  /**
   * List
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function posts(PostSearchRequest $request): JsonResponse
  {
    $search = Post::search($request->text);

    $priseFilter = [];

    if($request->from){
      $priseFilter[] = 'price >= '. $request->from;
    }

    if($request->to){
      $priseFilter[] = 'price <= '. $request->to;
    }

    $search->with([
      'numericFilters' => $priseFilter,
    ]);

    if($request->rad){
      $search->aroundLatLng($request->lat, $request->lng)
      ->with([
        'aroundRadius' => $request->rad,
      ]);
    }

    return response()->json($search->get());

  }

  /**
   * Get
   * @param  int $estate_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function get(Request $request, $post_id): JsonResponse
  {
    $post = Post::query()
                ->with(static::ITEM_RELATIONS)
                ->where('id', $post_id)
                ->published()
                ->first();

    if ($post) {
      return response()->json($post);
    }
    return response()->json([], Response::HTTP_NOT_FOUND);
  }
}
