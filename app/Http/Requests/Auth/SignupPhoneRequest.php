<?php

namespace App\Http\Requests\Auth;

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
    $length = PhonePrefix::getLengthByPrefix($prefix);

    return [
      'prefix'   => ['required', 'string', Rule::in(PhonePrefix::prefixList())],
      'phone'    => ['required', "digits:$length", 'unique:users'],
      'password' => ['required', 'min:6'],
      // 'password' => ['required', 'min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'],
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
