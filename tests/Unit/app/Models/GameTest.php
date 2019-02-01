<?php

namespace Tests\Unit\app\Models;

use Tests\TestCase;
use App\Models\Game;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameTest extends TestCase
{
    protected $fillable = [
        'title', 'description'
    ];

    protected $hidden = [
        'user_id'
    ];

    /**
     * Testa alguns atributos para configuração do model.
     *
     * @return void
     */
    public function test_attributes_configuration()
    {
        // Model
        $game = new Game();
        // Assertions
        $this->assertEquals($this->fillable, $game->getFillable());
        $this->assertEquals($this->hidden, $game->getHidden());
    }

    /**
     * Testa o relacionamento entre jogo e usuário.
     *
     * @return void
     */
    public function test_belongs_to_user_relation()
    {
        // Model
        $game = new Game();
        // Assertions
        $this->assertInstanceOf(BelongsTo::class, $game->user());
    }

    /**
     * Testa o relacionamento entre jogo e grupo
     */
    public function test_has_many_groups_relation()
    {
        // Model
        $game = new Game();
        // Assertions
        $this->assertInstanceOf(HasMany::class, $game->groups());
    }
}
