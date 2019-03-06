<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Actor;

class UserTest extends TestCase
{
    /**
     * Usuário com nível de administrador
     *
     * @var User
     */
    protected $administrator;

    /**
     * Usuário com nível de design
     *
     * @var User
     */
    protected $design;

    /**
     * Usuário com nível de player
     *
     * @var User
     */
    protected $player;

    /**
     * Configurar usuário administrador
     *
     * @return void
     */
    private function setUpUserAdministrator()
    {
        // Cria um usuário aleatório com nível de administrador
        $this->administrator = factory(User::class)->create();
        $this->assertDatabaseHas('users', $this->administrator->toArray());
        $actor = factory(Actor::class)->create([
            'user_id' => $this->administrator->id,
            'is_administrator' => true
        ]);
        $this->assertDatabaseHas('actors', $actor->toArray());
        $this->assertTrue($actor->is_administrator);
    }

    /**
     * Configurar usuário design
     *
     * @return void
     */
    private function setUpUserDesign()
    {
        // Cria um usuário aleatório com nível de design
        $this->design = factory(User::class)->create();
        $this->assertDatabaseHas('users', $this->design->toArray());
        $actor = factory(Actor::class)->create([
            'user_id' => $this->design->id,
            'is_administrator' => false,
            'is_design' => true,
            'is_player' => false
        ]);
        $this->assertDatabaseHas('actors', $actor->toArray());
        $this->assertFalse($actor->is_administrator);
        $this->assertTrue($actor->is_design);
        $this->assertFalse($actor->is_player);
    }

    /**
     * Configurar usuário jogador
     */
    private function setUpUserPlayer()
    {
        // Cria um usuário aleatório com nível de player
        $this->player = factory(User::class)->create();
        $this->assertDatabaseHas('users', $this->player->toArray());
        $actor = factory(Actor::class)->create([
            'user_id' => $this->player->id,
            'is_administrator' => false,
            'is_design' => false,
            'is_player' => true
        ]);
        $this->assertDatabaseHas('actors', $actor->toArray());
        $this->assertFalse($actor->is_administrator);
        $this->assertFalse($actor->is_design);
        $this->assertTrue($actor->is_player);
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUserAdministrator();
        $this->setUpUserDesign();
        $this->setUpUserPlayer();
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
        $data = factory(User::class)->create()->toArray();
        $data['actor'] = factory(Actor::class)->create(['user_id' => $data['id']])->toArray();
        // INDEX
        $response = $this->json('get', '/api/user');
        $response->assertStatus(401);
        // EDIT
        $data['email'] = 'Teste de atualização do nome';
        $data['email'] = str_random() . '@email.com';
        $data['actor']['is_player'] = !$data['actor']['is_player'];
        $response = $this->json('put', '/api/user/' . $data['id'], $data);
        $response->assertStatus(401);
        // DELETE
        $response = $this->json('delete', '/api/user/' . $data['id']);
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
        $data = factory(User::class)->create()->toArray();
        $data['actor'] = factory(Actor::class)->create(['user_id' => $data['id']])->toArray();

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
        $data['name'] = 'Teste de atualização do nome';
        $data['email'] = str_random() . '@email.com';
        $data['actor']['is_player'] = !$data['actor']['is_player'];
        $response = $this->actingAs($this->administrator, 'api')
            ->json('put', '/api/user/' . $data['id'], $data);
        $response->assertStatus(200);
        $response->assertJson(["data" => [
            "name" => $data["name"],
            "email" => $data["email"],
            "actor" => ["is_player" => $data["actor"]["is_player"]]
        ]]);
        // DELETE
        $response = $this->actingAs($this->administrator, 'api')
            ->json('delete', '/api/user/' . $data['id']);
        $response->assertStatus(204);
    }

