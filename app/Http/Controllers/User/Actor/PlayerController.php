<?php

namespace App\Http\Controllers\User\Actor;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserCollection;

class PlayerController extends Controller
{
    /**
     * Exibe uma liste de recurso de acordo com a pesquisa
     *
     * @param  string $search
     * @return \App\Http\Resources\User\UserCollection;
     */
    public function search($search)
    {
        // Verifica se a ação é autorizada ...
        $this->authorize('index', User::class);

        $players = User::where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")
            ->whereHas('actor', function ($query) {
                $query->where('is_player', true);
            })->take(5)->get();

        // Os valores retornados da pesquisa
        return new UserCollection($players);
    }
}
