<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use App\Models\Subject;
use Illuminate\Validation\Rule;

class SubjectUpdateRequest extends FormRequest
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
    public function rules(): array
    {
        $subjectId = $this->route('subject') ?? $this->route('id');
        $subject = Subject::find($subjectId);

        return [
            'name' => [
                'string',
                'nullable','min:3','max:255',
                Rule::unique('subjects', 'name')
                    ->ignore($subjectId)
                    ->when(
                        $subject && $this->input('name') === $subject->name,
                        function ($rule) {
                            return $rule->where('id', $this->route('subject') ?? $this->route('id'));
                        }
                    )
            ],
            'desription' => 'string|nullable|max:500',

        ];
    }
}
