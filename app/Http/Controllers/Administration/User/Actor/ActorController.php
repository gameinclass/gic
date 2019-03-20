<?php

namespace App\Http\Controllers\Administration\User\Actor;

use App\Models\User;
use App\Models\Actor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActorController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $userId
     * @param  int $actorId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId, $actorId)
    {
        // Procura pelo recurso no banco de dados.
        // $user = User::findOrFail($userIdentification);
        // Procura pelo recurso no banco de dados.
        $actor = Actor::findOrFail($actorId);
        // The current user can or can't ...
        $this->authorize('update', $actor);
        // OPÇÕES PARA ATOR
        $field = ['is_administrator' => false, 'is_design' => false, 'is_player' => false];
        if ($request->has('actor')) {
            // Verifica se o valor do campo existe entre as chaves do vetor.
            if (array_key_exists($request->get('actor', ''), $field)) {
                $field[$request->input('actor')] = true;
            } else { // Senão, define o padrão.
                $field['is_player'] = true;
            }
        }
        // Atualiza o modelo no banco de dados.
        if ($actor->update($field)) {
            $request->session()->flash('updated_successful', "As informações do usuário foram atualizadas");
            // Sucesso! Redireciona de volta.
            return redirect()->back();
        }
        $request->session()->flash('updated_unsuccessful', 'Não foi possível atualizar as informações do usuário');
        // Fracasso! Redireciona de volta.
        return redirect()->back();
    }
}
