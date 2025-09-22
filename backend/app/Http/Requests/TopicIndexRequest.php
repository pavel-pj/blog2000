<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use App\Models\Subject;

class TopicIndexRequest extends FormRequest
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
            'id' => ['required', 'uuid'] // Правило для параметра маршрута
        ];
    }

    public function withValidator($validator)
    {
    $validator->after(function ($validator) {
        $subjectId = $this->route('id'); // Получаем id из route
        
        // Проверяем существование и принадлежность
        $subject = Subject::find($subjectId);
        
        if (!$subject) {
           // $validator->errors()->add('subject_id', 'The subject does not exist.');
            //return;
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Subject not found');
        }
        
        if ($subject->user_id !== Auth::id()) {
            //$validator->errors()->add('subject_id', 'The subject does not belong to you.');
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Subject not found');
        }
    });
   }
}
