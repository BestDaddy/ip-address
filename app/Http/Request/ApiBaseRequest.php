<?php


namespace App\Http\Request;


use App\Exceptions\ApiServiceException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class ApiBaseRequest extends FormRequest
{
    public abstract function injectedRules();

    public function rules()
    {
        return $this->injectedRules();
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ApiServiceException(
            400,
            false,
            ['errors' => $validator->errors()]
        );
    }
}
