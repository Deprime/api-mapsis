<?php

namespace Tests\Feature;

use App\Models\SmsCode;
use App\Models\User;
use App\ValueObjects\PhonePrefix;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SignupTest extends TestCase
{
  use WithFaker, RefreshDatabase;

  /**
   *
   * @return void
   */
  public function testSignupPhoneSuccessfully()
  {
    foreach (PhonePrefix::list() as $row){

      $phone = $this->faker->numerify(str_repeat("#", $row['length']));
      $prefix = $row['prefix'];

      SmsCode::factory()->state([
        'prefix' => $prefix,
        'phone' => $phone
      ])->create();

      $response = $this->json('POST', 'api/v1/auth/signup-phone', [
        'prefix' => $prefix,
        'phone' => $phone,
        'password' => '123456'
      ]);

      $response->assertOk();

      //echo $row['country'] ." OK\n";
    }
  }
}
