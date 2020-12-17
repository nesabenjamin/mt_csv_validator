<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class csvValidationRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'csv_file' => 'required|mimes:csv,txt',
        ];
    }

    public function messages()
    {
        return [
            'csv_file.mimes' => 'The file must be a type: csv.',
        ];
    }
}
