<?php

namespace App\Http\Controllers\Game\Player;

use App\Models\Game;
use App\Models\Player;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Player\PlayerStoreRequest;
use App\Http\Resources\Player\Player as PlayerResource;
use App\Models\User;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int $gameId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($gameId)
    {
        $game = Game::findOrFail($gameId);
        // Verifica se a ação é autorizada ...
        $this->authorize('index', [Player::class, $game]);
        // Retorna a coleção de recursos.
        return PlayerResource::collection(Player::where('game_id', $game->id)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Game\Player\PlayerStoreRequest $request
     * @param  int $gameId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PlayerStoreRequest $request, $gameId)
    {
        $game = Game::findOrFail($gameId);
        // Verifica se a ação é autorizada ...
        $this->authorize('store', [Player::class, $game]);
        // Procura pelo usuário no banco de dados
        $user = User::find($request->input('id'));
        if (!$user) {
            return response()->json([
                'data' => $request->all(),
                'message' => 'Não foi possível criar o recurso'
            ])->setStatusCode(400);
        }
        // Adiciona o identificador de relacionamento com o usuário.
        $request->merge(['user_id' => $user->id]);
        // Adiciona as informações da requisição para o objeto jogador.
        $player = new Player($request->all());
        // Para evitar duplicidade, verifica se a medalha já foi adicionada.
        // A verificação se dá usando a chave estrangeira para usuário, o mesmo usuário não pode mais de uma vez
        // no jogo.
        if ($game->players->contains('user_id', $user->id)) {
            return (new PlayerResource($player))
                ->additional(['errors' => 'O jogador já foi adicionado.'])
                ->response()
                ->setStatusCode(422);
        }
        // Salva o recurso no banco de dados
        if ($game->players()->save($player)) {
            return (new PlayerResource($player))
                ->response()
                ->setStatusCode(201);
        }
        return (new PlayerResource($player))
            ->response()
            ->setStatusCode(422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $gameId
     * @param  int $playerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($gameId, $playerId)
    {
        $game = Game::findOrFail($gameId);
        $player = Player::findOrFail($playerId);
        // Verifica se a ação é autorizada ...
        $this->authorize('destroy', $game, $player);

        // Remove o recurso do banco de dados
        if ($player->delete()) {
            return response()->noContent();
        }
        return (new PlayerResource($player))
            ->response()
            ->setStatusCode(422);
    }
}
