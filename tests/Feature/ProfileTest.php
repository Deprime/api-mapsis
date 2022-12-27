<?php

namespace Tests\Feature;

use App\Models\User;
use App\ValueObjects\PhonePrefix;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileTest extends TestCase
{
  use WithFaker, RefreshDatabase;

  /**
   *
   * @return void
   */
  public function testProfileSuccessfully()
  {
    $row = collect(PhonePrefix::list())->random();

    $phone = $this->faker->numerify(str_repeat("#", $row['length']));

    $user = User::factory()->state([
      'prefix' => $row['prefix'],
      'phone' => $phone
    ])->create();

    Sanctum::actingAs($user,['*']);

    $response = $this->json('GET', 'api/v1/app/profile');

    $response->assertOk();

    $response->assertJson(fn (AssertableJson $json) =>
      $json->where('user.phone', $phone)
        ->etc()
    );


  }
}
