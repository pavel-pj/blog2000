<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use App\Models\SubjectOptions;
use App\Enums\RepetitionType;

class SubjectOptionsUpdateRequest extends FormRequest
{
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
    public function rules() 
    {
       $optionId = $this->route('option') ?? $this->route('id');
       $userId = Auth::id();

        return [
            'total_rows' => 'nullable|integer|between:30,150',
            'new_words'  => 'nullable|integer|between:5,20',
            'important_words' => 'nullable|integer|between:5,20', 
            'repetition_type' => ['nullable','string', Rule::in(RepetitionType::cases()) ],
            'repetition_theme' => 'nullable|string|min:3| max:255',
            'row_length'=> 'nullable|integer|between:10,25',
         ];
    }

    public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $optionId = $this->route('option') ?? $this->route('id');
        $option = SubjectOptions::with('subject')->find($optionId);

        if (!$option) {
            $validator->errors()->add('option', 'Option not found.');
            return;
        }

        if ($option->subject->user_id !== Auth::id()) {
            $validator->errors()->add('option', 'This option does not belong to you.');
        }
    });
}
}
