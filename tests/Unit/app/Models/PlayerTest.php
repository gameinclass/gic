<?php
/**
 * Created by PhpStorm.
 * User: jorge
 * Date: 01/02/19
 * Time: 17:59
 */

namespace Tests\Unit\app\Models;

use Tests\TestCase;
use App\Models\Player;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class PlayerTest extends TestCase
{
    protected $fillable = [];

    protected $hidden = [];

    /**
     * Testa alguns atributos para configuração do model.
     *
     * @return void
     */
    public function test_attributes_configuration()
    {
        // Model
        $player = new Player();
        // Assertions
        $this->assertEquals($this->fillable, $player->getFillable());
        $this->assertEquals($this->hidden, $player->getHidden());
    }

    /**
     * Testa o relacionamento entre jogador e usuário.
     *
     * @return void
     */
    public function test_belongs_to_user_relation()
    {
        // Model
        $player = new Player();
        // Assertions
        $this->assertInstanceOf(BelongsTo::class, $player->user());
    }

    /**
     * Testa o relacionamento entre jogador e jogo.
     *
     * @return void
     */
    public function test_belongs_to_game_relation()
    {
        // Model
        $player = new Player();
        // Assertions
        $this->assertInstanceOf(BelongsTo::class, $player->game());
    }

    /**
     * Testa o relacionamento entre jogador e medalha.
     *
     * @return void
     */
    public function test_morph_to_many_medals_relation()
    {
        // Model
        $player = new Player();
        // Assertions
        $this->assertInstanceOf(MorphToMany::class, $player->medals());
    }

    /**
     * Testa o relacionamento entre jogador e ponto.
     *
     * @return void
     */
    public function test_morph_to_many_scores_relation()
    {
        // Model
        $player = new Player();
        // Assertions
        $this->assertInstanceOf(MorphToMany::class, $player->scores());
    }
}
