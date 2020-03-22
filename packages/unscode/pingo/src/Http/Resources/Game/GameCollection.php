<?php

namespace App\Http\Resources\Game;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GameCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            // Quando a coleção está vazia, por padrão é retornado um array vazio, para evitar isso
            // foi adicionado a condição abaixo para transformar em objeto,
            'data' => $this->collection,
        ];
    }
}
