<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      \DB::table('languages')->truncate();
      \DB::table('languages')->insert(array (
        0 =>
          array (
            'locale' => 'en',
            'title' => 'English',
            'native_title' => 'English',
          ),
        1 =>
          array (
            'locale' => 'ru',
            'title' => 'Russian',
            'native_title' => 'Русский',
          ),
      ));
    }
}
