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
      DB::table('category')->insert([
        [
          'slug'      => 'rent',
          'order'     => 'DESC',
          'mui_key'   => 'rent_category',
          'title_ru'  => 'Аренда',
          'title_en'  => 'Rent'
        ],
        [
          'slug'      => 'Events',
          'order'     => 'DESC',
          'mui_key'   => 'events_category',
          'title_ru'  => 'Знакомства и мероприятия',
          'title_en'  => 'Dating and events'
        ],
        [
          'slug'      => 'clubs',
          'order'     => 'DESC',
          'mui_key'   => 'clubs_category',
          'title_ru'  => 'Заведения и клубы',
          'title_en'  => 'Establishments and clubs'
        ],
        [
          'slug'      => 'exchange',
          'order'     => 'DESC',
          'mui_key'   => 'exchange_category',
          'title_ru'  => 'Обмен валюты',
          'title_en'  => 'Currency exchange'
        ],
        [
          'slug'      => 'beauty_health',
          'order'     => 'DESC',
          'mui_key'   => 'beauty_health_category',
          'title_ru'  => 'Красота и здоровье',
          'title_en'  => 'Beauty and health'
        ],
        [
          'slug'      => 'digital_services',
          'order'     => 'DESC',
          'mui_key'   => 'digital_services_category',
          'title_ru'  => 'Цифровые услуги',
          'title_en'  => 'Digital services'
        ],
        [
          'slug'      => 'trainings',
          'order'     => 'DESC',
          'mui_key'   => 'trainings_category',
          'title_ru'  => 'Тренинги и мастерклассы',
          'title_en'  => 'Trainings and masterclasses'
        ],
        [
          'slug'      => 'photo_video',
          'order'     => 'DESC',
          'mui_key'   => 'photo_video_category',
          'title_ru'  => 'Фото и видео съемка',
          'title_en'  => 'Photo and video shooting'
        ],
        [
          'slug'      => 'visas_escorts',
          'order'     => 'DESC',
          'mui_key'   => 'visas_escorts_category',
          'title_ru'  => 'Визы и сопровождение',
          'title_en'  => 'Visas and escorts'
        ],
        [
          'slug'      => 'goods_shipment',
          'order'     => 'DESC',
          'mui_key'   => 'goods_shipment_category',
          'title_ru'  => 'Отправка грузов',
          'title_en'  => 'Shipment of goods'
        ],
        [
          'slug'      => 'other',
          'order'     => 'DESC',
          'mui_key'   => 'other_category',
          'title_ru'  => 'Другое',
          'title_en'  => 'Other'
        ],
      ]);
    }
}
