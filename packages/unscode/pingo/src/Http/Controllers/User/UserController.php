<?php

namespace Unscode\Pingo\Http\Controllers\User;

use App\Models\User;
use App\Models\Actor;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\User as UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // Verifica se a ação é autorizada ...
        $this->authorize('index', User::class);
        // Se sim, continua
        return UserResource::collection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        // Verifica se a ação é autorizada ...
        $this->authorize('store', User::class);
        // Se sim, continua
        // User
        $user = new User($request->all());
        // Adiciona um senha aleatória de 6 dígitos para o usuário.
        $user->password = str_random(6);
        // Actor
        $actor = new Actor($request->input('actor'));
        // Armazena os recursos
        if ($user->save() && $user->actor()->save($actor)) {
            return (new UserResource($user))
                ->response()
                ->setStatusCode(201);
        }
        return (new UserResource($user))
            ->response()
            ->setStatusCode(422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::with('actor')->findOrFail($id);
        // Verifica se a ação é autorizada ...
        $this->authorize('update', $user);

        // Atualiza os recursos
        $user->update($request->all());
        // Atualizado o tipo de ator somente se for administrador
        if (Auth::user()->actor->is_administrator) {
            $user->actor->update($request->input('actor'));
        }
        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Verifica se a ação é autorizada ...
        $this->authorize('destroy', $user);

        if ($user->actor->delete() && $user->delete()) {
            return response()->noContent();
        }
        return response('', 422);
    }
}
