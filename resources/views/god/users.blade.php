@extends('layouts.master')
@section('title',  $title)
@section('content')
@include('god.godMenu')
<div class="row">
<div class="col-xs-12">
    <h1 class="row">LISTA DE USUARIOS:</h1>
    <a href="user\new"><button type="button" class="btn btn-default">NEW</button></a>
    <table class="table">
        @foreach ($datas as $data)
        <tr>
            <td>{{ $data->getName() }}</td>
            <td>
                <a href="user\edit\{{$data->getId()}}"><button type="button" class="btn btn-primary">
                    EDIT</button></a>
                <a href="user\delete\{{$data->getId()}}"><button type="button" class="btn btn-danger"
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