<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostSearchRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use App\Models\{Currency, Post};

class SearchController extends Controller
{
  protected const LIST_RELATIONS = ['author', 'status', 'poster'];
  protected const ITEM_RELATIONS = ['author', 'status', 'photos'];

  /**
   * List
   * @param PostSearchRequest $request
   * @return JsonResponse
   */
  public function posts(PostSearchRequest $request): JsonResponse
  {
    $query         = $request->input('query');
    $minPrice      = $request->input('min_price');
    $maxPrice      = $request->input('max_price');
    $latitude      = $request->input('latitude');
    $longitude     = $request->input('longitude');
    $radius        = $request->input('radius');
    $currency_code = $request->input('currency');
    $total         = $request->input('total');

    $queryBuilder = Post::search($query);

    $params = [
      'getRankingInfo' => true,
      'filters' => ''
    ];

    if ($latitude !== null && $longitude !== null && $radius !== null) {
      $params['aroundRadius'] = intval($radius);
      $params['aroundLatLng'] = $latitude.','.$longitude;
    }

    if ($minPrice !== null && $maxPrice !== null) {
      $params['filters'] = "price:$minPrice TO $maxPrice";
    }else{
      if ($minPrice !== null) {
        $params['filters'] = "price >= $minPrice";
      }
      if ($maxPrice !== null) {
        $params['filters'] = "price <= $maxPrice";
      }
    }

    if ($currency_code !== null) {
      $currency = Currency::where('code',$currency_code)->firstOrFail();

      if($params['filters'] !== ''){
        $params['filters'] = $params['filters'] . ' AND ' . "currency_id = $currency->id";
      }else{
        $params['filters'] = "currency_id == $currency->id" ;
      }
    }

    $results = $queryBuilder->with($params)->query(fn ($query) => $query->with(static::LIST_RELATIONS))->paginate($total ?? 20);

    return response()->json($results);
  }

  /**
   * Get
   * @param Request $request
   * @param $post_id
   * @return JsonResponse
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
