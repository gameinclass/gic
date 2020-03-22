<?php

namespace App\Http\Controllers\Game\Score;

use App\Http\Requests\Game\Score\ScoreStoreRequest;
use App\Http\Requests\Game\Score\ScoreUpdateRequest;
use App\Models\Game;
use App\Models\Score;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Game\Score\Score as ScoreResource;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $gameId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($gameId)
    {
        $game = Game::findOrFail($gameId);
        // Verifica se a ação é autorizada ...
        // $this->authorize('index', [Score::class, $game]);
        // Retorna a coleção de recursos do jogo.
        return ScoreResource::collection($game->scores()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Game\Score\ScoreStoreRequest $request
     * @param int $gameId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ScoreStoreRequest $request, $gameId)
    {
        $game = Game::findOrFail($gameId);
        // Verifica se a ação é autorizada ...
        // $this->authorize('store', [Score::class, $game]);

        $score = new Score($request->all());
        // Salva o recurso no banco de dados
        if ($game->scores()->save($score)) {
            return (new ScoreResource($score))
                ->response()
                ->setStatusCode(201);
        }
        return (new ScoreResource($score))
            ->response()
            ->setStatusCode(422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $gameId
     * @param int $scoreId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ScoreUpdateRequest $request, $gameId, $scoreId)
    {
        $game = Game::findOrFail($gameId);
        $score = Score::findOrFail($scoreId);
        // Verifica se a ação é autorizada ...
        // $this->authorize('destroy', $game, $score);

        // Atualiza o recurso no banco de dados
        $score->update($request->all());
        return (new ScoreResource($score))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $gameId
     * @param int $scoreId
     * @return \Illuminate\Http\Response
     */
    public function destroy($gameId, $scoreId)
    {
        $game = Game::findOrFail($gameId);
        $score = Score::findOrFail($scoreId);
        // Verifica se a ação é autorizada ...
        // $this->authorize('destroy', $game, $score);

        // Remove o recurso do banco de dados
        if ($score->delete()) {
            return response()->noContent();
        }
        return (new ScoreResource($score))
            ->response()
            ->setStatusCode(422);
    }
}
