@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-5">
            {{Auth::user()->name}}
        </h1>
        <h4 class="mb-5">
            {{Auth::user()->email}}

            @if(Auth::user()->actor)
                @if(Auth::user()->actor->is_administrator)
                    <span class="badge badge-warning">Administrador</span>
                @endif
                @if(Auth::user()->actor->is_design)
                    <span class="badge badge-warning">Professor</span>
                @endif
            @endif
        </h4>
        <div class="border border-white rounded">
            <passport-authorized-clients></passport-authorized-clients>
        </div>
    </div>
@endsection
