<?php

namespace Tests\Unit\app\Models;

use Tests\TestCase;
use App\Models\Phase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhaseTest extends TestCase
{
    /**
     * Os atributos que são atribuíveis em massa
     *
     * @var array
     */
    protected $fillable = [
        'name', 'from', 'to',
    ];

    /**
     * Os atributos excluídos do formulário JSON do modelo.
     *
     * @var array
     */
    protected $hidden = [
        'game_id'
    ];

    /**
     * Os atributos que devem ser convertidos em tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'int',
        'user_id' => 'integer',
        'from' => 'datetime:Y-m-d H:i:s',
        'to' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * Testa alguns atributos para configuração do model.
     *
     * @return void
     */
    public function test_attributes_configuration()
    {
        // Model
        $phase = new Phase();
        // Assertions
        $this->assertEquals($this->fillable, $phase->getFillable());
        $this->assertEquals($this->hidden, $phase->getHidden());
        $this->assertEquals($this->casts, $phase->getCasts());
    }

    /**
     * Testa o relacionamento entre medalha e usuário.
     *
     * @return void
     */
    public function test_belongs_to_game_relation()
    {
        // Model
        $phase = new Phase();
        // Assertions
        $this->assertInstanceOf(BelongsTo::class, $phase->game());
    }
}