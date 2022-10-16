<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'title'=>'required|string|min:3|max:255',
            'phone'=>'required|string|max:60',
            'description'=>'required|string|min:10|max:255'
        ];
    }
}
