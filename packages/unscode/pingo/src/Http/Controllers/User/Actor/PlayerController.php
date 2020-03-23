<?php

namespace Unscode\Pingo\Http\Controllers\User\Actor;

use Unscode\Pingo\Models\User;
use Unscode\Pingo\Http\Controllers\Controller;
use Unscode\Pingo\Http\Resources\User\UserCollection;

class PlayerController extends Controller
{
    /**
     * Exibe uma lista de recurso de acordo com a pesquisa
     *
     * @param  string $search
     * @return UserCollection
     */
    public function search($search)
    {
        // Verifica se a ação é autorizada ...
        $this->authorize('index', User::class);

        // Pesquisa pelo usuários que são jogadores.
        $players = User::where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->whereHas('actor', function ($query) {
                $query->where('is_player', true);
            })
            ->take(5)
            ->get();

        return new UserCollection($players);
    }
}
