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
        ],
      ]);
    }
}
