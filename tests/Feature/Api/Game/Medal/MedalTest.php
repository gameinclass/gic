<?php

namespace Tests\Feature\Api\Game\Medal;

use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Actor;
use App\Models\Medal;
use Illuminate\Http\UploadedFile;

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
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUserAdministrator();
        $this->setUpUserDesign();
        $this->setUpUserPlayer();
    }

    /**
     * Testa se um usuário anônimo pode gerenciar medalhas de um jogo na API.
     *
     * @return void
     */
    public function test_anonymous_can_manage_game_medal_resource_in_api()
    {
        // Cria um jogo no banco de dados.
        $game = factory(Game::class)->create();
        // Cria e adicionada 3 tipos de pontos para o jogo.
        $medal = factory(Medal::class)->create();

        // CREATE
        $data = $medal->toArray();
        $data['medal_id'] = $data['id'];
        $response = $this->json('post', '/api/game/'. $game->id . '/medal', $data);
        $response->assertStatus(401);
        // INDEX
        $response = $this->json('get', '/api/game/'. $game->id . '/medal');
        $response->assertStatus(401);
        // DELETE
        $game->medals()->attach($medal);
        $response = $this->json('delete', '/api/game/'. $game->id . '/medal/' . $medal->id);
        $response->assertStatus(401);
    }
}