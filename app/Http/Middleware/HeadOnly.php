<?php

namespace App\Http\Middleware;

use Closure;
// use TusPhp\Middleware\Middleware;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;
use App\Models\Company;

class HeadOnly
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $company = Company::query()
      ->where('head_id', $request->user()->id)
      ->first();

    if (!$company) {
      return response()->json([], Response::HTTP_FORBIDDEN);
    }
    return $next($request);
  }
}
