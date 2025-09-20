<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use App\Models\Subject;

class WordCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() ;
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
            'name' => 'string|required|min:2|max:255',
            'translation' => 'string|nullable|max:500',
            'subject_id' => [
                'required',
                'string',
                'exists:subjects,id',
                function ($attribute, $value, $fail) {
                    $subject = Subject::find($value);
                    if (!$subject || $subject->user_id !== Auth::id()) {
                        $fail('The selected subject does not belong to you.');
                    }
                },
            ],
        ];
    }
}
