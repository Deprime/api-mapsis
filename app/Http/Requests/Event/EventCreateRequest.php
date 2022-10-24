<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class EventCreateRequest extends FormRequest
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
    return [
      'status_id'      => [
        'required',
        'numeric',
        'exists:post_status,id'
      ],
      'type_id'      => [
        'required',
        'numeric',
        'exists:post_type,id'
      ],
      'title' => ['required', 'string'],
      'description'  => ['required', 'string'],
      'address'  => ['required', 'string'],
      'suggested_address'  => ['required', 'string'],
      'coords'  => ['required', 'string'],
      'published_at'  => ['required', 'string'],
      'start_at'  => ['required', 'string'],
      'finish_at'  => ['required', 'string'],
    ];
  }


  /**
   * Fail validation response
   * @param Illuminate\Contracts\Validation\Validator
   * @throws Illuminate\Http\Exceptions\HttpResponseException
   */
  protected function failedValidation(Validator $validator) {
    throw new HttpResponseException(response()->json($validator->errors(), Response::HTTP_PRECONDITION_FAILED));
  }
}
