<?php

namespace Unscode\Pingo\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => 'min:1|max:255',
            'email' => 'email|unique:users',
            'actor.is_administrator' => 'boolean',
            'actor.is_design' => 'boolean',
            'actor.is_player' => 'boolean',
        ];
    }
}
