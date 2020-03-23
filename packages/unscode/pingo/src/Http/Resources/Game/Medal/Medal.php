<?php

namespace Unscode\Pingo\Http\Resources\Game\Medal;

use Illuminate\Http\Resources\Json\JsonResource;

class Medal extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
