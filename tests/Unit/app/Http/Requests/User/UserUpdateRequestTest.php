<?php

namespace Tests\Feature\app\Http\Requests\User;

use Tests\TestCase;
use App\Http\Requests\User\UserUpdateRequest;

class UserUpdateRequestTest extends TestCase
{
    /**
     * Testa se o usuário está autorizado a fazer a requisição.
     *
     * @return void
     */
    public function test_authorize()
    {
        $userStoreRequest = new UserUpdateRequest();
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
            'name' => 'min:1|max:255',
            'email' => 'email|unique:users',
            'actor.is_administrator' => 'boolean',
            'actor.is_design' => 'boolean',
            'actor.is_player' => 'boolean',
        ];
        $userStoreRequest = new UserUpdateRequest();
        $this->assertEquals($rules, $userStoreRequest->rules());
    }
}
