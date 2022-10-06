<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use App\Models\{
  EstateStatus,
};

use App\ValueObjects\{
  PhonePrefix,
};

class DictionaryController extends Controller
{
  /**
   * List
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function phonePrefixList(Request $request)
  {
    $phone_prefixes = PhonePrefix::list();
    return response()->json($phone_prefixes, Response::HTTP_OK);
  }

  /**
   * Event status list
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function eventStatusList(Request $request): JsonResponse
  {
    $event_status_list = EventStatus::query()->get();
    return response()->json($event_status_list, Response::HTTP_OK);
  }
}