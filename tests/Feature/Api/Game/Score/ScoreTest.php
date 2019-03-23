<?php

namespace Tests\Feature\Api\Game\Score;

use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Score;
use App\Models\Actor;

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
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUserAdministrator();
        $this->setUpUserDesign();
        $this->setUpUserPlayer();
    }

    /**
     * Teste de integração
     * Testa se um usuário anônimo pode gerenciar recurso de ponto de jogo na API.
     *
     * @return void
     */
    public function test_anonymous_can_manage_game_score_resource_in_api()
    {
        // Cria um recurso de jogo no banco de dados.
        $gameOne = factory(Game::class)->create();
        // Cria uma ponto no banco de dados.
        $gameOneScoreOne = factory(Score::class)->create();

        // CREATE
        $data = $gameOneScoreOne->toArray();
        $response = $this->json('post', '/api/game/' . $gameOne->id . '/score', $data);
        $response->assertStatus(401);
        // INDEX
        $response = $this->json('get', '/api/game/' . $gameOne->id . '/score');
        $response->assertStatus(401);
        // EDIT
        $resource = $gameOneScoreOne->toArray();
        $resource['title'] = 'Teste';
        $response = $this->json('put', '/api/game/' . $gameOne->id . '/score/' . $resource['id'], $resource);
        $response->assertStatus(401);
        // DELETE
        // Vincula o ponto ao jogo para testar sua exclusão.
        $gameOne->medals()->attach($gameOneScoreOne);
        $response = $this->json('delete', '/api/game/' . $gameOne->id . '/score/' . $resource['id']);
        $response->assertStatus(401);
    }

    /**
     * Testa se um usuário administrador pode gerenciar pontos de um jogo na API.
     *
     * @return void
     */
    public function test_administrador_can_manage_game_score_resource_in_api()
    {
        // Jogo do proprietário.
        $gameOne = factory(Game::class)->create(['user_id' => $this->administrator->id]);
        // Jogo de outro proprietário.
        $gameTwo = factory(Game::class)->create();

        // Cria uma ponto no banco de dados.
        $gameOneScoreOne = factory(Score::class)->create();
        // Cria uma ponto no banco de dados.
        $gameTwoScoreOne = factory(Score::class)->create();

        // CREATE
        $data = $gameOneScoreOne->toArray();
        // Verifica se o usuário pode cria e vincular pontos ao próprio jogo.
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameOne->id . '/score', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);
        // Verifica se o usuário pode cria e vincular pontos ao jogo de outro proprietário.
        $response = $this->actingAs($this->administrator, 'api')->json('post', '/api/game/' . $gameTwo->id . '/score', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data" => ["id"]]);

        // INDEX

        // EDIT
        $gameOne->medals()->attach($gameOneScoreOne);
        $gameTwo->medals()->attach($gameTwoScoreOne);
        $gameOneScoreOne['title'] = 'Outro título';
        // Verifica se o usuário pode cria e vincular pontos ao próprio jogo.
        $response = $this->actingAs($this->administrator, 'api')->json('put', '/api/game/' . $gameOne->id . '/score/' . $gameOneScoreOne->id, $data);
        $response->assertStatus(200);
        $response->assertJson(['data' => [
            'name' => $gameOneScoreOne['name']
        ]]);
        // Verifica se o usuário pode cria e vincular pontos ao jogo de outro proprietário.
        $gameTwoScoreOne['title'] = 'Outro título';
        $response = $this->actingAs($this->administrator, 'api')->json('put', '/api/game/' . $gameTwo->id . '/score/' . $gameTwoScoreOne->id, $data);
        $response->assertStatus(200);
        $response->assertJson(['data' => [
            'name' => $gameTwoScoreOne['name']
        ]]);

        // DELETE
        // Tenta remover fase do proprio jogo
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameOne->id . '/score/' . $gameOneScoreOne->id);
        $response->assertStatus(204);
        // Tenta remover fase do jogo de outro proprietario
        $response = $this->actingAs($this->administrator, 'api')->json('delete', '/api/game/' . $gameTwo->id . '/score/' . $gameTwoScoreOne->id);
        $response->assertStatus(204);
    }
}