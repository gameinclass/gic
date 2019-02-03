<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Actor;

class UserTest extends TestCase
{
    /**
     * Testa se um usuário (anônimo) pode listar todos os usuários.
     *
     * @return void
     */
    public function test_anonymous_can_index_users()
    {
        // Requisição, resposta e asserções
        $response = $this->json('get', '/api/user');
        $response->assertStatus(403);
    }

    /**
     * Testa se um usuário (administrador) pode listar todos os usuários.
     *
     * @return void
     */
    public function test_administrator_can_index_users()
    {
        // Cria um usuário aleatório
        $user = factory(User::class)->create();
        $this->assertDatabaseHas('users', $user->toArray());
        $actor = factory(Actor::class)->create(['user_id' => $user->id, 'is_administrator' => true]);
        $this->assertDatabaseHas('actors', $actor->toArray());
        $this->assertTrue($actor->is_administrator);

        // Requisição, resposta e asserções
        $response = $this->actingAs($user)->json('get', '/api/user');
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
    public function test_anonymous_can_create_user()
    {
        // Fábrica falsa
        $user = factory(User::class)->make();
        $actor = factory(Actor::class)->make();
        // Dados de requisição
        $data = $user->toArray();
        $data['actor'] = $actor->toArray();
        // Requisição, resposta e asserções
        $response = $this->json('post', '/api/user', $data);
        $response->assertStatus(403);
    }

    /**
     * Verifica se um usuário (administrador) pode criar um usuário.
     *
     * @return void
     */
    public function test_administrator_can_create_user()
    {
        // Cria um usuário aleatório
        $user = factory(User::class)->create();
        $this->assertDatabaseHas('users', $user->toArray());
        $actor = factory(Actor::class)->create(['user_id' => $user->id, 'is_administrator' => true]);
        $this->assertDatabaseHas('actors', $actor->toArray());
        $this->assertTrue($actor->is_administrator);

        // Dados de requisição
        $data = factory(User::class)->make()->toArray();
        $data['actor'] = factory(Actor::class)->make()->toArray();
        // Requisição, resposta e asserções
        $response = $this->actingAs($user)->json('post', '/api/user', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);
    }

    /**
     * Testa se um usuário (anônimo) pode editar um usuário.
     *
     * @return void
     */
    public function test_anonymous_can_edit_user()
    {
        // Recupera um usuário aleatório
        $user = User::with('actor')->get()->random();
        // Dados de requisição
        $data = factory(User::class)->make()->toArray();
        $data['actor'] = factory(Actor::class)->make()->toArray();
        // Requisição, resposta e asserções
        $response = $this->json('put', '/api/user/' . $user->id, $data);
        $response->assertStatus(403);
    }

    /**
     * Testa se um usuário (administrador) pode editar um usuário.
     *
     * @return void
     */
    public function test_administrator_can_edit_user()
    {
        // Cria um usuário aleatório
        $user = factory(User::class)->create();
        $this->assertDatabaseHas('users', $user->toArray());
        $actor = factory(Actor::class)->create(['user_id' => $user->id, 'is_administrator' => true]);
        $this->assertDatabaseHas('actors', $actor->toArray());
        $this->assertTrue($actor->is_administrator);

        // Altera alguns dados
        $user->email = str_random() . '@email.com';
        // Dados da requisição
        $data = $user->toArray();
        $data['actor'] = $user->actor->toArray();
        // Requisição, resposta e asserções
        $response = $this->actingAs($user)->json('put', '/api/user/' . $user->id, $data);
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
    public function test_anonymous_can_remove_user()
    {
        // Recupera um usuário aleatório
        $user = User::with('actor')->get()->random();
        // Requisição, resposta e asserções
        $response = $this->json('delete', '/api/user/' . $user->id);
        $response->assertStatus(403);
    }

    /**
     * Testa se um usuário (anônimo) pode remover um usuário
     *
     * @return void
     */
    public function test_administrator_can_remove_user()
    {
        // Cria um usuário aleatório
        $user = factory(User::class)->create();
        $this->assertDatabaseHas('users', $user->toArray());
        $actor = factory(Actor::class)->create(['user_id' => $user->id, 'is_administrator' => true]);
        $this->assertDatabaseHas('actors', $actor->toArray());
        $this->assertTrue($actor->is_administrator);

        // ATENÇÃO! Remove o proprio usuário criado para a ação.

        // Requisição, resposta e asserções
        $response = $this->actingAs($user)->json('delete', '/api/user/' . $user->id);
        $response->assertStatus(204);
    }
}
