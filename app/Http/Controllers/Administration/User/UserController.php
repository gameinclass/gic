<?php

namespace App\Http\Controllers\Administration\User;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request('q')) {
            // Todos usuários com paginação
            $users = User::with('actor')
                ->where('name', 'like', '%' . request('q') . '%')
                ->orWhere('email', 'like', '%' . request('q') . '%')
                ->paginate(5);
        } else {
            // Todos usuários com paginação
            $users = User::with('actor')->paginate(5);
        }

        return view('administration.user.index')
            ->with('users', $users);
    }
}
