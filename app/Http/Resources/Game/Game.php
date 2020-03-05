<?php

namespace App\Http\Resources\Game;

use App\Http\Resources\Game\Score\Score;
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
            'medals' => !$request->route('game') ? $this->medals->count() :
                // Quando a coleção está vazia, por padrão é retornado um array vazio, para evitar isso
                // foi adicionado a condição abaixo para transformar em objeto.
                ($this->medals->isEmpty() ? (object)[] : Medal::collection($this->medals)->keyBy->id),

            'players' => !$request->route('game') ? $this->players->count() :
                // Quando a coleção está vazia, por padrão é retornado um array vazio, para evitar isso
                // foi adicionado a condição abaixo para transformar em objeto.
                ($this->players->isEmpty() ? (object)[] : Player::collection($this->players)->keyBy->id),

            'scores' => !$request->route('game') ? $this->scores->count() :
                // Quando a coleção está vazia, por padrão é retornado um array vazio, para evitar isso
                // foi adicionado a condição abaixo para transformar em objeto.
                ($this->scores->isEmpty() ? (object)[] : Score::collection($this->scores)->keyBy->id),

            'phases' => !$request->route('game') ? $this->phases->count() :
                // Quando a coleção está vazia, por padrão é retornado um array vazio, para evitar isso
                // foi adicionado a condição abaixo para transformar em objeto.
                ($this->phases->isEmpty() ? (object)[] : Phase::collection($this->phases)->keyBy->id),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
