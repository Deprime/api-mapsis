<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      \DB::table('post_type')->truncate();
      \DB::table('post_type')->insert(array (
        0 =>
          array (
            'id' => 1,
            'title' => 'Event',
            'mui_key' => 'event',
          ),
        1 =>
          array (
            'id' => 2,
            'title' => 'Service',
            'mui_key' => 'service',
          ),
      ));
    }
}
