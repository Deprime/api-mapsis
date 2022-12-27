<?php

namespace Database\Factories;

use App\Models\SmsCode;
use App\ValueObjects\PhonePrefix;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photo>
 */
class SmsCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmsCode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
      $row = collect(PhonePrefix::list())->random();

      return [
        'prefix' => $row['prefix'],
        'phone' => $this->faker->numerify(str_repeat("#", $row['length'])),
        'code' => '1111',
        'validated_at' => now(),
      ];
    }
}

