<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientRequest extends FormRequest
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
        $id = request('id');
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'birth_date' => 'required',
            'email' => 'required|email|max:255|unique:patients',
            'email' => ['required','email', Rule::unique('patients')->ignore($id) ]//'required|email|max:255|unique:patients',   
            'province' => 'required',
            
        ];
    }
}
