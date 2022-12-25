<?php

namespace Tests\Feature;

use App\Models\SmsCode;
use App\Models\User;
use App\ValueObjects\PhonePrefix;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VerificationTest extends TestCase
{
  use WithFaker, RefreshDatabase;

  /**
   *
   * @return void
   */
  public function testSendValidationCodeSuccessfully()
  {
    $row = collect(PhonePrefix::list())->random();

    $phone = $this->faker->numerify(str_repeat("#", $row['length']));

    User::factory()->state([
      'prefix' => $row['prefix'],
      'phone' => $phone
    ])->create();

    $code = SmsCode::factory()->state([
      'prefix' => $row['prefix'],
      'phone' => $phone
    ])->create();

    $response = $this->json('POST', 'api/v1/auth/restore-password', [
      'flag' => null,
      'prefix' => $row['prefix'],
      'code' => $code->code,
      'phone' => $phone,
      'password' => '123456'
    ]);

    $response->assertOk();
  }


}
