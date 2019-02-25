<?php

namespace Tests\Feature\app\Http\Requests\Game;

use Tests\TestCase;
use App\Http\Requests\Game\Phase\PhaseUpdateRequest;

class PhaseUpdateRequestTest extends TestCase
{
    /**
     * Testa se o usuário está autorizado a fazer a requisição.
     *
     * @return void
     */
    public function test_authorize()
    {
        $phaseUpdateRequest = new PhaseUpdateRequest();
        $this->assertTrue($phaseUpdateRequest->authorize());
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
            'from' => 'required|date_format:Y-m-d H:i:s',
            'to' => 'required|date_format:Y-m-d H:i:s'
        ];
        $phaseUpdateRequest = new PhaseUpdateRequest();
        $this->assertEquals($rules, $phaseUpdateRequest->rules());
    }
}
