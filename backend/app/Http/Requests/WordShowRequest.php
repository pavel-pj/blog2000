<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use App\Models\Subject;
use App\Models\Word;
use App\Rules\WordBelongsToUser;


class WordShowRequest extends FormRequest
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
