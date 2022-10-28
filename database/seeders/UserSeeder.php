<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{
  Hash, DB,
};
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
        [
          'role'        => Role::DEMENTOR,
          'first_name'  => 'Администратор',
          'last_name'   => 'Кеноби',
          'email'       => 'deprimehell@gmail.com',
          'prefix'      => '+7',
          'phone'       => '9824165796',
          'password'    => Hash::make('testpassword1'),
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
          'referal_parent_id' => null,
          'referal_connected_at' => null,
        ],
        [
          'role'        => Role::DEMENTOR,
          'first_name'  => 'Генерал',
          'last_name'   => 'Гривус',
          'email'       => 's.budumyan@gmail.com',
          'prefix'      => '+7',
          'phone'       => '8005553535',
          'password'    => Hash::make('testpassword1'),
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
          'referal_parent_id' => 1,
          'referal_connected_at' => date("Y-m-d H:i:s"),
        ],
      ]);
    }
}
