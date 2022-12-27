<?php

declare(strict_types=1);

namespace App\Helpers\ElasticSearchQuery;

use JeroenG\Explorer\Domain\Syntax\SyntaxInterface;

class GeoDistance implements SyntaxInterface
{
  private float $distance;
  private float $lat;
  private float $lon;

  public function __construct(float $distance, float $lat, float $lon)
  {
    $this->distance = $distance / 1000;
    $this->lat = $lat;
    $this->lon = $lon;
  }

  public function build(): array
  {
    return ['geo_distance' => [
      'distance' => $this->distance."km",

      "pin.location" => [
        'lat' => $this->lat,
        'lon' => $this->lon,
      ],
    ]];
  }
}
