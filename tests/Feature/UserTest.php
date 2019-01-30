<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Models\Actor;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * Verifica se um usuário (anônimo) pode listar todos os usuários.
     *
     * @return void
     */
    public function test_it_can_index_users()
    {
        // Requisição, resposta e asserções
        $response = $this->json('get', '/api/user');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data"
        ]);
    }

    /**
     * Verifica se um usuário (anônimo) pode criar um usuário.
     *
     * @return void
     */
    public function test_it_can_create_user()
    {
        // Fábrica falsa
        $user = factory(User::class)->make();
        $actor = factory(Actor::class)->make();
        // Dados de requisição
        $data = $user->toArray();
        $data['actor'] = $actor->toArray();
        // Requisição, resposta e asserções
        $response = $this->json('post', '/api/user', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);
    }
}
