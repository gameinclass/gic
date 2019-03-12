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
            // Relacionamentos
            'games' => $request->route('medal', false) ? Game::collection($this->games)->keyBy->id : $this->games->count(),
            'players' => $request->route('medal', false) ? Player::collection($this->players)->keyBy->id : $this->players->count(),
            // Fim dos relacionamentos
        ];
    }
}
