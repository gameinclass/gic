<?php

namespace Unscode\Pingo\Http\Controllers\Game\Player\Score;

use App\Models\Game;
use App\Models\Score;
use App\Models\Player;
use App\Http\Controllers\Controller;
use App\Http\Resources\Game\Score\Score as ScoreResource;
use App\Http\Requests\Game\Player\Score\ScoreStoreRequest;
use App\Http\Requests\Game\Player\Score\ScoreUpdateRequest;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $gameId
     * @param int $playerId
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
        return ScoreResource::collection($player->scores()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Game\Player\Score\ScoreStoreRequest $request
     * @param int $gameId
     * @param int $playerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ScoreStoreRequest $request, $gameId, $playerId)
    {
        $game = Game::findOrFail($gameId);
        //$player = Player::findOrFail($playerId);
        // Verifica se a ação é autorizada ...
        // Atenção! As regras (policy) são as mesmas que adicionar jogador ao jogo.
        $this->authorize('store', [Player::class, $game]);

        // Procura pelo jogador nos jogadores do jogo.
        $player = $game->players()->find($playerId);

        // Procura pelo ponto nos pontos do jogo.
        $score = $game->scores()->find($request->input('id'));

        if ($player && $score) {
            // Faz a associação do ponto ao jogador.
            $player->scores()->attach($score);
            return (new ScoreResource($score))
                ->response()
                ->setStatusCode(201);
        }
        return response()->json($request->all())
            ->setStatusCode(422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Game\Player\Score\ScoreUpdateRequest $request
     * @param int $gameId
     * @param int $playerId
     * @param int $scoreId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ScoreUpdateRequest $request, $gameId, $playerId, $scoreId)
    {
        $game = Game::findOrFail($gameId);
        $player = Player::findOrFail($playerId);
        // Verifica se a ação é autorizada ...
        // Atenção! As regras (policy) são as mesmas que adicionar jogador ao jogo.
        $this->authorize('update', $game, $player);

        $score = Score::findOrFail($scoreId);
        // Atualiza os dados do recurso
        $score->update($request->all());
        return (new ScoreResource($score))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $gameId
     * @param int $playerId
     * @param int $scoreId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($gameId, $playerId, $scoreId)
    {
        $game = Game::findOrFail($gameId);
        $player = Player::findOrFail($playerId);
        // Verifica se a ação é autorizada ...
        // Atenção! As regras (policy) são as mesmas que adicionar jogador ao jogo.
        $this->authorize('destroy', $game, $player);
        // Remove a associação do ponto com o jogador.
        if ($player->scores()->detach([$scoreId])) {
            return response()->noContent();
        }
        return response()->json([])
            ->setStatusCode(422);
    }
}
