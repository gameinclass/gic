<?php

namespace App\Http\Controllers\Game\Player\Medal;

use App\Models\Game;
use App\Models\Player;
use App\Http\Controllers\Controller;
use App\Http\Resources\Medal\Medal as MedalResource;
use App\Http\Requests\Game\Player\Medal\MedalStoreRequest;

class MedalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int $gameId
     * @param  int $playerId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($gameId, $playerId)
    {
        $game = Game::findOrFail($gameId);
        $player = Player::findOrFail($playerId);
        // Verifica se a ação é autorizada ...
        // Atenção! As regras (policy) são as mesmas que adicionar jogador ao jogo.
        $this->authorize('index', [Player::class, $game]);
        // Retorna a coleção de recursos.
        return MedalResource::collection($player->medals()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int $gameId
     * @param  int $playerId
     * @param  \App\Http\Requests\Game\Player\Medal\MedalStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MedalStoreRequest $request, $gameId, $playerId)
    {
        $game = Game::findOrFail($gameId);
        $player = Player::findOrFail($playerId);
        // Verifica se a ação é autorizada ...
        // Atenção! As regras (policy) são as mesmas que adicionar jogador ao jogo.
        $this->authorize('store', [Player::class, $game]);

        // Procura pela medalha entre as medalhas do jogo.
        $medal = $game->medals()->find($request->input('medal_id'));
        // Recupera a medalha atribuída ao jogador.
        if ($medal) {
            // Faz a associação da medalha ao jogador.
            $player->medals()->attach($request->input('medal_id'));
            return (new MedalResource($medal))
                ->response()
                ->setStatusCode(201);
        }
        return response()->json($request->all())
            ->setStatusCode(422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $gameId
     * @param  int $playerId
     * @param  int $gameId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($gameId, $playerId, $medalId)
    {
        $game = Game::findOrFail($gameId);
        $player = Player::findOrFail($playerId);
        // Verifica se a ação é autorizada ...
        $this->authorize('destroy', $game, $player);

        // Remove a associação da medalha com o jogador.
        if ($player->medals()->detach([$medalId])) {
            return response()->noContent();
        }
        return MedalResource::collection($player->medals()->paginate())
            ->response()
            ->setStatusCode(422);
    }
}
