<?php

namespace App\ValueObjects;

class PhonePrefix
{
  protected static $data = [
    ["value" => 1, "country" => 'Indonesia', "prefix" => '+62', 'length' => 11],
    ["value" => 2, "country" => 'Ukrain', "prefix" => '+380', 'length' => 9],
    ["value" => 3, "country" => 'Russia', "prefix" => '+7', 'length' => 10],
    // ["value" => 4, "country" => 'Kazakhstan', "prefix" => '+7'],
    ["value" => 5, "country" => 'Belarus', "prefix" => '+375', 'length' => 9],
    ["value" => 6, "country" => 'Kyrgyzstan', "prefix" => '+996', 'length' => 9],
    ["value" => 7, "country" => 'Uzbekistan', "prefix" => '+998', 'length' => 9],
    ["value" => 8, "country" => 'Tajikistan', "prefix" => '+992', 'length' => 9],
    ["value" => 9, "country" => 'Bulgaria', "prefix" => '+359', 'length' => 9],
    ["value" => 10, "country" => 'Lithuania', "prefix" => '+370', 'length' => 8],
    ["value" => 11, "country" => 'Latvia', "prefix" => '+371', 'length' => 8],
    ["value" => 12, "country" => 'Estonia', "prefix" => '+372', 'length' => 8],
    ["value" => 13, "country" => 'Armenia', "prefix" => '+374', 'length' => 8],
    ["value" => 14, "country" => 'Georgia', "prefix" => '+995', 'length' => 9],
    ["value" => 15, "country" => 'Turkey', "prefix" => '+90', 'length' => 10],
    ["value" => 16, "country" => 'Poland', "prefix" => '+48', 'length' => 9],
  ];

  /**
   * List
   */
  public static function list()
  {
    return self::$data;
  }

  /**
   * Prefix List
   */
  public static function prefixList()
  {
    $prefix_list = array_map(fn($value) => $value['prefix'], self::$data);
    return $prefix_list;
  }

  /**
   * Get  phone length by prefix
   */
  public static function getLengthByPrefix(string $prefix): string | null
  {
    $record = collect(self::$data)->first(function ($value, $key) use ($prefix) {
      return $value['prefix'] === $prefix;
    });
    return $record ? $record['length'] : 10;
  }
}
