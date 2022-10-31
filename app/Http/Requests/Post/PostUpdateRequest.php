<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class PostUpdateRequest extends FormRequest
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
      'title'       => ['required', 'string'],
      'description' => ['required', 'string'],
      'category_id' => [
        'required',
        'numeric',
        'exists:category,id'
      ],
      'address'   => ['nullable', 'string'],
      'coords'    => ['nullable', 'array'],
      'coords.*'  => ['numeric'],
      'price'     => ['required', 'numeric'],
      'suggested_address' => ['nullable', 'string'],
      // 'start_at'   => ['nullable', 'string'],
      // 'finish_at'  => ['nullable', 'string'],
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
