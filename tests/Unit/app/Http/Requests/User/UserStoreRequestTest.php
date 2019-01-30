<?php

namespace Tests\Feature\app\Http\Requests;

use Tests\TestCase;
use App\Http\Requests\User\UserStoreRequest;

class UserStoreRequestTest extends TestCase
{
    /**
     * Testa se o usuário está autorizado a fazer a requisição.
     *
     * @return void
     */
    public function test_authorize()
    {
        $userStoreRequest = new UserStoreRequest();
        $this->assertTrue($userStoreRequest->authorize());
    }

    /**
     * Testa as regras de validação que será aplicado a requisição
     *
     * @return void
     */
    public function test_rules()
    {
        $rules = [
            'name' => 'required|min:1|max:255',
            'email' => 'required|email|unique:users',
            'is_administrator' => 'required|boolean',
            'is_design' => 'required|boolean',
            'is_player' => 'required|boolean',
        ];
        $userStoreRequest = new UserStoreRequest();
        $this->assertEquals($rules, $userStoreRequest->rules());

    }
}