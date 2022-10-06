<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Cross-Origin Resource Sharing (CORS) Configuration
  |--------------------------------------------------------------------------
  |
  | Here you may configure your settings for cross-origin resource sharing
  | or "CORS". This determines what cross-origin operations may execute
  | in web browsers. You are free to adjust these settings as needed.
  |
  | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
  |
  */
  'paths' => ['api/*', 'sanctum/csrf-cookie'],
  'allowed_methods' => ['*'],
  'allowed_origins' => ['*'],
  // 'allowed_origins' => (env('APP_ENV') === 'production')
  //   ? [
  //     'http://evim-estate.ru',
  //     'https://evim-estate.ru',
  //     'http://www.evim-estate.ru',
  //     'https://www.evim-estate.ru'
  //     'http://dev-evim.vercel.app',
  //     'https://dev-evim.vercel.app',
  //   ]
  //   : ['*'],

  'allowed_origins_patterns' => [],
  'allowed_headers' => ['*'],
  'exposed_headers' => [],
  'max_age' => 0,
  'supports_credentials' => true,
];
