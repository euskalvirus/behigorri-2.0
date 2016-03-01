@extends('layouts.master')
@section('title',  $title)
@section('content')
<div class="title">Wellcome to the main page visitor</div>
Buenas tardes {{ $user->getEmail() }}
<a href="auth/logout" href="{{ URL::route('logout') }}">Salir</a>
@endsection
