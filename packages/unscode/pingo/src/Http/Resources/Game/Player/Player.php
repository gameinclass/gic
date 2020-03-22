<?php

namespace App\Http\Resources\Game\Player;

use App\Http\Resources\Game\Player\Score\Score;
use App\Http\Resources\Game\Player\Medal\Medal;
use Illuminate\Http\Resources\Json\JsonResource;

class Player extends JsonResource
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
            'name' => $this->user->name,
            'medals' => Medal::collection($this->medals),
            'scores' => Score::collection($this->scores),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
