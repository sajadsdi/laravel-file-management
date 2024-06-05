<?php

namespace Sajadsdi\LaravelFileManagement\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class GetIDRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => ['required','string'],
        ];
    }
}
