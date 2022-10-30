<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('currency')->truncate();
        \DB::table('currency')->insert(array (
            0 =>
            array (
                'code' => 'USD',
                'id' => 1,
                'number' => 840,
                'symbol' => '$',
                'title' => 'US Dollar',
            ),
            1 =>
            array (
                'code' => 'EUR',
                'id' => 2,
                'number' => 978,
                'symbol' => '€',
                'title' => 'Euro',
            ),
            2 =>
            array (
                'code' => 'RUB',
                'id' => 3,
                'number' => 643,
                'symbol' => '₽',
                'title' => 'Russian Ruble',
            ),
            3 =>
            array (
                'code' => 'TRY',
                'id' => 4,
                'number' => 949,
                'symbol' => '₺',
                'title' => 'Turkish Lira',
            ),
        ));


    }
}
