<?php

namespace Unscode\Pingo\Http\Controllers\Medal;

use Unscode\Pingo\Models\Medal;
use Unscode\Pingo\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Medal\MedalCollection;
use App\Http\Requests\Medal\MedalStoreRequest;
use App\Http\Requests\Medal\MedalUpdateRequest;
use App\Http\Resources\Medal\Medal as MedalResource;

class MedalController extends Controller
{
    /**
     * Exibe uma liste de recurso de acordo com a pesquisa
     *
     * @param string $search
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection;
     */
    public function search($search)
    {
        // Verifica se a ação é autorizada ...
        $this->authorize('index', Medal::class);

        // Os valores retornados da pesquisa
        return MedalResource::collection(Medal::where('title', 'like', "%{$search}%")->take(3)->get());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\Medal\MedalCollection;
     */
    public function index()
    {
        // Verifica se a ação é autorizada ...
        $this->authorize('index', Medal::class);

        // Se o usuário for administrador vê todos os registros.
        if (Auth::user()->actor && Auth::user()->actor->is_administrator) {
            return new MedalCollection(
                Medal::orderBy('id', 'desc')->paginate()
            );
        } else {
            return new MedalCollection(
                Medal::where('user_id', Auth::user()->id)
                    ->orderBy('id', 'desc')
                    ->paginate()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MedalStoreRequest $request)
    {
        // Verifica se a ação é autorizada ...
        $this->authorize('store', Medal::class);

        $medal = new Medal($request->all());
        // Adiciona o usuário da requisição.
        $medal->user_id = $request->user()->id;

        // Salva o recurso no banco de dados
        if ($medal->save()) {
            return (new MedalResource($medal))
                ->response()
                ->setStatusCode(201);
        }
        return (new MedalResource($medal))
            ->response()
            ->setStatusCode(422);
    }

    /**
     * Display the specified resource.
     *
     * @param int $medalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($medalId)
    {
        $medal = Medal::findOrFail($medalId);
        // Verifica se a ação é autorizada ...
        $this->authorize('update', $medal);
        return (new MedalResource($medal))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $medalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MedalUpdateRequest $request, $medalId)
    {
        $medal = Medal::findOrFail($medalId);
        // Verifica se a ação é autorizada ...
        $this->authorize('update', $medal);
        // Adiciona o usuário da requisição.
        // $medal->user_id = $request->user()->id;
        // Verifica se foi enviado novo arquivo, se sim, substitui o novo.
        if ($request->has('image')) {
            $path = $request->file('image')
                ->storeAs('', $medal->path, 'public');
            $medal->path = $path ? $path : $medal->path;
        }
        // Atualiza o recurso no banco de dados
        $medal->update($request->all());
        return (new MedalResource($medal))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $medalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($medalId)
    {
        $medal = Medal::findOrFail($medalId);
        // Verifica se a ação é autorizada ...
        $this->authorize('destroy', $medal);

        // Evita que s medalha seja removida se houver recursos vinculados.
        $errors = array();
        // Verifica se existe(m) jogos(s) vinculado(s) a esta medalha.
        if (!$medal->games->isEmpty()) {
            $errors[] = 'Ops! Há jogo(s) vinculado(s) a esta medalha.';
        }
        // Verifica se existe(m) jogadores(s) vinculado(s) a esta medalha.
        if (!$medal->players->isEmpty()) {
            $errors[] = 'Ops! Há jogadores(s) vinculado(s) a esta medalha.';
        }

        // Se houver recursos vinculados, envia resposta de dados não processad, com informações sobre o erro.
        if ($errors) {
            return (new MedalResource($medal))
                ->additional([
                    "message" => "Não foi possível remover a medalha.",
                    "errors" => $errors
                ])
                ->response()
                ->setStatusCode(422);
        }

        // Remove o recurso do banco de dados
        if ($medal->delete()) {
            return response()->noContent();
        }
        return (new MedalResource($medal))
            ->additional([
                "message" => "Não foi possível remover a medalha.",
            ])
            ->response()
            ->setStatusCode(422);
    }
}
