<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class OAuthController extends Controller
{

  public function signupByTelegram(Request $request)
  {
    return Socialite::driver('telegram')->redirect();
  }
}
