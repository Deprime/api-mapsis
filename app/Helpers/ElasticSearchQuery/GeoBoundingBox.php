<?php

declare(strict_types=1);

namespace App\Helpers\ElasticSearchQuery;

use JeroenG\Explorer\Domain\Syntax\SyntaxInterface;

class GeoBoundingBox implements SyntaxInterface
{
  private float $top_left_lat;
  private float $top_left_lon;
  private float $bottom_right_lat;
  private float $bottom_right_lon;

  public function __construct(float $top_left_lat, float $top_left_lon, float $bottom_right_lat, float $bottom_right_lon)
  {
    $this->top_left_lat = $top_left_lat;
    $this->top_left_lon = $top_left_lon;
    $this->bottom_right_lat = $bottom_right_lat;
    $this->bottom_right_lon = $bottom_right_lon;
  }

  public function build(): array
  {
    return ['geo_bounding_box' => [
      "pin.location" => [
        'top_left' => [
          'lat' => $this->top_left_lat,
          'lon' => $this->top_left_lon
        ],
        'bottom_right' => [
          'lat' => $this->bottom_right_lat,
          'lon' => $this->bottom_right_lon
        ],
      ],
    ]];
  }
}
