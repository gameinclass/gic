<?php
namespace Tests\Feature\app\Http\Requests\Game;

use Tests\TestCase;
use App\Http\Requests\Game\GameUpdateRequest;

class GameUpdateRequestTest extends TestCase
{
    /**
     * Testa se o usuário está autorizado a fazer a requisição.
     *
     * @return void
     */
    public function test_authorize()
    {
        $gameUpdateRequest = new GameUpdateRequest();
        $this->assertTrue($gameUpdateRequest->authorize());
    }

    /**
     * Testa as regras de validação que será aplicado a requisição
     *
     * @return void
     */
    public function test_rules()
    {
        $rules = [
            'title' => 'min:1|max:255',
        ];
        $gameUpdateRequest = new GameUpdateRequest();
        $this->assertEquals($rules, $gameUpdateRequest->rules());
    }
}
