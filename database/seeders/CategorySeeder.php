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
          'order'     => 'DESC',
          'mui_key'   => 'rent_category',
          'title_ru'  => 'Аренда',
          'title_en'  => 'Rent'
        ],
        [
          'id'        => 2,
          'slug'      => 'events',
          'order'     => 'DESC',
          'mui_key'   => 'events_category',
          'title_ru'  => 'Знакомства и мероприятия',
          'title_en'  => 'Dating and events'
        ],
        [
          'id'        => 3,
          'slug'      => 'clubs',
          'order'     => 'DESC',
          'mui_key'   => 'clubs_category',
          'title_ru'  => 'Заведения и клубы',
          'title_en'  => 'Establishments and clubs'
        ],
        [
          'id'        => 4,
          'slug'      => 'exchange',
          'order'     => 'DESC',
          'mui_key'   => 'exchange_category',
          'title_ru'  => 'Обмен валюты',
          'title_en'  => 'Currency exchange'
        ],
        [
          'id'        => 5,
          'slug'      => 'beauty_health',
          'order'     => 'DESC',
          'mui_key'   => 'beauty_health_category',
          'title_ru'  => 'Красота и здоровье',
          'title_en'  => 'Beauty and health'
        ],
        [
          'id'        => 6,
          'slug'      => 'digital_services',
          'order'     => 'DESC',
          'mui_key'   => 'digital_services_category',
          'title_ru'  => 'Цифровые услуги',
          'title_en'  => 'Digital services'
        ],
        [
          'id'        => 7,
          'slug'      => 'trainings',
          'order'     => 'DESC',
          'mui_key'   => 'trainings_category',
          'title_ru'  => 'Тренинги и мастерклассы',
          'title_en'  => 'Trainings and masterclasses'
        ],
        [
          'id'        => 8,
          'slug'      => 'photo_video',
          'order'     => 'DESC',
          'mui_key'   => 'photo_video_category',
          'title_ru'  => 'Фото и видео съемка',
          'title_en'  => 'Photo and video shooting'
        ],
        [
          'id'        => 9,
          'slug'      => 'visas_escorts',
          'order'     => 'DESC',
          'mui_key'   => 'visas_escorts_category',
          'title_ru'  => 'Визы и сопровождение',
          'title_en'  => 'Visas and escorts'
        ],
        [
          'id'        => 10,
          'slug'      => 'goods_shipment',
          'order'     => 'DESC',
          'mui_key'   => 'goods_shipment_category',
          'title_ru'  => 'Отправка грузов',
          'title_en'  => 'Shipment of goods'
        ],
        [
          'id'        => 11,
          'slug'      => 'other',
          'order'     => 'DESC',
          'mui_key'   => 'other_category',
          'title_ru'  => 'Другое',
          'title_en'  => 'Other'
        ],
      ]);
    }
}
