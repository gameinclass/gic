<?php
namespace Tests\Feature\app\Http\Requests\Game\Phase;

use Tests\TestCase;
use App\Http\Requests\Game\Phase\PhaseStoreRequest;

class PhaseStoreRequestTest extends TestCase
{
    /**
     * Testa se o usuário está autorizado a fazer a requisição.
     *
     * @return void
     */
    public function test_authorize()
    {
        $phaseStoreRequest = new PhaseStoreRequest();
        $this->assertTrue($phaseStoreRequest->authorize());
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
            'from' => 'required|date',
            'to' => 'required|date'
        ];
        $phaseStoreRequest = new PhaseStoreRequest();
        $this->assertEquals($rules, $phaseStoreRequest->rules());
    }
}
