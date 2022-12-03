<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('category')->truncate();
      DB::table('category')->insert([
        [
          'id'        => 1,
          'slug'      => 'rent',
          'order'     => 1,
          'mui_key'   => 'rent_category',
          'title_ru'  => 'Аренда',
          'title_en'  => 'Rent'
        ],
        [
          'id'        => 2,
          'slug'      => 'events',
          'order'     => 2,
          'mui_key'   => 'events_category',
          'title_ru'  => 'Знакомства и мероприятия',
          'title_en'  => 'Dating and events'
        ],
        [
          'id'        => 3,
          'slug'      => 'clubs',
          'order'     => 3,
          'mui_key'   => 'clubs_category',
          'title_ru'  => 'Заведения и клубы',
          'title_en'  => 'Establishments and clubs'
        ],
        [
          'id'        => 4,
          'slug'      => 'exchange',
          'order'     => 4,
          'mui_key'   => 'exchange_category',
          'title_ru'  => 'Обмен валюты',
          'title_en'  => 'Currency exchange'
        ],
        [
          'id'        => 5,
          'slug'      => 'beauty_health',
          'order'     => 5,
          'mui_key'   => 'beauty_health_category',
          'title_ru'  => 'Красота и здоровье',
          'title_en'  => 'Beauty and health'
        ],
        [
          'id'        => 6,
          'slug'      => 'digital_services',
          'order'     => 6,
          'mui_key'   => 'digital_services_category',
          'title_ru'  => 'Цифровые услуги',
          'title_en'  => 'Digital services'
        ],
        [
          'id'        => 7,
          'slug'      => 'trainings',
          'order'     => 7,
          'mui_key'   => 'trainings_category',
          'title_ru'  => 'Тренинги и мастерклассы',
          'title_en'  => 'Trainings and masterclasses'
        ],
        [
          'id'        => 8,
          'slug'      => 'photo_video',
          'order'     => 8,
          'mui_key'   => 'photo_video_category',
          'title_ru'  => 'Фото и видео съемка',
          'title_en'  => 'Photo and video shooting'
        ],
        [
          'id'        => 9,
          'slug'      => 'visas_escorts',
          'order'     => 9,
          'mui_key'   => 'visas_escorts_category',
          'title_ru'  => 'Визы и сопровождение',
          'title_en'  => 'Visas and escorts'
        ],
        [
          'id'        => 10,
          'slug'      => 'goods_shipment',
          'order'     => 10,
          'mui_key'   => 'goods_shipment_category',
          'title_ru'  => 'Отправка грузов',
          'title_en'  => 'Shipment of goods'
        ],
        [
          'id'        => 11,
          'slug'      => 'other',
          'order'     => 11,
          'mui_key'   => 'other_category',
          'title_ru'  => 'Другое',
          'title_en'  => 'Other'
        ],
      ]);
    }
}
