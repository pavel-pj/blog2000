<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class TopicCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
     public function authorize(): bool
    {
        return Auth::check();
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                'errors' => $validator->errors(),
                ],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
          return [
            'name' => [
                'string',
                'required',
                'min:2',
                'max:255',
                 Rule::unique('topics')->where(function ($query)   {
                    return $query->where('subject_id', $this->subject_id);
                }),
            ],
            'subject_id' => [
                'required',
                'string',
                Rule::exists('subjects', 'id')->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                }),
                ]
            
        ];
    }
}
