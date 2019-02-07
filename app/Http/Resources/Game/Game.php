<?php

namespace App\Http\Resources\Game;

use App\Http\Resources\Group\Group as Group;
use Illuminate\Http\Resources\Json\JsonResource;

class Game extends JsonResource
{
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
            'groups' => Group::collection($this->groups),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
