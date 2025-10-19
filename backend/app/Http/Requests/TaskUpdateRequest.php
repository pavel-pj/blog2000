<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use App\Models\Subject;
use Illuminate\Validation\Rule;
use App\Models\Task;
use App\Models\Topic;
use App\Enums\TaskWordStatus;

class TaskUpdateRequest extends FormRequest
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
            'status' =>  [
                    'nullable',
                    'string',
                    Rule::enum(TaskWordStatus::class)
                ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $taskId = $this->route('task'); // Получаем id из route
            
            // Проверяем существование и принадлежность
            $task = Task::find($taskId);
            
            if (!$task) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Subject not found');
            }
            
            if ($task->repetition->subject->user_id !== Auth::id()) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Subject not found');
            }
        });
    }


}
