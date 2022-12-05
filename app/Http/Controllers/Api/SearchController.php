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
    $search = Post::search($request->text)->query(fn ($query) => $query->with(static::LIST_RELATIONS));

    $priseFilter = [];

    if($request->min_price){
      $priseFilter[] = 'price >= '. $request->min_price;
    }

    if($request->max_price){
      $priseFilter[] = 'price <= '. $request->max_price;
    }

    $search->with([
      'numericFilters' => $priseFilter,
    ]);

    if ($request->radius) {
      $search->aroundLatLng($request->lat, $request->lng)
      ->with([
        'aroundRadius' => $request->radius,
      ]);
    }elseif ($request->pointTopLeft and $request->pointBottomRight){
      $pointTopLeft = explode(",", $request->pointTopLeft);
      $pointBottomRight = explode(",", $request->pointBottomRight);

      $boundingBox = [
       floatval($pointTopLeft[0]),
       floatval($pointTopLeft[1]),
       floatval($pointBottomRight[0]),
       floatval($pointBottomRight[1])
      ];

      $search->with([
        'insideBoundingBox' => [$boundingBox],
      ]);
    }

    $post_list = $search->get();

    return response()->json($post_list);
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
