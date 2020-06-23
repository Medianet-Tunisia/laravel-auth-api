<?php

namespace MedianetDev\LaravelAuthApi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiUserRegisterRequest extends FormRequest
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
        $user = config('laravel-auth-api.user_model_fqn');

        return [
            'name' => 'required|min:4|max:20',
            'email' => 'required|unique:'.(new $user)->getTable().',email',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
        /*'name.required' => '',
            'name.min' => '',
            'name.max' => '',
            'email.required' => '',
            'email.unique' => '',
            'password.unique' => '',
            'password.min' => '',
            'c_password.required' => '',
            'c_password.same' => '',
            */];
    }
}
