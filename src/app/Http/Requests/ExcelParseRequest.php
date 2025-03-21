<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExcelParseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1',
            'name' => 'required|string|regex:/^[a-zA-Z\s]+$/',
            'date' => 'required|date_format:d.m.Y|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'id.required' => 'Поле "ID" обязательно для заполнения.',
            'id.integer' => 'Поле "ID" должно быть целым числом.',
            'id.min' => 'Поле "ID" должно быть больше 0.',

            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'name.string' => 'Поле "Имя" должно быть строкой.',
            'name.regex' => 'Поле "Имя" должно содержать только буквы английского алфавита и пробелы.',

            'date.required' => 'Поле "Дата" обязательно для заполнения.',
            'date.date_format' => 'Поле "Дата" должно быть в формате дд.мм.гггг.',
            'date.date' => 'Поле "Дата" должно быть валидным.',
        ];
    }
}
