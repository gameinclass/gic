<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Actor;

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
            "links" => ["first", "last", "prev", "next"], "data" => [
                ["id", "name", "email", "actor" => ["is_administrator", "is_design", "is_player"]]
            ]
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

    /**
     * Testa se um usuário (anônimo) pode editar um usuário.
     *
     * @return void
     */
    public function test_it_can_edit_user()
    {
        // Recupera um usuário aleatório
        $user = User::with('actor')->get()->random();
        // Altera os dados do usuário
        $user->name = 'Maria Silva';
        $user->email = str_random() . '@email.com';
        $user->actor->is_player = (bool)rand(0, 1);
        // Dados da requisição
        $data = $user->toArray();
        $data['actor'] = $user->actor->toArray();
        // Requisição, resposta e asserções
        $response = $this->json('put', '/api/user/' . $user->id, $data);
        $response->assertStatus(200);
        $response->assertJson(["data" => [
            "name" => $data["name"],
            "email" => $data["email"],
            "actor" => ["is_player" => $data["actor"]["is_player"]]
        ]]);
    }

    /**
     * Testa se um usuário (anônimo) pode remover um usuário
     *
     * @return void
     */
    public function test_it_can_destroy_user()
    {
        // Recupera um usuário aleatório
        $user = User::with('actor')->get()->random();
        // Requisição, resposta e asserções
        $response = $this->json('delete', '/api/user/' . $user->id);
        $response->assertStatus(204);
    }
}
