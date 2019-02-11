<?php

namespace Tests\Feature\app\Http\Requests\Medal;

use Tests\TestCase;
use App\Http\Requests\Medal\MedalUpdateRequest;

class MedalUpdateRequestTest extends TestCase
{
    /**
     * Testa se o usuário está autorizado a fazer a requisição.
     *
     * @return void
     */
    public function test_authorize()
    {
        $medalUpdateRequest = new MedalUpdateRequest();
        $this->assertTrue($medalUpdateRequest->authorize());
    }

    /**
     * Testa as regras de validação que será aplicado a requisição
     *
     * @return void
     */
    public function test_rules()
    {
        $rules = [
            'title' => 'required|min:1|max:255',
            'description' => 'required',
        ];
        $medalUpdateRequest = new MedalUpdateRequest();
        $this->assertEquals($rules, $medalUpdateRequest->rules());
    }
}
