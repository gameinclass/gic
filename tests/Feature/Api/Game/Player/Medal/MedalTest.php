<?php

namespace Tests\Feature\Api\Game\Player\Medal;

use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Medal;
use App\Models\Actor;
use App\Models\Player;

class MedalTest extends TestCase
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
    public function setUp()
    {
        parent::setUp();
        $this->setUpUserAdministrator();
        $this->setUpUserDesign();
        $this->setUpUserPlayer();
    }

    /**
     * Teste de integração
     * Testa se um usuário anônimo pode gerenciar recurso de medalha do jogador de um jogo na API.
     *
     * @return void
     */
    public function test_anonymous_can_manage_game_player_medal_resource_in_api()
    {
        // Cria um jogo no banco de dados.
        $game = factory(Game::class)->create();
        // Cria e adicionada 3 medalhas para o jogo.
        $medals = factory(Medal::class, 3)->create();
        $game->medals()->sync($medals);
        // Cria um jogador para o jogo.
        $player = factory(Player::class)->create(['game_id' => $game->id]);

        // CREATE
        $data['medal'] = $game->medals()->pluck('id')->toArray();
        $response = $this->json('post', '/api/game/' . $game->id . '/player/' . $player->id . '/medal', $data);
        $response->assertStatus(401);

        // DELETE
        $response = $this->json('delete', '/api/game/' . $game->id . '/player/' . $player->id . '/medal/' . $data['medal'][0]);
        $response->assertStatus(401);
    }

    /**
     * Teste de integração
     * Testa se um usuário administrador pode gerenciar recurso de medalha do jogador de um jogo na API.
     *
     * @return void
     */
    public function test_administrator_can_manage_game_player_medal_resource_in_api()
    {
        // Jogo do proprietário.
        $gameOne = factory(Game::class)->create(['user_id' => $this->administrator->id]);
        // Jogo de outro proprietário.
        $gameTwo = factory(Game::class)->create();

        // Cria 3 medalhas para cada um dos jogos.
        $gameOneMedals = factory(Medal::class, 3)->create();
        $gameOne->medals()->sync($gameOneMedals);
        $gameTwoMedals = factory(Medal::class, 3)->create();
        $gameTwo->medals()->sync($gameTwoMedals);

        // Cria um jogador para cada um dos jogos.
        $gameOnePlayerOne = factory(Player::class)->create(['game_id' => $gameOne->id]);
        $gameTwoPlayerOne = factory(Player::class)->create(['game_id' => $gameTwo->id]);

        // CREATE
        // Tenta adicionar medalhas aos jogadores do próprio jogo.
        $dataOne['medal'] = $gameOne->medals()->pluck('id')->toArray();
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/medal', $dataOne);
        $response->assertStatus(201);
        // Tenta adicionar medalhas aos jogadores de jogo alheio
        $dataTwo['medal'] = $gameTwo->medals()->pluck('id')->toArray();
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/medal', $dataTwo);
        $response->assertStatus(201);

        // INDEX
        // Tenta visualizar medalhas dos jogadores do próprio jogo
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/medal');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                ["id"]
            ]
        ]);
        // Tenta visualizar medalhas dos jogadores de jogo alheio.
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/medal');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                ["id"]
            ]
        ]);

        // DELETE
        // Tenta remover medalhas dos jogadores do proprio jogo.
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/medal/' . $dataOne['medal'][0]);
        $response->assertStatus(204);
        // Tenta remover medalhas dos jogadores de jogo alheio.
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/medal/' . $dataTwo['medal'][0]);
        $response->assertStatus(204);
    }

    /**
     * Teste de integração
     * Testa se um usuário design pode gerenciar recurso de medalha do jogador de um jogo na API.
     *
     * @return void
     */
    public function test_design_can_manage_game_player_medal_resource_in_api()
    {
        // Jogo do proprietário.
        $gameOne = factory(Game::class)->create(['user_id' => $this->design->id]);
        // Jogo de outro proprietário.
        $gameTwo = factory(Game::class)->create();

        // Cria 3 medalhas para cada um dos jogos.
        $gameOneMedals = factory(Medal::class, 3)->create();
        $gameOne->medals()->sync($gameOneMedals);
        $gameTwoMedals = factory(Medal::class, 3)->create();
        $gameTwo->medals()->sync($gameTwoMedals);

        // Cria um jogador para cada um dos jogos.
        $gameOnePlayerOne = factory(Player::class)->create(['game_id' => $gameOne->id]);
        $gameTwoPlayerOne = factory(Player::class)->create(['game_id' => $gameTwo->id]);

        // CREATE
        // Tenta adicionar medalhas aos jogadores do próprio jogo.
        $dataOne['medal'] = $gameOne->medals()->pluck('id')->toArray();
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/medal', $dataOne);
        $response->assertStatus(201);
        // Tenta adicionar medalhas aos jogadores de jogo alheio
        $dataTwo['medal'] = $gameTwo->medals()->pluck('id')->toArray();
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/medal', $dataTwo);
        $response->assertStatus(403);

        // INDEX
        // Tenta visualizar medalhas dos jogadores do próprio jogo
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/medal');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                ["id"]
            ]
        ]);
        // Tenta visualizar medalhas dos jogadores de jogo alheio.
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/medal');
        $response->assertStatus(403);

        // DELETE
        // Tenta remover medalhas dos jogadores do proprio jogo.
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/medal/' . $dataOne['medal'][0]);
        $response->assertStatus(204);
        // Tenta remover medalhas dos jogadores de jogo alheio.
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/medal/' . $dataTwo['medal'][0]);
        $response->assertStatus(403);
    }

    /**
     * Teste de integração
     * Testa se um usuário jogador pode gerenciar recurso de medalha do jogador de um jogo na API.
     *
     * @return void
     */
    public function test_player_can_manage_game_player_medal_resource_in_api()
    {
        // Jogo do proprietário.
        // Atenção! O usuário não pode criar jogo, esse teste esta sendo feito no policimento, e o factory
        // não permite o usuário criar jogo.
        $gameOne = factory(Game::class)->create(['user_id' => $this->design->id]);

        // Cria 3 medalhas para cada um dos jogos.
        $gameOneMedals = factory(Medal::class, 3)->create();
        $gameOne->medals()->sync($gameOneMedals);

        // Cria um jogador para cada um dos jogos.
        $gameOnePlayerOne = factory(Player::class)->create(['game_id' => $gameOne->id]);

        // CREATE
        // Tenta adicionar medalhas aos jogadores do jogo alheio.
        $dataOne['medal'] = $gameOne->medals()->pluck('id')->toArray();
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/medal', $dataOne);
        $response->assertStatus(403);

        // INDEX
        // Tenta visualizar medalhas dos jogadores do jogo alheio
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/medal');
        $response->assertStatus(403);

        // DELETE
        // Tenta remover medalhas dos jogadores do jogo alheio.
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/medal/' . $dataOne['medal'][0]);
        $response->assertStatus(403);
    }
}