<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Storage;

// use App\Helpers\SmsAero;
// use App\Services\ProfileService;

// use \App\Http\Requests\Event\{
//   EventCreateRequest,
// };

use App\Models\{
  // Role,
  User,
  Event,
  EventStatus,
};

class EventController extends Controller
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
    $event_list = Event::query()
                      ->with(static::LIST_RELATIONS)
                      ->where('author_id', $user->id)
                      ->get();

    return response()->json([
      'event_list' => $event_list,
    ]);
  }

  /**
   * Get
   * @param  int $event_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function get(Request $request, $event_id): JsonResponse
  {
    $user = $request->user();
    $event = Event::query()
                      ->with(static::ITEM_RELATIONS)
                      ->where('id', $event_id)
                      ->where('author_id', $user->id)
                      ->first();

    if ($event) {
      return response()->json($event);
    }
    return response()->json([], Response::HTTP_NOT_FOUND);
  }
}
