<?php

namespace App\Http\Requests\Signup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

use App\ValueObjects\{
  PhonePrefix,
};

class SignupPhoneRequest extends FormRequest
{
  protected const PHONE_MODEL = 'App\Models\User';

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, mixed>
   */
  public function rules()
  {
    $prefix = $this->prefix;
    $length = $prefix ? PhonePrefix::getLengthByPrefix($prefix) : 9;

    $phone_rules = ['required', "digits:$length"];
    if ($prefix) {
      $phone_rules[] = Rule::unique(static::PHONE_MODEL)
        ->where(function ($query) use ($prefix) {
          return $query->where('prefix', $prefix);
        });
    }

    return [
      'prefix'   => ['required', 'string', Rule::in(PhonePrefix::prefixList())],
      'phone'    => $phone_rules,
      'password' => ['required', 'min:6'],
    ];
  }

  /**
   * Fail validation response
   * @param Illuminate\Contracts\Validation\Validator
   * @throws Illuminate\Http\Exceptions\HttpResponseException
   */
  protected function failedValidation(Validator $validator) {
    throw new HttpResponseException(response()->json($validator->errors(), Response::HTTP_UNAUTHORIZED));
  }
}
