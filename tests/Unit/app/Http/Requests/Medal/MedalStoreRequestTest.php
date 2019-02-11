<?php

namespace Tests\Feature\app\Http\Requests\Medal;

use Tests\TestCase;
use App\Http\Requests\Medal\MedalStoreRequest;

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
            'title' => 'required|min:1|max:255',
            'description' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:10000'
        ];
        $medalStoreRequest = new MedalStoreRequest();
        $this->assertEquals($rules, $medalStoreRequest->rules());
    }
}
