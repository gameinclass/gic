<?php

namespace Tests\Unit\app\Http\Requests\Game\Medal;

use Tests\TestCase;
use App\Http\Requests\Game\Medal\MedalStoreRequest;

class MedalStoreRequestTest extends TestCase
{
    /**
     * Testa se o usuário está autorizado a fazer a requisição.
     *
     * @return void
     */
    public function test_authorize()
    {
        $medalStoreRequest = new MedalStoreRequest();
        $this->assertTrue($medalStoreRequest->authorize());
    }

    /**
     * Testa as regras de validação que será aplicado a requisição
     *
     * @return void
     */
    public function test_rules()
    {
        $rules = [
            'medal_id' => 'required|integer'
        ];
        $medalStoreRequest = new MedalStoreRequest();
        $this->assertEquals($rules, $medalStoreRequest->rules());
    }
}