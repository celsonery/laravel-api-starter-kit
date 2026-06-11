<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreJogoRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jogo' => ['required', 'integer'],
            'numbers' => ['required', 'array', 'min:6', 'max:12'],
            'numbers.*' => ['required', 'integer', 'between:1,60', 'distinct']
        ];
    }
}
