<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use App\Models\Subject;
use Illuminate\Validation\Rule;
use App\Models\Word;

class WordUpdateRequest extends FormRequest
{
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
                'name' => 'string|required|min:2| max:255',
                'translation' => 'string|nullable|max:500',
                //'subject_id' => 'string|nullable|exists:subjects,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $wordId = $this->route('word'); // Получаем id из route
            
            // Проверяем существование и принадлежность
            $word = Word::find($wordId);
            
            if (!$word) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Subject not found');
            }
            
            if ($word->subject->user_id !== Auth::id()) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Subject not found');
            }
        });
    }
}
