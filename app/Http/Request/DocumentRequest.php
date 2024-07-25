<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|file|mimes:csv,txt|max:102400', // max size is optional
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Please upload a CSV file.',
            'file.mimes' => 'The file must be a file of type: csv.',
            'file.max' => 'The file may not be greater than 100MB.',
        ];
    }
}
