<?php

namespace App\Http\Controllers\Game;

use App\Models\Game;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\GameStoreRequest;
use App\Http\Requests\Game\GameUpdateRequest;
use App\Http\Resources\Game\Game as GameResource;
use App\Http\Resources\Game\GameCollection;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\Game\GameCollection
     */
    public function index()
    {
        // Verifica se a ação é autorizada ...
        $this->authorize('index', Game::class);
        // Se o usuário for administrador vê todos os registros.
        if (Auth::user()->actor && Auth::user()->actor->is_administrator) {
            return new GameCollection(Game::orderBy('created_at', 'desc')
                ->paginate());
        } else { // Senão, vê somente os seus registros.
            return new GameCollection(Game::where('user_id', Auth::user()->id)
                ->orderBy('created_at', 'desc')
                ->paginate());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  GameStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(GameStoreRequest $request)
    {
        // Verifica se a ação é autorizada ...
        $this->authorize('store', Game::class);

        $game = new Game($request->all());
        // Adiciona o usuário da requisição.
        $game->user_id = $request->user()->id;
        // Salva o recurso no banco de dados
        if ($game->save()) {
            return (new GameResource($game))
                ->response()
                ->setStatusCode(201);
        }
        return (new GameResource($game))
            ->response()
            ->setStatusCode(422);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $gameId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($gameId)
    {
        $game = Game::findOrFail($gameId);
        // Verifica se a ação é autorizada ...
        $this->authorize('update', $game);
        return (new GameResource($game))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  GameUpdateRequest $request
     * @param  int $gameId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(GameUpdateRequest $request, $gameId)
    {
        $game = Game::findOrFail($gameId);
        // Verifica se a ação é autorizada ...
        $this->authorize('update', $game);

        // Adiciona o usuário da requisição.
        $game->user_id = $request->user()->id;
        // Atualiza o recurso no banco de dados
        $game->update($request->all());
        return (new GameResource($game))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $gameId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($gameId)
    {
        $game = Game::findOrFail($gameId);
        // Verifica se a ação é autorizada ...
        $this->authorize('destroy', $game);

        // Evita que o jogo seja removido se houver recursos vinculados.
        $errors = array();
        // Verifica se existe(m) medalha(s) vinculada(s) a este jogo.
        if (!$game->medals->isEmpty()) {
            $errors['medals'][0] = 'Ops! Há medalhas(s) vinculado(s) a este jogo.';
        }
        // Verifica se existe(m) ponto(s) vinculado(s) a este jogo.
        if (!$game->scores->isEmpty()) {
            $errors['scores'][0] = 'Ops! Há pontos(s) vinculado(s) a este jogo.';
        }
        // Verifica se existe(m) fases(s) vinculada(s) a este jogo.
        if (!$game->phases->isEmpty()) {
            $errors['phases'][0] = 'Ops! Há fases(s) vinculada(s) a este jogo.';
        }
        // Verifica se existe(m) jogadores(s) vinculado(s) a este jogo.
        if (!$game->players->isEmpty()) {
            $errors['players'][0] = 'Ops! Há jogadores(s) vinculado(s) a este jogo.';
        }

        // Se houver recursos vinculados, envia resposta de dados não processadas, com informações sobre o erro.
        if ($errors) {
            return (new GameResource($game))
                ->additional([
                    "message" => "Não foi possível remover o jogo.",
                    "errors" => $errors
                ])
                ->response()
                ->setStatusCode(422);
        }

        // Remove o recurso do banco de dados
        if ($game->delete()) {
            return response()->noContent();
        }
        return (new GameResource($game))
            ->additional([
                "message" => "Não foi possível remover o jogo.",
            ])
            ->response()
            ->setStatusCode(422);
    }
}
