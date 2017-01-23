@extends('layouts.master')
@section('title',  $title)
@section('content')
@include('god.godMenu')
<div class="row">
<div class="col-xs-12">
    <h1 class="row">LISTA DE USUARIOS:</h1>
    <a href="user/new"><button type="button" class="btn btn-default">NEW</button></a>
    <tr>
       <tr>
         <form method="post" action="/admin/user/search" accept-charset="UTF-8" style="display:inline">
           <input type="text" name="search" placeholder="Search..">
           <input type="submit" value="Submit">
         </form>

       </tr>
    </tr>
    <table class="table">
        @foreach ($datas as $data)
        <tr>
            <td>{{ $data->getName() }}</td>
            <td>
                <a href="user/edit/{{$data->getId()}}"><button type="button" class="btn  btn-success">
                    EDIT</button></a>

                <form method="GET" action="user/delete/{{$data->getId()}}"accept-charset="UTF-8" style="display:inline">
                	@if($data->getId()==$user->getId())
		    			<button class="btn btn-danger" disabled  type="button">
		        			DELETE
		    			</button>
		    		@else
		    			<button class="btn btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
		        			DELETE
		    			</button>
		    		@endif
				</form>   
                <a href="user/view/{{$data->getId()}}"><button type="button" class="btn btn-primary"
                   formaction="exit">VIEW</button></a>
            </td>
            </tr>
    		
        @endforeach
        </table>
		{!!$datas->render()!!}
    </div>
    </div>

@endsection