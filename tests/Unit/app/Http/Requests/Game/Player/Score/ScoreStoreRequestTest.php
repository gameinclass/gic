<?php
namespace Tests\Unit\app\Http\Requests\Game\Player\Score;

use Tests\TestCase;
use App\Http\Requests\Game\Player\Score\ScoreStoreRequest;

class ScoreStoreRequestTest extends TestCase
{
    /**
     * Testa se o usuário está autorizado a fazer a requisição.
     *
     * @return void
     */
    public function test_authorize()
    {
        $scoreStoreRequest = new ScoreStoreRequest();
        $this->assertTrue($scoreStoreRequest->authorize());
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
            'value' => 'required|numeric',
        ];
        $scoreStoreRequest = new ScoreStoreRequest();
        $this->assertEquals($rules, $scoreStoreRequest->rules());
    }
}