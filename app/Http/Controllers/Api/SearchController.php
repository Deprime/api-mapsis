<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ElasticSearchQuery\GeoBoundingBox;
use App\Helpers\ElasticSearchQuery\GeoDistance;
use App\Helpers\ElasticSearchQuery\Range;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostSearchRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

    if ($request->min_price || $request->max_price) {
      $search->must(new Range('price', $request->min_price, $request->max_price));
    }

    if ($request->radius) {
      $search->filter(new GeoDistance($request->radius, $request->lat, $request->lng));
    }

    elseif ($request->point_top_left and $request->point_bottom_right){
      $point_top_left = explode(",", $request->point_top_left);
      $point_bottom_right = explode(",", $request->point_bottom_right);

      $search->filter(new GeoBoundingBox(
        floatval($point_top_left[0]),
        floatval($point_top_left[1]),
        floatval($point_bottom_right[0]),
        floatval($point_bottom_right[1]),
      ));
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
