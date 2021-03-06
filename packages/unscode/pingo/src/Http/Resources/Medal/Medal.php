<?php

namespace Unscode\Pingo\Http\Resources\Medal;

use Unscode\Pingo\Http\Resources\Game\Game;
use Unscode\Pingo\Http\Resources\Player\Player;
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
     * @param \Illuminate\Http\Request $request
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
            'games' => !$request->route('medal')
                ? $this->games->count()
                : Game::collection($this->games),

            // Verifica se está e requisitando um recurso específico ou se está listando todos. Para listagem
            // do recurso, somente é retornado a quantidade de itens relacionados.
            'players' => !$request->route('medal')
                ? $this->players->count()
                : Player::collection($this->players),
        ];
    }
}
