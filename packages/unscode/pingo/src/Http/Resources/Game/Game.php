<?php

namespace Unscode\Pingo\Http\Resources\Game;

use Unscode\Pingo\Http\Resources\Medal\Medal;
use Unscode\Pingo\Http\Resources\Player\Player;
use Unscode\Pingo\Http\Resources\Game\Phase\Phase;
use Unscode\Pingo\Http\Resources\Game\Score\Score;
use Illuminate\Http\Resources\Json\JsonResource;

class Game extends JsonResource
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

            // Verifica se está e requisitando um recurso específico ou se está listando todos. Para listagem
            // do recurso, somente é retornado a quantidade de itens relacionados.
            'medals' => !$request->route('game')
                ? $this->medals->count()
                : Medal::collection($this->medals),

            'players' => !$request->route('game')
                ? $this->players->count()
                : Player::collection($this->players),

            'scores' => !$request->route('game')
                ? $this->scores->count()
                : Score::collection($this->scores),

            'phases' => !$request->route('game')
                ? $this->phases->count()
                : Phase::collection($this->phases),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
