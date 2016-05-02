@extends('layouts.master')
@section('title',  $title)
@section('content')
<div class="title">{{ $title }}</div> 
<div>Buenas tardes {{ $user->getEmail() }} <a href="auth/logout">Salir</a></div>
<div class="row">
<div class="col-xs-12">
    <h1 class="row">LISTA DE FICHEROS:</h1>
    <a href="data\new"><button type="button" class="btn btn-default">NEW</button></a>
    <table class="table">
        @foreach ($datas as $data)
        <tr>
            <td>{{ $data->getName() }}</td>
            <td>
                <a href="data\edit\{{$data->getId()}}"><button type="button" class="btn btn-primary">
                    EDIT</button></a>
                <a href="data\delete\{{$data->getId()}}"><button type="button" class="btn btn-danger"
                   formaction="delete">DELETE</button></a>
                <a href="#"><button type="button" class="btn btn-primary"
                   formaction="exit">EXIT NOW NOW NOW</button></a>
            </td>
            </tr>
        @endforeach
        </table>
    </div>
    </div>

@endsection
