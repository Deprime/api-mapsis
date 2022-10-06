<?php

namespace App\Providers;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

use League\Flysystem\Filesystem;
use PlatformCommunity\Flysystem\BunnyCDN\BunnyCDNAdapter;
use PlatformCommunity\Flysystem\BunnyCDN\BunnyCDNClient;
use PlatformCommunity\Flysystem\BunnyCDN\BunnyCDNRegion;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Storage::extend('bunnycdn', function ($app, $config) {
        $adapter = new BunnyCDNAdapter(
          new BunnyCDNClient(
            $config['storage_zone'],
            $config['api_key'],
            $config['region']
          ),
          'http://testing.b-cdn.net' # Optional
        );

        return new FilesystemAdapter(
          new Filesystem($adapter, $config),
          $adapter,
          $config
        );
    });
    }
}
