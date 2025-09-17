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
        
        /*
        && Word::where('id', $this->route('word'))
                                ->whereIn('subject_id', function($query) {
                                    $query->select('id')
                                    ->from('subjects')
                                    ->where('user_id', auth()->user()->id);
                                })->exists();   
        */
        
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

     protected function prepareForValidation()
    {
        $this->merge([
            'word' => $this->route('word')
        ]);
    }


    public function rules(): array
    {
         return [
             'word'=>[ 'required','uuid',new WordBelongsToUser ]
        ];
    }
 
  

}
