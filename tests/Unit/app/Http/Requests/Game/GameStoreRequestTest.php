<?php
namespace Tests\Feature\app\Http\Requests\Game;

use Tests\TestCase;
use App\Http\Requests\Game\GameStoreRequest;

class GameStoreRequestTest extends TestCase
{
    /**
     * Testa se o usuário está autorizado a fazer a requisição.
     *
     * @return void
     */
    public function test_authorize()
    {
        $gameStoreRequest = new GameStoreRequest();
        $this->assertTrue($gameStoreRequest->authorize());
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
        $gameStoreRequest = new GameStoreRequest();
        $this->assertEquals($rules, $gameStoreRequest->rules());
    }
}
