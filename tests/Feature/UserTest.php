<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Actor;

class UserTest extends TestCase
{
    /**
     * Usuário administrador
     *
     * @var User
     */
    protected $administrator;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Cria um usuário aleatório com nível de administrador
        $this->administrator = factory(User::class)->create();
        $this->assertDatabaseHas('users', $this->administrator->toArray());
        $actor = factory(Actor::class)->create(['user_id' => $this->administrator->id, 'is_administrator' => true]);
        $this->assertDatabaseHas('actors', $actor->toArray());
        $this->assertTrue($actor->is_administrator);
    }

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
        // Dados da requisição
        // ATENÇÃO! É necessário pelo menos um registro de usuário no banco para que o teste passe, por isso
        // o usuário abaixo é criado.
        $data = factory(User::class)->create()->toArray();
        $data['actor'] = factory(Actor::class)->create(['user_id' => $data['id']])->toArray();
        // Requisição, resposta e asserções
        $response = $this->actingAs($this->administrator)->json('get', '/api/user');
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
        // Dados da requisição
        $data = factory(User::class)->make()->toArray();
        $data['actor'] = factory(Actor::class)->make()->toArray();
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
        // Dados da requisição
        $data = factory(User::class)->make()->toArray();
        $data['actor'] = factory(Actor::class)->make()->toArray();
        // Requisição, resposta e asserções
        $response = $this->actingAs($this->administrator)->json('post', '/api/user', $data);
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
        // Dados da requisição
        $data = factory(User::class)->create()->toArray();
        $data['actor'] = factory(Actor::class)->create(['user_id' => $data['id']])->toArray();
        // Alteração de alguns dados.
        $data['email'] = 'Teste de atualização do nome';
        $data['email'] = str_random() . '@email.com';
        $data['actor']['is_player'] = !$data['actor']['is_player'];
        // Requisição, resposta e asserções
        $response = $this->json('put', '/api/user/' . $data['id'], $data);
        $response->assertStatus(403);
    }

    /**
     * Testa se um usuário (administrador) pode editar um usuário.
     *
     * @return void
     */
    public function test_administrator_can_edit_user()
    {
        // Dados da requisição
        $data = factory(User::class)->create()->toArray();
        $data['actor'] = factory(Actor::class)->create(['user_id' => $data['id']])->toArray();
        // Alteração de alguns dados.
        $data['email'] = 'Teste de atualização do nome';
        $data['email'] = str_random() . '@email.com';
        $data['actor']['is_player'] = !$data['actor']['is_player'];
        // Requisição, resposta e asserções
        $response = $this->actingAs($this->administrator)->json('put', '/api/user/' . $this->administrator->id, $data);
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
        // Dados da requisição
        $data = factory(User::class)->create()->toArray();
        $data['actor'] = factory(Actor::class)->create(['user_id' => $data['id']])->toArray();
        // Requisição, resposta e asserções
        $response = $this->json('delete', '/api/user/' . $data['id']);
        $response->assertStatus(403);
    }

    /**
     * Testa se um usuário (anônimo) pode remover um usuário
     *
     * @return void
     */
    public function test_administrator_can_remove_user()
    {
        // Dados da requisição
        $data = factory(User::class)->create()->toArray();
        $data['actor'] = factory(Actor::class)->create(['user_id' => $data['id']])->toArray();
        // Requisição, resposta e asserções
        $response = $this->actingAs($this->administrator)->json('delete', '/api/user/' . $data['id']);
        $response->assertStatus(204);
    }
}
