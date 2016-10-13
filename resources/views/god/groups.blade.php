@extends('layouts.master')
@section('title',  $title)
@section('content')
@include('god.godMenu')
<div class="row">
<div class="col-xs-12">
    <h1 class="row">LISTA DE GRUPOS:</h1>
    <a href="group/new"><button type="button" class="btn btn-default">NEW</button></a>
    <table class="table">
    @if(is_array($datas))
        @foreach ($datas as $data)
        <tr>
            <td>{{ $data->getName() }}</td>
            <td>
                <a href="group/edit/{{$data->getId()}}"><button type="button" class="btn btn-primary">
                    EDIT</button></a>
                <a href="group/delete/{{$data->getId()}}"><button type="button" class="btn btn-danger"
                   formaction="delete">DELETE</button></a>
                <a href="#"><button type="button" class="btn btn-primary"
                   formaction="exit">EXIT NOW NOW NOW</button></a>
            </td>
            </tr>
        @endforeach
        </table>
    @endif
    </div>
    </div>

@endsection