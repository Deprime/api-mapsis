<?php

namespace Tests\Feature;

use App\Models\User;
use App\ValueObjects\PhonePrefix;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SigninTest extends TestCase
{
  use WithFaker, RefreshDatabase;

  /**
   *
   * @return void
   */
  public function testSigninByPhoneSuccessfully()
  {
    foreach (PhonePrefix::list() as $row){

      $phone = $this->faker->numerify(str_repeat("#", $row['length']));

      User::factory()->state([
        'prefix' => $row['prefix'],
        'phone' => $phone
      ])->create();

      $response = $this->json('POST', 'api/v1/auth/signin-by-phone', [
        'prefix' => $row['prefix'],
        'phone' => $phone,
        'password' => '123456'
      ]);

      $response->assertOk();

      //echo $row['country'] ." OK\n";
    }
  }

  /**
   *
   * @return void
   */
  public function testLogoutSuccessfully()
  {
    $row = collect(PhonePrefix::list())->random();

    $phone = $this->faker->numerify(str_repeat("#", $row['length']));

    $user = User::factory()->state([
      'prefix' => $row['prefix'],
      'phone' => $phone
    ])->create();

    Sanctum::actingAs($user,['*']);

    $response = $this->json('DELETE', 'api/v1/auth/logout');

    $response->assertNoContent();
  }
}
