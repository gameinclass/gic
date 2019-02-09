<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Actor;

class GameTest extends TestCase
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
     * O recurso que será manipulado pelo atores
     *
     * @var User
     */
    protected $resource;

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
        $actor = factory(Actor::class)->create(['user_id' => $this->administrator->id, 'is_administrator' => true]);
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
    public function setUp()
    {
        parent::setUp();
        $this->setUpUserAdministrator();
        $this->setUpUserDesign();
        $this->setUpUserPlayer();
    }

    /**
     * Testa se um usuário anônimo pode gerenciar recurso de jogo na API.
     *
     * @return void
     */
    public function test_anonymous_can_manage_game_resource_in_api()
    {
        // CREATE
        $data = factory(Game::class)->make()->toArray();
        $response = $this->json('post', '/api/game', $data);
        $response->assertStatus(401);

        // Cria um recurso no banco para testar o gerenciamento.
        $resource = factory(Game::class)->create()->toArray();

        // INDEX
        $response = $this->json('get', '/api/game');
        $response->assertStatus(401);
        // EDIT
        $resource['title'] = 'Teste de atualização do título';
        $resource['description'] = 'Teste de atualização de outra descrição';
        $response = $this->json('put', '/api/game/' . $resource['id'], $resource);
        $response->assertStatus(401);
        // DELETE
        $response = $this->json('delete', '/api/game/' . $resource['id']);
        $response->assertStatus(401);
    }

    /**
     * Testa se um usuário administrador pode gerenciar recurso de jogo na API.
     *
     * @return void
     */
    public function test_administrator_can_manage_game_resource_in_api()
    {
        // CREATE
        $data = factory(Game::class)->make()->toArray();
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);

        // Cria um recurso no banco para testar o gerenciamento.
        $resource = factory(Game::class)->create()->toArray();

        // INDEX
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                ["id", "title", "description", "groups", "players"]
            ]
        ]);
        // EDIT
        $resource['title'] = 'Teste de atualização do título';
        $resource['description'] = 'Teste de atualização de outra descrição';
        $response = $this->actingAs($this->administrator)
            ->json('put', '/api/game/' . $resource['id'], $resource);
        $response->assertStatus(200);
        $response->assertJson(["data" => [
            "title" => $resource["title"],
            "description" => $resource["description"],
        ]]);
        // DELETE
        $response = $this->actingAs($this->administrator, 'api')
            ->json('delete', '/api/game/' . $resource['id']);
        $response->assertStatus(204);
    }
}
