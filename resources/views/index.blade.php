@extends('layouts.master')
@section('title',  $title)
@section('content')
<div class="title">{{ $title }} <a href="auth/logout">Salir</a></div>
Buenas tardes {{ $user->getEmail() }}
<div>
    <div class="page-header">
        <h1>MENU</h1>
    </div>
    <p>
        <button type="button" class="btn btn-lg btn-primary">USER</button>
        <button type="button" class="btn btn-lg btn-primary">GROUP</button>
    </p>
</div>

@endsection
