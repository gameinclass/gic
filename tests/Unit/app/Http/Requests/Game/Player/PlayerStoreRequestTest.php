<?php

namespace Tests\Unit\app\Http\Requests\Game\Player;

use Tests\TestCase;
use App\Http\Requests\Game\Player\PlayerStoreRequest;

class PlayerStoreRequestTest extends TestCase
{
    /**
     * Testa se o usuário está autorizado a fazer a requisição.
     *
     * @return void
     */
    public function test_authorize()
    {
        $playerStoreRequest = new PlayerStoreRequest();
        $this->assertTrue($playerStoreRequest->authorize());
    }

    /**
     * Testa as regras de validação que será aplicado a requisição
     *
     * @return void
     */
    public function test_rules()
    {
        $rules = [
            'id' => 'required|integer',
        ];
        $plauerStoreRequest = new PlayerStoreRequest();
        $this->assertEquals($rules, $plauerStoreRequest->rules());
    }
}