<?php

namespace App\Http\Resources\Actor;

use Illuminate\Http\Resources\Json\JsonResource;

class Actor extends JsonResource
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
            'is_administrator' => $this->is_administrator,
            'is_design' => $this->is_design,
            'is_player' => $this->is_player,
        ];
    }
}
