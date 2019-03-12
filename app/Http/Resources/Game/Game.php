<?php

namespace App\Http\Resources\Game;

use App\Http\Resources\Medal\Medal;
use App\Http\Resources\Player\Player;
use App\Http\Resources\Game\Phase\Phase;
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
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            // Relacionamentos
            'medals' => $request->route('game', false) ? Medal::collection($this->medals)->keyBy->id : $this->medals->count(),
            'players' => $request->route('game', false) ? Player::collection($this->players)->keyBy->id : $this->players->count(),
            'phases' => $request->route('game', false) ? Phase::collection($this->phases)->keyBy->id : $this->phases->count(),
            // Fim dos relacionamentos
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
