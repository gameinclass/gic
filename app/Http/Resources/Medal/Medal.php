<?php

namespace App\Http\Resources\Medal;

use App\Http\Resources\Game\Game;
use App\Http\Resources\Player\Player;
use Illuminate\Http\Resources\Json\JsonResource;

class Medal extends JsonResource
{
    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    public $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'url' => url('storage/' . $this->path),

            // Verifica se está e requisitando um recurso específico ou se está listando todos. Para listagem
            // do recurso, somente é retornado a quantidade de itens relacionados.
            'games' => !$request->route('medal') ? $this->games->count() :
                // Quando a coleção está vazia, por padrão é retornado um array vazio, para evitar isso
                // foi adicionado a condição abaixo para transformar em objeto.
                ($this->games->isEmpty() ? (object)[] : Game::collection($this->games)->keyBy->id),

            // Verifica se está e requisitando um recurso específico ou se está listando todos. Para listagem
            // do recurso, somente é retornado a quantidade de itens relacionados.
            'players' => !$request->route('medal') ? $this->players->count() :
                // Quando a coleção está vazia, por padrão é retornado um array vazio, para evitar isso
                // foi adicionado a condição abaixo para transformar em objeto.
                ($this->players->isEmpty() ? (object)[] : Player::collection($this->players)->keyBy->id),
        ];
    }
}
