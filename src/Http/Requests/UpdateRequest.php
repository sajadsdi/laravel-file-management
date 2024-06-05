<?php

namespace Sajadsdi\LaravelFileManagement\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'updates' => ['required', 'array'],
            'updates.title' => ['nullable','string'],
            'updates.disk' => ['nullable','string'],
        ];
    }
}
