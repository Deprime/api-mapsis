<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventCreateRequest;
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
  public function get(Request $request,int $event_id): JsonResponse
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

  public  function create(EventCreateRequest $request): JsonResponse
  {
    $user = $request->user();
    $draftStatus  = EventStatus::findOrFail(1);

    $event = Event::create([
      'author_id' => $user->id,
      'status_id' => $draftStatus->id,
      'title' => $request->title,
      'description' => $request->description,
      'address' => $request->address,
      'suggested_address' => $request->suggested_address,
      'coords' => explode(",", $request->title),
      'published_at' => date( 'd.m.Y' , strtotime($request->published_at) ),
      'start_at' => date( 'd.m.Y' , strtotime($request->start_at) ),
      'finish_at' => date( 'd.m.Y' , strtotime($request->finish_at) )
    ]);

    $event->save();

    return response()->json($event);
  }

}
