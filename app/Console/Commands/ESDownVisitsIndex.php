<?php

namespace App\Console\Commands;

use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ESDownVisitsIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:down_visits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending request to delete Index of unique visitors journal for post to Elastic Search';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

      $params = [
        "index" => 'visits'
      ];

      $client = ClientBuilder::create()
        ->setHosts([sprintf("%s://%s:%s", env('ES_SCHEME'), env('ES_HOST'), env('ES_PORT'))])
        ->setBasicAuthentication(env('ES_USERNAME'), env('ES_PASSWORD'))
        ->setSSLVerification(config('explorer.connection.ssl.verify'))
        ->build();

      $response = $client->delete($params);
      print_r($response);

      return Command::SUCCESS;
    }
}





