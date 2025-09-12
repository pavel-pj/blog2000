<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use App\Models\Catalog;

class CatalogUpdateRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $catalogId = $this->route('catalog') ?? $this->route('id');
        $catalog = Catalog::find($catalogId);

        return [
            'name' => [
                'string',
                'nullable',
                Rule::unique('catalogs', 'name')
                    ->ignore($catalogId)
                    ->when(
                        $catalog && $this->input('name') === $catalog->name,
                        function ($rule) {
                            return $rule->where('id', $this->route('catalog') ?? $this->route('id'));
                        }
                    )
            ],
 
        ];
    }
}
