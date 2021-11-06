<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class GetUserDepositsByStatusRequest extends FormRequest
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
            'message' => 'The given data is invalid',
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
            "deposit_status_fk" => "required|integer",
            "year" => "required|int|regex:/^\d{4}$/",
            "month" => "required|int|min:1|max:12"
            // |regex:/^(0)(1-9){1}|(10|11|12)
        ];
    }
}
