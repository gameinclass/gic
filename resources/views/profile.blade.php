@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="">{{Auth::user()->name}}</h1>
        <h3 class="mb-5">{{Auth::user()->email}}</h3>

        <div class="border border-white rounded">
            <passport-authorized-clients></passport-authorized-clients>
        </div>
    </div>
@endsection
