<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyFormRequest extends FormRequest
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
           'name' => 'required|string',
           'email' => [
               'required',
               'string',
               'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
           ],
           'status' => 'required|bool' ,
           'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'email.regex' => 'Email Formate Is Incorrect',
        ];
    }

    public function fields()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
        ];
    }

    public function action()
    {
        return $this->id ? 'Updated' : 'Created';
    }
}
