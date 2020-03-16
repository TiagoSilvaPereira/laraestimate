<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionStoreRequest extends FormRequest
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
            'text' => 'nullable|string',
            'type' => 'required|string',
            'items' => 'nullable|array',
            'items.*.description' => 'nullable|string',
            'items.*.duration' => 'nullable|string',
            'items.*.price' => 'nullable|number',
        ];
    }
}
