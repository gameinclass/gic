<?php

namespace Tests\Feature\Api\Game\Player;

use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Actor;
use App\Models\Player;

class PlayerTest extends TestCase
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
     * Testa se um usuário anônimo pode gerenciar recurso de jogador do jogo na API.
     *
     * @return void
     */
    public function test_anonymous_can_manage_game_player_resource_in_api()
    {
        // Cria um recurso de jogo no banco de dados.
        $game = factory(Game::class)->create()->toArray();

        // CREATE
        $data = factory(Player::class)->make()->toArray();
        $response = $this->json('post', '/api/game/' . $game['id'] . '/player', $data);
        $response->assertStatus(401);
        // INDEX
        $response = $this->json('get', '/api/game/' . $game['id'] . '/player');
        $response->assertStatus(401);
        // EDIT
        $resource = factory(Player::class)->create(['game_id' => $game['id']])->toArray();
        $resource['name'] = 'Teste de atualização do título';
        $response = $this->json('put', '/api/game/' . $game['id'] . '/player/' . $resource['id'], $resource);
        $response->assertStatus(401);
        // DELETE
        $response = $this->json('delete', '/api/game/' . $game['id'] . '/player/' . $resource['id']);
        $response->assertStatus(401);
    }

    /**
     * Teste de integração
     * Testa se um usuário administrador pode gerenciar recurso de jogador do jogo na API.
     *
     * @return void
     */
    public function test_administrator_can_manage_game_player_resource_in_api()
    {
        // Jogo do proprietário.
        $gameOne = factory(Game::class)->create(['user_id' => $this->administrator->id])->toArray();
        // Usuário jogador do jogo do proprietário.
        $gameOnePlayerOne = factory(Player::class)->create(['game_id' => $gameOne['id']])->toArray();
        // Jogo de outro proprietário.
        $gameTwo = factory(Game::class)->create()->toArray();
        // Usuário jogador do jogo de outro proprietário.
        $gameTwoPlayerOne = factory(Player::class)->create(['game_id' => $gameTwo['id']])->toArray();

        // CREATE
        $data = $gameOnePlayerOne;
        // Tenta adicionar um jogador para o proprio jogo
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameOne['id'] . '/player', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);
        // Tenta adicionar um jogador para o jogo alheio
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameTwo['id'] . '/player', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);

        // INDEX
        // Tenta visualizar fases do proprio jogo
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameOne['id'] . '/player');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                ["id"]
            ]
        ]);
        // Tenta visualizar fases do jogo alheio
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameTwo['id'] . '/player');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                ["id"]
            ]
        ]);

        // DELETE
        // Tenta remover um usuário jogador do proprio jogo
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameOne['id'] . '/player/' . $gameOnePlayerOne['id']);
        $response->assertStatus(204);
        // Tenta remover um usuário jogador do jogo alheio
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameTwo['id'] . '/player/' . $gameTwoPlayerOne['id']);
        $response->assertStatus(204);
    }

    /**
     * Teste de integração
     * Testa se um usuário design pode gerenciar recurso de jogador do jogo na API.
     *
     * @return void
     */
    public function test_design_can_manage_game_player_resource_in_api()
    {
        // Jogo do proprietário.
        $gameOne = factory(Game::class)->create(['user_id' => $this->design->id])->toArray();
        // Usuário jogador do jogo do proprietário.
        $gameOnePlayerOne = factory(Player::class)->create(['game_id' => $gameOne['id']])->toArray();
        // Jogo de outro proprietário.
        $gameTwo = factory(Game::class)->create()->toArray();
        // Usuário jogador do jogo de outro proprietário.
        $gameTwoPlayerOne = factory(Player::class)->create(['game_id' => $gameTwo['id']])->toArray();

        // CREATE
        $data = $gameOnePlayerOne;
        // Tenta adicionar um jogador para o proprio jogo
        $response = $this->actingAs($this->design, 'api')->json('post', '/api/game/' . $gameOne['id'] . '/player', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);
        // Tenta adicionar um jogador para o jogo alheio
        $response = $this->actingAs($this->design, 'api')->json('post', '/api/game/' . $gameTwo['id'] . '/player', $data);
        $response->assertStatus(403);

        // INDEX
        // Tenta visualizar fases do proprio jogo
        $response = $this->actingAs($this->design, 'api')->json('get', '/api/game/' . $gameOne['id'] . '/player');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                ["id"]
            ]
        ]);
        // Tenta visualizar fases do jogo alheio
        $response = $this->actingAs($this->design, 'api')->json('get', '/api/game/' . $gameTwo['id'] . '/player');
        $response->assertStatus(403);

        // DELETE
        // Tenta remover um usuário jogador do proprio jogo
        $response = $this->actingAs($this->design, 'api')->json('delete', '/api/game/' . $gameOne['id'] . '/player/' . $gameOnePlayerOne['id']);
        $response->assertStatus(204);
        // Tenta remover um usuário jogador do jogo alheio
        $response = $this->actingAs($this->design, 'api')->json('delete', '/api/game/' . $gameTwo['id'] . '/player/' . $gameTwoPlayerOne['id']);
        $response->assertStatus(403);
    }

    /**
     * Teste de integração
     * Testa se um usuário design pode gerenciar recurso de jogador do jogo na API.
     *
     * @return void
     */
    public function test_player_can_manage_game_player_resource_in_api()
    {
        // Jogo do proprietário.
        // Atenção! O usuário não pode criar jogo, esse teste esta sendo feito no policimento, e o factory
        // não permite o usuário criar jogo.
        $gameOne = factory(Game::class)->create(['user_id' => $this->design->id])->toArray();
        // Usuário jogador do jogo do proprietário.
        $gameOnePlayerOne = factory(Player::class)->create(['game_id' => $gameOne['id']])->toArray();

        // CREATE
        $data = $gameOnePlayerOne;
        // Tenta adicionar um jogador para o jogo alheio
        $response = $this->actingAs($this->player, 'api')->json('post', '/api/game/' . $gameOne['id'] . '/player', $data);
        $response->assertStatus(403);

        // INDEX
        // Tenta visualizar fases do jogo alheio
        $response = $this->actingAs($this->player, 'api')->json('get', '/api/game/' . $gameOne['id'] . '/player');
        $response->assertStatus(403);

        // DELETE
        // Tenta remover um usuário jogador do jogo alheio
        $response = $this->actingAs($this->player, 'api')->json('delete', '/api/game/' . $gameOne['id'] . '/player/' . $gameOnePlayerOne['id']);
        $response->assertStatus(403);
    }
}