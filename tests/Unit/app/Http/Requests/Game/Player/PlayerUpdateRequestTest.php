<?php
namespace Tests\Unit\app\Http\Requests\Game\Player;

use Tests\TestCase;
use App\Http\Requests\Game\Player\PlayerUpdateRequest;

class PlayerUpdateRequestTest extends TestCase
{
    /**
     * Testa se o usuário está autorizado a fazer a requisição.
     *
     * @return void
     */
    public function test_authorize()
    {
        $playerUpdateRequest = new PlayerUpdateRequest();
        $this->assertTrue($playerUpdateRequest->authorize());
    }

    /**
     * Testa as regras de validação que será aplicado a requisição
     *
     * @return void
     */
    public function test_rules()
    {
        $rules = [
            'user_id' => 'required|integer',
        ];
        $plauerUpdateRequest = new PlayerUpdateRequest();
        $this->assertEquals($rules, $plauerUpdateRequest->rules());
    }
}