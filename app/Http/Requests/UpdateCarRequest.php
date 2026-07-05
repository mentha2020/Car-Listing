<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isClient() && $this->route('car')->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'fuel_type' => ['required', 'in:petrol,diesel,electric,hybrid'],
            'transmission' => ['required', 'in:manual,automatic'],
            'mileage' => ['required', 'integer', 'min:0'],
            'city' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:1'],
            'description' => ['nullable', 'string', 'max:2000'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ];
    }
}
