<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostStatusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('post_status')->truncate();
        \DB::table('post_status')->insert(array (
            0 =>
            array (
                'id' => 1,
                'title' => 'Draft',
                'mui_key' => 'draft',
            ),
            1 =>
            array (
                'id' => 2,
                'title' => 'Published',
                'mui_key' => 'published',
            ),
            2 =>
            array (
                'id' => 3,
                'title' => 'Archived',
                'mui_key' => 'archived',
            ),
        ));
    }
}
