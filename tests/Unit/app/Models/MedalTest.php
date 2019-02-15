<?php

namespace Tests\Unit\app\Models;

use Tests\TestCase;
use App\Models\Medal;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class MedalTest extends TestCase
{
    protected $fillable = [
        'title', 'description', 'path',
    ];

    protected $hidden = [

    ];

    /**
     * Testa alguns atributos para configuração do model.
     *
     * @return void
     */
    public function test_attributes_configuration()
    {
        // Model
        $medal = new Medal();
        // Assertions
        $this->assertEquals($this->fillable, $medal->getFillable());
        $this->assertEquals($this->hidden, $medal->getHidden());
    }

    /**
     * Testa o relacionamento entre medalha e usuário.
     *
     * @return void
     */
    public function test_belongs_to_user_relation()
    {
        // Model
        $medal = new Medal();
        // Assertions
        $this->assertInstanceOf(BelongsTo::class, $medal->user());
    }

    /**
     * Testa o relacionamento entre medalha e jogador.
     */
    public function test_morph_to_many_games_relation()
    {
        // Model
        $medal = new Medal();
        // Assertions
        $this->assertInstanceOf(MorphToMany::class, $medal->games());
    }

    /**
     * Testa o relacionamento entre medalha e jogador.
     */
    public function test_morph_to_many_players_relation()
    {
        // Model
        $medal = new Medal();
        // Assertions
        $this->assertInstanceOf(MorphToMany::class, $medal->players());
    }

    /**
     * Testa o relacionamento entre medalha e grupo.
     */
    public function test_morph_to_many_groups_relation()
    {
        // Model
        $medal = new Medal();
        // Assertions
        $this->assertInstanceOf(MorphToMany::class, $medal->groups());
    }
}
