<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventStatusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('event_status')->delete();
        
        \DB::table('event_status')->insert(array (
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
                'mui_key' => 'Publishedp',
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