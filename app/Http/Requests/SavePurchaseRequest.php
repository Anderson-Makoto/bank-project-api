<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class SavePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new JsonResponse([
            'description' => 'The given data is invalid',
            'errors' => $validator->errors()
        ], 400);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "user_fk" => "required|integer",
            "description" => "required|string|max:20",
            "value" => "required|numeric|regex:/^\d{1,13}(\.\d{1,2})?$/",
            "purchase_date" => "required|date"
        ];
    }
}
