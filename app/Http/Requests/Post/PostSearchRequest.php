<?php

namespace App\Http\Requests\Post;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class PostSearchRequest extends FormRequest
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
        'text' => ['nullable', 'string'],
        'min_price' => ['nullable', 'numeric'],
        'max_price' => ['nullable', 'numeric'],
        'rad'  => ['nullable', 'numeric'],
        'lat'  => ['required_with:rad', 'numeric'],
        'lng'  => ['required_with:rad', 'numeric'],
        'point_top_left' => ['required_with:point_bottom_right', 'string',],
        'point_bottom_right' => ['required_with:point_top_left', 'string',]
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
