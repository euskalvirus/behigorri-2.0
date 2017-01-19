@extends('layouts.master')
@section('title',  $title)
@section('content')
@include('god.godMenu')
<div class="row">
<div class="col-xs-12">
    <h1 class="row">LISTA DE GRUPOS:</h1>
    <a href="group/new"><button type="button" class="btn btn-default">NEW</button></a>
    <table class="table">
        @foreach ($datas as $data)
        <tr>
            <td>{{ $data->name }}</td>
            <td>
                <a href="group/edit/{{$data->id}}"><button type="button" class="btn  btn-success">
                    EDIT</button></a>
                <!-- <a href="group/delete/{{$data->id}}"><button type="button" class="btn btn-danger"
                   formaction="delete">DELETE</button></a> -->
                <form method="GET" action="group/delete/{{$data->id}}" accept-charset="UTF-8" style="display:inline">
	    			<button class="btn btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="Delete Group" data-message="Are you sure you want to delete this group ?">
	        			DELETE
	    			</button>
				</form>   
                <a href="group/view/{{$data->id}}"><button type="button" class="btn btn-primary"
                   formaction="exit">VIEW</button></a>
            </td>
            </tr>
        @endforeach
        </table>
    </div>
    </div>

@endsection