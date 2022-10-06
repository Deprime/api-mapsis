<?php

namespace App\Http\Middleware;

use Closure;
use TusPhp\Middleware\Middleware;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class CustomerOnly extends Middleware
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
    if ($request->user()->role != Role::CUSTOMER) {
      return response()->json([], Response::HTTP_FORBIDDEN);
    }
    return $next($request);
  }
}
