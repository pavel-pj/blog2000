<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use App\Models\Subject;
use App\Models\Topic;
use App\Rules\WordBelongsToUser;


class TopicShowRequest extends FormRequest
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
 
    public function rules(): array
    {
         return [
              
        ];
    }

        public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $topicId = $this->route('topic'); // Получаем id из route
             
  
            // Проверяем существование и принадлежность
            $topic= Topic::find($topicId);
            
            if (!$topic) {
                  throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Subject not found');
            }
            
            if ($topic->subject->user_id !== Auth::id()) {
                  throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Subject not found');
            }
        });
    }
 
  

}
