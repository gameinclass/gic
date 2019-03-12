<?php

namespace Tests\Feature\Api\Game\Phase;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Phase;
use App\Models\Actor;

class PhaseTest extends TestCase
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
     * Teste de integração
     * Testa se um usuário anônimo pode gerenciar recurso de fase de jogo na API.
     *
     * @return void
     */
    public function test_anonymous_can_manage_game_phase_resource_in_api()
    {
        // Cria um recurso de jogo no banco de dados.
        $game = factory(Game::class)->create()->toArray();

        // CREATE
        $data = factory(Phase::class)->make()->toArray();
        $response = $this->json('post', '/api/game/' . $game['id'] . '/phase', $data);
        $response->assertStatus(401);
        // INDEX
        $response = $this->json('get', '/api/game/' . $game['id'] . '/phase');
        $response->assertStatus(401);
        // EDIT
        $resource = factory(Phase::class)->create(['game_id' => $game['id']])->toArray();
        $resource['name'] = 'Teste de atualização do título';
        $response = $this->json('put', '/api/game/' . $game['id'] . '/phase/' . $resource['id'], $resource);
        $response->assertStatus(401);
        // DELETE
        $response = $this->json('delete', '/api/game/' . $game['id'] . '/phase/' . $resource['id']);
        $response->assertStatus(401);
    }

    /**
     * Teste de integração
     * Testa se um usuário administrador pode gerenciar recurso de fase de jogo na API.
     *
     * @return void
     */
    public function test_administrator_can_manage_game_phase_resource_in_api()
    {
        // Jogo do proprietário.
        $gameOne = factory(Game::class)->create(['user_id' => $this->administrator->id])->toArray();
        // Jogo de outro proprietário.
        $gameTwo = factory(Game::class)->create()->toArray();

        // CREATE
        $data = factory(Phase::class)->make()->toArray();
        // Tenta criar fase para o proprio jogo
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameOne['id'] . '/phase', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);
        // Tenta criar fase para o jogo alheio
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameTwo['id'] . '/phase', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);

        // INDEX
        // Tenta visualizar fases do proprio jogo
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameOne['id'] . '/phase');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                "*" => ["id", "name", "from", "to"]
            ]
        ]);
        // Tenta visualizar fases do jogo alheio
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameTwo['id'] . '/phase');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                "*" => ["id", "name", "from", "to"]
            ]
        ]);

        // EDIT
        // Fase do jogo do proprietário.
        $gameOnePhaseOne = factory(Phase::class)->create(['game_id' => $gameOne['id']])->toArray();
        // Fase do jogo de outro proprietário.
        $gameTwoPhaseOne = factory(Phase::class)->create(['game_id' => $gameTwo['id']])->toArray();

        // Tenta editar fase do proprio jogo
        $gameOnePhaseOne['name'] = 'Atualizado nome de fase';
        $response = $this->actingAs($this->administrator, 'api')->json('put', '/api/game/' . $gameOne['id'] . '/phase/' . $gameOnePhaseOne['id'], $gameOnePhaseOne);
        $response->assertStatus(200);
        $response->assertJson(['data' => [
            'name' => $gameOnePhaseOne['name']
        ]]);
        // Tenta editar fase do jogo alheio
        $gameTwoPhaseOne['name'] = 'Atualizado nome de fase';
        $response = $this->actingAs($this->administrator, 'api')->json('put', '/api/game/' . $gameTwo['id'] . '/phase/' . $gameTwoPhaseOne['id'], $gameTwoPhaseOne);
        $response->assertStatus(200);
        $response->assertJson(['data' => [
            'name' => $gameOnePhaseOne['name']
        ]]);

        // DELETE
        // Tenta remover fase do proprio jogo
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameOne['id'] . '/phase/' . $gameOnePhaseOne['id']);
        $response->assertStatus(204);
        // Tenta remover fase do jogo alheio
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameTwo['id'] . '/phase/' . $gameTwoPhaseOne['id']);
        $response->assertStatus(204);
    }

    /**
     * Teste de integração
     * Testa se um usuário design pode gerenciar recurso de fase de jogo na API.
     *
     * @return void
     */
    public function test_design_can_manage_game_phase_resource_in_api()
    {
        // Jogo do proprietário.
        $gameOne = factory(Game::class)->create(['user_id' => $this->design->id])->toArray();
        // Jogo de outro proprietário.
        $gameTwo = factory(Game::class)->create()->toArray();

        // CREATE
        $data = factory(Phase::class)->make()->toArray();
        // Tenta criar fase para o proprio jogo
        $response = $this->actingAs($this->design, 'api')->json('post', '/api/game/' . $gameOne['id'] . '/phase', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);
        // Tenta criar fase para o jogo alheio
        $response = $this->actingAs($this->design, 'api')->json('post', '/api/game/' . $gameTwo['id'] . '/phase', $data);
        $response->assertStatus(403);

        // INDEX
        // Tenta visualizar fases do proprio jogo
        $response = $this->actingAs($this->design, 'api')->json('get', '/api/game/' . $gameOne['id'] . '/phase');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                "*" => ["id", "name", "from", "to"]
            ]
        ]);
        // Tenta visualizar fases do jogo alheio
        $response = $this->actingAs($this->design, 'api')->json('get', '/api/game/' . $gameTwo['id'] . '/phase');
        $response->assertStatus(403);

        // EDIT
        // Fase do jogo do priprietário.
        $gameOnePhaseOne = factory(Phase::class)->create(['game_id' => $gameOne['id']])->toArray();
        // Fase do jogo de outro proprietário.
        $gameTwoPhaseOne = factory(Phase::class)->create(['game_id' => $gameTwo['id']])->toArray();

        // Tenta editar fase do proprio jogo
        $gameOnePhaseOne['name'] = 'Atualizado nome de fase';
        $response = $this->actingAs($this->design, 'api')->json('put', '/api/game/' . $gameOne['id'] . '/phase/' . $gameOnePhaseOne['id'], $gameOnePhaseOne);
        $response->assertStatus(200);
        $response->assertJson(['data' => [
            'name' => $gameOnePhaseOne['name']
        ]]);
        // Tenta editar fase do jogo alheio
        $gameTwoPhaseOne['name'] = 'Atualizado nome de fase';
        $response = $this->actingAs($this->design, 'api')->json('put', '/api/game/' . $gameTwo['id'] . '/phase/' . $gameTwoPhaseOne['id'], $gameTwoPhaseOne);
        $response->assertStatus(403);

        // DELETE
        // Tenta remover fase do proprio jogo
        $response = $this->actingAs($this->design, 'api')->json('delete', '/api/game/' . $gameOne['id'] . '/phase/' . $gameOnePhaseOne['id']);
        $response->assertStatus(204);
        // Tenta remover fase do jogo alheio
        $response = $this->actingAs($this->design, 'api')->json('delete', '/api/game/' . $gameTwo['id'] . '/phase/' . $gameTwoPhaseOne['id']);
        $response->assertStatus(403);
    }

    /**
     * Teste de integração
     * Testa se um usuário design pode gerenciar recurso de fase de jogo na API.
     *
     * @return void
     */
    public function test_player_can_manage_game_phase_resource_in_api()
    {
        // Jogo de outro proprietário.
        $gameOne = factory(Game::class)->create(['user_id' => $this->design->id])->toArray();
        // Jogo de outro proprietário.
        $gameTwo = factory(Game::class)->create()->toArray();

        // CREATE
        $data = factory(Phase::class)->make()->toArray();
        // Tenta criar fase para o proprio jogo
        $response = $this->actingAs($this->player, 'api')->json('post', '/api/game/' . $gameOne['id'] . '/phase', $data);
        $response->assertStatus(403);
        // Tenta criar fase para o jogo alheio
        $response = $this->actingAs($this->player, 'api')->json('post', '/api/game/' . $gameTwo['id'] . '/phase', $data);
        $response->assertStatus(403);

        // INDEX
        // Tenta visualizar fases do proprio jogo
        $response = $this->actingAs($this->player, 'api')->json('get', '/api/game/' . $gameOne['id'] . '/phase');
        $response->assertStatus(403);
        // Tenta visualizar fases do jogo alheio
        $response = $this->actingAs($this->player, 'api')->json('get', '/api/game/' . $gameTwo['id'] . '/phase');
        $response->assertStatus(403);

        // EDIT
        // Fase do jogo do priprietário.
        $gameOnePhaseOne = factory(Phase::class)->create(['game_id' => $gameOne['id']])->toArray();
        // Fase do jogo de outro proprietário.
        $gameTwoPhaseOne = factory(Phase::class)->create(['game_id' => $gameTwo['id']])->toArray();

        // Tenta editar fase do proprio jogo
        $gameOnePhaseOne['name'] = 'Atualizado nome de fase';
        $response = $this->actingAs($this->player, 'api')->json('put', '/api/game/' . $gameOne['id'] . '/phase/' . $gameOnePhaseOne['id'], $gameOnePhaseOne);
        $response->assertStatus(403);
        // Tenta editar fase do jogo alheio
        $gameTwoPhaseOne['name'] = 'Atualizado nome de fase';
        $response = $this->actingAs($this->player, 'api')->json('put', '/api/game/' . $gameTwo['id'] . '/phase/' . $gameTwoPhaseOne['id'], $gameTwoPhaseOne);
        $response->assertStatus(403);

        // DELETE
        // Tenta remover fase do proprio jogo
        $response = $this->actingAs($this->player, 'api')->json('delete', '/api/game/' . $gameOne['id'] . '/phase/' . $gameOnePhaseOne['id']);
        $response->assertStatus(403);
        // Tenta remover fase do jogo alheio
        $response = $this->actingAs($this->player, 'api')->json('delete', '/api/game/' . $gameTwo['id'] . '/phase/' . $gameTwoPhaseOne['id']);
        $response->assertStatus(403);
    }
}