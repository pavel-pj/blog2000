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
use App\Models\Topic;
use App\Enums\WordStatus;
use App\Enums\TaskWordStatus;

class WordUpdateStatusRequest extends FormRequest
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

        $userId = Auth::id();
 
        return [
            'word_id' => 'required|string|exists:words,id',
            'word_status' => [
                'required',
                'string',
                Rule::enum(WordStatus::class)
            ],
            'task_word_id' => 'required|string|exists:task_words,id',
            'task_word_status' => [
                'required',
                'string',
                Rule::enum(TaskWordStatus::class)
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->userOwnsWord()) {
                $validator->errors()->add('word_id', 'You do not have permission to access this word.');
            }

            if (!$this->userOwnsTaskWord()) {
                $validator->errors()->add('task_word_id', 'You do not have permission to access this task word.');
            }
        });
    }

    private function userOwnsWord(): bool
    {
        return \DB::table('words')
            ->join('subjects', 'words.subject_id', '=', 'subjects.id')
            ->where('words.id', $this->word_id)
            ->where('subjects.user_id', Auth::id())
            ->exists();
    }

    private function userOwnsTaskWord(): bool
    {
        return \DB::table('task_words')
            ->join('words', 'task_words.word_id', '=', 'words.id')
            ->join('subjects', 'words.subject_id', '=', 'subjects.id')
            ->where('task_words.id', $this->task_word_id)
            ->where('subjects.user_id', Auth::id())
            ->exists();
    }
}
