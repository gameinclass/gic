<?php
namespace Tests\Unit\app\Http\Requests\Game\Player\Medal;

use Tests\TestCase;
use App\Http\Requests\Game\Player\Medal\MedalStoreRequest;

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
            'id' => 'required|integer',
        ];
        $medalStoreRequest = new MedalStoreRequest();
        $this->assertEquals($rules, $medalStoreRequest->rules());
    }
}