<?php

namespace Tests\Feature\Api;

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
     * O recurso que será manipulado pelo atores.
     *
     * @var User
     */
    protected $resource;

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
     * Testa se um usuário anônimo pode gerenciar recurso de usuário na API.
     *
     * @return void
     */
    public function test_anonymous_can_manage_user_resource_in_api()
    {
        // CREATE
        $data = factory(User::class)->make()->toArray();
        $data['actor'] = factory(Actor::class)->make()->toArray();
        $response = $this->json('post', '/api/user', $data);
        $response->assertStatus(401);

        // Cria um recurso no banco para testar o gerenciamento.
        $resource = factory(User::class)->create()->toArray();
        $resource['actor'] = factory(Actor::class)->create(['user_id' => $resource['id']])->toArray();

        // INDEX
        $response = $this->json('get', '/api/user');
        $response->assertStatus(401);
        // EDIT
        $resource['email'] = 'Teste de atualização do nome';
        $resource['email'] = str_random() . '@email.com';
        $resource['actor']['is_player'] = !$resource['actor']['is_player'];
        $response = $this->json('put', '/api/user/' . $resource['id'], $resource);
        $response->assertStatus(401);
        // DELETE
        $response = $this->json('delete', '/api/user/' . $resource['id']);
        $response->assertStatus(401);
    }

    /**
     * Testa se um usuário administrador pode gerenciar recurso de usuário na API.
     *
     * @return void
     */
    public function test_administrator_can_manage_user_resource_in_api()
    {
        // CREATE
        $data = factory(User::class)->make()->toArray();
        $data['actor'] = factory(Actor::class)->make()->toArray();
        $response = $this->actingAs($this->administrator, 'api')
            ->json('post', '/api/user', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);

        // Cria um recurso no banco para testar o gerenciamento.
        $resource = factory(User::class)->create()->toArray();
        $resource['actor'] = factory(Actor::class)->create(['user_id' => $resource['id']])->toArray();

        // INDEX
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/user');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                ["id", "name", "email", "actor" => ["is_administrator", "is_design", "is_player"]]
            ]
        ]);
        // EDIT
        $resource['email'] = 'Teste de atualização do nome';
        $resource['email'] = str_random() . '@email.com';
        $resource['actor']['is_player'] = !$resource['actor']['is_player'];
        $response = $this->actingAs($this->administrator, 'api')
            ->json('put', '/api/user/' . $resource['id'], $resource);
        $response->assertStatus(200);
        $response->assertJson(["data" => [
            "name" => $resource["name"],
            "email" => $resource["email"],
            "actor" => ["is_player" => $resource["actor"]["is_player"]]
        ]]);
        // DELETE
        $response = $this->actingAs($this->administrator, 'api')
            ->json('delete', '/api/user/' . $resource['id']);
        $response->assertStatus(204);
    }
}
