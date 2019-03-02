<?php

namespace Tests\Feature\Api\Game\Player\Score;

use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Score;
use App\Models\Actor;
use App\Models\Player;

class ScoreTest extends TestCase
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
     * Testa se um usuário anônimo pode gerenciar recurso de pontos do jogador de um jogo na API.
     *
     * @return void
     */
    public function test_anonymous_can_manage_game_player_score_resource_in_api()
    {
        // Cria um jogo no banco de dados.
        $game = factory(Game::class)->create();
        // Cria e adicionada 3 tipos de pontos para o jogo.
        $scores = factory(Score::class, 3)->create();
        $game->scores()->sync($scores);
        // Cria um jogador para o jogo.
        $player = factory(Player::class)->create(['game_id' => $game->id]);

        // CREATE
        $data = $game->scores()->first()->toArray();
        $response = $this->json('post', '/api/game/' . $game->id . '/player/' . $player->id . '/score', $data);
        $response->assertStatus(401);

        // INDEX
        $response = $this->json('get', '/api/game/' . $game->id . '/player/' . $player->id . '/score');
        $response->assertStatus(401);

        // EDIT
        $data['title'] = 'Titulo atualizado';
        $response = $this->json('put', '/api/game/' . $game->id . '/player/' . $player->id . '/score/' . $data['id'], $data);
        $response->assertStatus(401);

        // DELETE
        $response = $this->json('delete', '/api/game/' . $game->id . '/player/' . $player->id . '/score/' . $data['id']);
        $response->assertStatus(401);
    }

    /**
     * Teste de integração
     * Testa se um usuário administrador pode gerenciar recurso de pontos do jogador de um jogo na API.
     *
     * @return void
     */
    public function test_administrator_can_manage_game_player_score_resource_in_api()
    {
        // Jogo do proprietário.
        $gameOne = factory(Game::class)->create(['user_id' => $this->administrator->id]);
        // Jogo de outro proprietário.
        $gameTwo = factory(Game::class)->create();

        // Cria 3 tipos de pontos para cada um dos jogos.
        $gameOneScores = factory(Score::class, 3)->create();
        $gameOne->scores()->sync($gameOneScores);
        $gameTwoScores = factory(Score::class, 3)->create();
        $gameTwo->scores()->sync($gameTwoScores);

        // Cria um jogador para cada um dos jogos.
        $gameOnePlayerOne = factory(Player::class)->create(['game_id' => $gameOne->id]);
        $gameTwoPlayerOne = factory(Player::class)->create(['game_id' => $gameTwo->id]);

        // CREATE
        // Tenta adicionar pontos dos jogadores do próprio jogo.
        $dataOne = $gameOne->scores()->first()->toArray();
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/score', $dataOne);
        $response->assertStatus(201);
        // Tenta adicionar pontos dos jogadores de jogo alheio
        $dataTwo = $gameTwo->scores()->first()->toArray();
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/score', $dataTwo);
        $response->assertStatus(201);

        // INDEX
        // Tenta visualizar pontos dos jogadores do próprio jogo
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/score');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                ["id"]
            ]
        ]);
        // Tenta visualizar pontos dos jogadores de jogo alheio.
        $response = $this->actingAs($this->administrator, 'api')->json('get', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/score');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                ["id"]
            ]
        ]);

        // EDIT
        // Tenta editar pontos dos jogadores do próprio jogo.
        $dataOne['title'] = 'Título atualizado';
        $response = $this->actingAs($this->administrator, 'api')->json('put', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/score/' . $dataOne['id'], $dataOne);
        $response->assertStatus(200);
        // Tenta adicionar pontos dos jogadores de jogo alheio
        $dataTwo['title'] = 'Título atualizado';
        $response = $this->actingAs($this->administrator, 'api')->json('put', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/score/' . $dataTwo['id'], $dataTwo);
        $response->assertStatus(200);

        // DELETE
        // Tenta remover pontos dos jogadores do proprio jogo.
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/score/' . $dataOne['id']);
        $response->assertStatus(204);
        // Tenta remover pontos dos jogadores de jogo alheio.
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/score/' . $dataTwo['id']);
        $response->assertStatus(204);
    }

    /**
     * Teste de integração
     * Testa se um usuário design pode gerenciar recurso de pontos do jogador de um jogo na API.
     *
     * @return void
     */
    public function test_design_can_manage_game_player_score_resource_in_api()
    {
        // Jogo do proprietário.
        $gameOne = factory(Game::class)->create(['user_id' => $this->design->id]);
        // Jogo de outro proprietário.
        $gameTwo = factory(Game::class)->create();

        // Cria 3 tipos de pontos para cada um dos jogos.
        $gameOneScores = factory(Score::class, 3)->create();
        $gameOne->scores()->sync($gameOneScores);
        $gameTwoScores = factory(Score::class, 3)->create();
        $gameTwo->scores()->sync($gameTwoScores);

        // Cria um jogador para cada um dos jogos.
        $gameOnePlayerOne = factory(Player::class)->create(['game_id' => $gameOne->id]);
        $gameTwoPlayerOne = factory(Player::class)->create(['game_id' => $gameTwo->id]);

        // CREATE
        // Tenta adicionar pontos aos jogadores do próprio jogo.
        $dataOne = $gameOne->scores()->first()->toArray();
        $response = $this->actingAs($this->design, 'api')->json('post', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/score', $dataOne);
        $response->assertStatus(201);
        // Tenta adicionar pontos aos jogadores de jogo alheio
        $dataTwo = $gameTwo->scores()->first()->toArray();
        $response = $this->actingAs($this->design, 'api')->json('post', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/score', $dataTwo);
        $response->assertStatus(403);

        // INDEX
        // Tenta visualizar pontos dos jogadores do próprio jogo
        $response = $this->actingAs($this->design, 'api')->json('get', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/score');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "meta" => ["current_page", "from", "last_page", "path", "per_page", "to", "total"],
            "links" => ["first", "last", "prev", "next"], "data" => [
                ["id"]
            ]
        ]);
        // Tenta visualizar pontos dos jogadores de jogo alheio.
        $response = $this->actingAs($this->design, 'api')->json('get', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/score');
        $response->assertStatus(403);

        // EDIT
        // Tenta editar pontos dos jogadores do próprio jogo.
        $dataOne['title'] = 'Título atualizado';
        $response = $this->actingAs($this->design, 'api')->json('put', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/score/' . $dataOne['id'], $dataOne);
        $response->assertStatus(200);
        // Tenta adicionar pontos dos jogadores de jogo alheio
        $dataTwo['title'] = 'Título atualizado';
        $response = $this->actingAs($this->design, 'api')->json('put', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/score/' . $dataTwo['id'], $dataTwo);
        $response->assertStatus(403);

        // DELETE
        // Tenta remover pontos dos jogadores do proprio jogo.
        $response = $this->actingAs($this->design, 'api')->json('delete', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/score/' . $dataOne['id']);
        $response->assertStatus(204);
        // Tenta remover pontos dos jogadores de jogo alheio.
        $response = $this->actingAs($this->design, 'api')->json('delete', '/api/game/' . $gameTwo->id . '/player/' . $gameTwoPlayerOne->id . '/score/' . $dataTwo['id']);
        $response->assertStatus(403);
    }

    /**
     * Teste de integração
     * Testa se um usuário jogador pode gerenciar recurso de pontos do jogador de um jogo na API.
     *
     * @return void
     */
    public function test_player_can_manage_game_player_score_resource_in_api()
    {
        // Jogo do proprietário.
        // Atenção! O usuário não pode criar jogo, esse teste esta sendo feito no policimento, e o factory
        // não permite o usuário criar jogo.
        $gameOne = factory(Game::class)->create(['user_id' => $this->design->id]);

        // Cria 3 tipos de pontos para cada um dos jogos.
        $gameOneScores = factory(Score::class, 3)->create();
        $gameOne->scores()->sync($gameOneScores);

        // Cria um jogador para cada um dos jogos.
        $gameOnePlayerOne = factory(Player::class)->create(['game_id' => $gameOne->id]);

        // CREATE
        // Tenta adicionar pontos aos jogadores do jogo alheio.
        $dataOne = $gameOne->scores()->first()->toArray();
        $response = $this->actingAs($this->player, 'api')->json('post', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/score', $dataOne);
        $response->assertStatus(403);

        // INDEX
        // Tenta visualizar pontos dos jogadores do jogo olheio
        $response = $this->actingAs($this->player, 'api')->json('get', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/score');
        $response->assertStatus(403);

        // EDIT
        // Tenta editar pontos dos jogadores do jogo alheio.
        $dataOne['title'] = 'Título atualizado';
        $response = $this->actingAs($this->player, 'api')->json('put', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/score/' . $dataOne['id'], $dataOne);
        $response->assertStatus(403);

        // DELETE
        // Tenta remover pontos dos jogadores do jogo alheio.
        $response = $this->actingAs($this->player, 'api')->json('delete', '/api/game/' . $gameOne->id . '/player/' . $gameOnePlayerOne->id . '/score/' . $dataOne['id']);
        $response->assertStatus(403);
    }
}