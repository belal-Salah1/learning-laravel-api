<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Dimensions;

class GeneratePromptRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
  public function rules(): array
{
    return [
        'image' => [
            'required',
            'file',
            'image',
            'mimes:jpeg,png,jpg,gif,svg',
            'max:10240', // 10MB
            'dimensions:min_width=100,min_height=100,max_width=10000,max_height=10000',
        ],
    ];
}



    public function messages(): array
    {
        return [
            'image.required' => 'An image file is required.',
            'image.file' => 'The uploaded file must be a valid file.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'image.max' => 'The image size must not exceed 10MB.',
            'image.min' => 'The image size must not be less than 10KB.',
            'image.dimensions' => 'The image dimensions are invalid. Minimum size is 100x100 pixels and maximum size is 10000x10000 pixels.',
        ];
    }
}
