<?php

namespace App\Helpers;

/**
 * HiCall - flashcall service
 * Simple API class
 */
class HiCall {

  const URL_HICALL_API = 'https://a.hi-call.ru';

  public function __construct() {}

  /**
   * Creating curl request
   * @param $url
   * @param $post
   * @param $options
   * @return mixed
   */
  private static function curl_post($url, $is_post = false, array $post = [],  array $options = []) {
    $defaults = [
      CURLOPT_HEADER => 0,
      CURLOPT_URL => $url,
      CURLOPT_FRESH_CONNECT => 1,
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_FORBID_REUSE => 1,
      CURLOPT_TIMEOUT => 10,
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_SSL_VERIFYHOST => 0,
      CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
    ];

    if ($is_post) {
      $defaults[CURLOPT_POST] = 1;
      $defaults[CURLOPT_POSTFIELDS] = http_build_query($post);
    }

    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if (!$result = curl_exec($ch)) {
      return curl_error($ch);
    }
    curl_close($ch);
    return $result;
  }

  /**
   * Call
   * sample: https://a.hi-call.ru/call/4506d297-4ea2-427e-a15a-cbc77a1b1e53/79824165796/
   */
  public static function call($phone) {
    $api_key = config('hicall.api_key');
    $number  = str_replace('+', '', $phone);
    $url     = self::URL_HICALL_API . "/call/$api_key/$number";
    return json_decode(self::curl_post($url), true);
  }
}