    /**
     * Testa se um usuário design pode gerenciar recurso de usuário na API.
     *
     * @return void
     */
    public function test_design_can_manage_user_resource_in_api()
    {
        // CREATE
        $data = factory(User::class)->make()->toArray();
        $data['actor'] = factory(Actor::class)->make()->toArray();
        $response = $this->actingAs($this->design, 'api')
            ->json('post', '/api/user', $data);
        $response->assertStatus(403);

        // Cria um recurso no banco para testar o gerenciamento.
        $data = factory(User::class)->create()->toArray();
        $data['actor'] = factory(Actor::class)->create(['user_id' => $data['id']])->toArray();

        // INDEX
        $response = $this->actingAs($this->design, 'api')->json('get', '/api/user');
        $response->assertStatus(403);

        // EDIT
        $data['name'] = 'Teste de atualização do nome';
        $data['email'] = str_random() . '@email.com';
        $data['actor']['is_player'] = !$data['actor']['is_player'];
        $response = $this->actingAs($this->design, 'api')
            ->json('put', '/api/user/' . $data['id'], $data);
        $response->assertStatus(403);
        // Atenção! Testa se o usuário pode atualizar seus próprios dados.
        $this->design->name = 'Teste de atualização do nome';
        $this->design->email = str_random() . '@email.com';
        // Brecha de segurança !!!
        $this->design->actor->is_player = !$this->design->actor->is_player;
        $response = $this->actingAs($this->design, 'api')
            ->json('put', '/api/user/' . $this->design->id, $this->design->toArray());
        $response->assertStatus(200);
        $response->assertJson(["data" => [
            "name" => $this->design->name,
            "email" => $this->design->email,
            "actor" => ["is_player" => $this->design->actor->is_player]
        ]]);
        // DELETE
        $response = $this->actingAs($this->design, 'api')
            ->json('delete', '/api/user/' . $data['id']);
        $response->assertStatus(403);
        // Atenção! Testa se o usuário pode se auto remover
        $response = $this->actingAs($this->design, 'api')
            ->json('delete', '/api/user/' . $this->design->id);
        $response->assertStatus(204);
    }

    /**
     * Testa se um usuário jogador pode gerenciar recurso de usuário na API.
     *
     * @return void
     */
    public function test_player_can_manage_user_resource_in_api()
    {
        // CREATE
        $data = factory(User::class)->make()->toArray();
        $data['actor'] = factory(Actor::class)->make()->toArray();
        $response = $this->actingAs($this->player, 'api')
            ->json('post', '/api/user', $data);
        $response->assertStatus(403);

        // Cria um recurso no banco para testar o gerenciamento.
        $data = factory(User::class)->create()->toArray();
        $data['actor'] = factory(Actor::class)->create(['user_id' => $data['id']])->toArray();

        // INDEX
        $response = $this->actingAs($this->player, 'api')->json('get', '/api/user');
        $response->assertStatus(403);

        // EDIT
        $data['name'] = 'Teste de atualização do nome';
        $data['email'] = str_random() . '@email.com';
        $data['actor']['is_player'] = !$data['actor']['is_player'];
        $response = $this->actingAs($this->player, 'api')
            ->json('put', '/api/user/' . $data['id'], $data);
        $response->assertStatus(403);
        // Atenção! Testa se o usuário pode atualizar seus próprios dados.
        $this->player->name = 'Teste de atualização do nome';
        $this->player->email = str_random() . '@email.com';
        // Brecha de segurança !!!
        $this->player->actor->is_player = $this->player->actor->is_player;
        $response = $this->actingAs($this->player, 'api')
            ->json('put', '/api/user/' . $this->player->id, $this->player->toArray());
        $response->assertStatus(200);
        $response->assertJson(["data" => [
            "name" => $this->player->name,
            "email" => $this->player->email,
            "actor" => ["is_player" => $this->player->actor->is_player]
        ]]);
        // DELETE
        $response = $this->actingAs($this->player, 'api')
            ->json('delete', '/api/user/' . $data['id']);
        $response->assertStatus(403);
        // Atenção! Testa se o usuário pode se auto remover
        $response = $this->actingAs($this->player, 'api')
            ->json('delete', '/api/user/' . $this->player->id);
        $response->assertStatus(204);
    }
}
