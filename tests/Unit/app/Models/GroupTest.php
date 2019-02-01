<?php

namespace Tests\Unit\app\Models;

use Tests\TestCase;
use App\Models\Group;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class GroupTest extends TestCase
{
    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'game_id'
    ];

    /**
     * Testa alguns atributos para configuração do model.
     *
     * @return void
     */
    public function test_attributes_configuration()
    {
        // Model
        $group = new Group();
        // Assertions
        $this->assertEquals($this->fillable, $group->getFillable());
        $this->assertEquals($this->hidden, $group->getHidden());
    }

    /**
     * Testa o relacionamento entre grupo e jogo.
     *
     * @return void
     */
    public function test_belongs_to_game_relation()
    {
        // Model
        $group = new Group();
        // Assertions
        $this->assertInstanceOf(BelongsTo::class, $group->game());
    }

    /**
     * Testa o relacionamento entre grupo e medalha
     */
    public function test_morph_to_many_medals_relation()
    {
        // Model
        $group = new Group();
        // Assertions
        $this->assertInstanceOf(MorphToMany::class, $group->medals());
    }

    /**
     * Testa o relacionamento entre grupo e ponto
     */
    public function test_morph_to_many_scores_relation()
    {
        // Model
        $group = new Group();
        // Assertions
        $this->assertInstanceOf(MorphToMany::class, $group->scores());
    }
}
