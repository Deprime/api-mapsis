<?php

namespace App\ValueObjects;

class PhonePrefix
{
  protected static $data = [
    ["value" => 1, "country" => 'Turkey', "prefix" => '+90'],
    ["value" => 2, "country" => 'Ukrain', "prefix" => '+380'],
    ["value" => 3, "country" => 'Russia', "prefix" => '+7'],
    // ["value" => 4, "country" => 'Kazakhstan', "prefix" => '+7'],
    ["value" => 5, "country" => 'Belarus', "prefix" => '+375'],
    ["value" => 6, "country" => 'Kyrgyzstan', "prefix" => '+996'],
    ["value" => 7, "country" => 'Uzbekistan', "prefix" => '+998'],
    ["value" => 8, "country" => 'Tajikistan', "prefix" => '+992'],
    ["value" => 9, "country" => 'Bulgaria', "prefix" => '+359'],
    ["value" => 10, "country" => 'Lithuania', "prefix" => '+370'],
    ["value" => 11, "country" => 'Latvia', "prefix" => '+371'],
    ["value" => 12, "country" => 'Estonia', "prefix" => '+372'],
    ["value" => 13, "country" => 'Armenia', "prefix" => '+374'],
    ["value" => 14, "country" => 'Georgia', "prefix" => '+995'],
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
}
