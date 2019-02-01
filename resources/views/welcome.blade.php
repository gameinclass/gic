<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Game In Class</title>

    <!-- Fonts -->

    <!-- Styles -->
    <style>

    </style>
</head>
<body>
<div>
    @if (Route::has('login'))
        <div>
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div>
        <h1>Usuário e seus jogos</h1>
        <ul>
            @foreach($users as $user)
                <li>
                    {{$user->name}}
                    <ul>
                        @foreach($user->games as $game)
                            <li>{{$game->title}}</li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
</div>
</body>
</html>
