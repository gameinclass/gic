<?php
namespace Tests\Unit\app\Http\Requests\Game\Player\Score;

use Tests\TestCase;
use App\Http\Requests\Game\Player\Score\ScoreUpdateRequest;

class ScoreUpdateRequestTest extends TestCase
{
    /**
     * Testa se o usuário está autorizado a fazer a requisição.
     *
     * @return void
     */
    public function test_authorize()
    {
        $scoreUpdateRequest = new ScoreUpdateRequest();
        $this->assertTrue($scoreUpdateRequest->authorize());
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
        $scoreUpdateRequest = new ScoreUpdateRequest();
        $this->assertEquals($rules, $scoreUpdateRequest->rules());
    }
}