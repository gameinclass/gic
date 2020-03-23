<?php

namespace Unscode\Pingo\Http\Resources\Game\Phase;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PhaseCollection extends ResourceCollection
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
