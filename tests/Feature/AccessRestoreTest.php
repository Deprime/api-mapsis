<?php

namespace Tests\Feature;

use App\Models\SmsCode;
use App\Models\User;
use App\ValueObjects\PhonePrefix;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccessRestoreTest extends TestCase
{
  use WithFaker, RefreshDatabase;

  /**
   *
   * @return void
   */
  public function testRestorePasswordSuccessfully()
  {
    $row = collect(PhonePrefix::list())->random();

    $phone = $this->faker->numerify(str_repeat("#", $row['length']));

    $response = $this->json('POST', 'api/v1/auth/send-validation-code', [
      'flag' => null,
      'prefix' => $row['prefix'],
      'phone' => $phone,
    ]);

    $response->assertOk();
  }

  /**
   *
   * @return void
   */
  public function testVerifyValidationCodeSuccessfully()
  {
    $row = collect(PhonePrefix::list())->random();

    $phone = $this->faker->numerify(str_repeat("#", $row['length']));

    $code = SmsCode::factory()->state([
      'prefix' => $row['prefix'],
      'phone' => $phone
    ])->create();

    $response = $this->json('POST', 'api/v1/auth/verify-validation-code', [
      'flag' => null,
      'prefix' => $row['prefix'],
      'code' => $code->code,
      'phone' => $phone,
    ]);

    $response->assertOk();
  }
}
