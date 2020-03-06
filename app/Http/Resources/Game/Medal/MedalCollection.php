<?php

namespace App\Http\Resources\Game\Medal;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MedalCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
