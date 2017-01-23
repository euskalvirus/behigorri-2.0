@extends('layouts.master')
@section('title',  $title)
@section('content')
@include('user.userMenu')
<br>
<br>
<div class="row">
<div class="col-xs-12">
    <h1 class="panel-heading">LISTA DE FICHEROS:</h1>
    <a href="data/new"><button type="button" class="btn btn-default">NEW</button></a>
    <a href="data/newFile"><button type="button" class="btn btn-default">NEW FILE</button></a>
    <br><br>
     <form method="post" action="/data/search" accept-charset="UTF-8" style="display:inline">
           <input type="text" name="search" placeholder="Search..">
           <input type="submit" value="Submit">
         </form>
    TAG SEARCH:  
    @foreach ($tags as $tag)
    			<a href="/data/searchTag/{{$tag->getName()}}">{{$tag->getName()}}</a>, 
    	@endforeach
    <table class="table">
    	<tr>
    		<th>NAME</th>
    		<th>ACTION</th>

    	</tr>
        @foreach ($datas as $data)
        <tr>
            <td>{{ $data->getName() }}</td>
            <td>
                <a href="data/edit/{{$data->getId()}}"><button type="button" class="btn btn-primary">
                    EDIT</button></a>
                <!-- <a href="data/delete/{{$data->getId()}}"><button type="button" class="btn btn-danger"
                   formaction="delete" data-target="#confirmDelete" data-title="Delete User" 
                   data-message="Are you sure you want to delete this data ?">DELETE</button></a> -->
            
                <form method="GET" action="data/delete/{{$data->getId()}}" accept-charset="UTF-8" style="display:inline">
                @if($data->getUser()->getId()==$user->getId())
	    			<button class="btn btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="Delete User" data-message="Are you sure you want to delete this data ?">
	        			DELETE
	    			</button>
	    		@else
	    			<button class="btn btn-danger" disabled value="Disabled Input Button" type="button">
	        			DELETE
	    			</button>
	    		@endif
				</form>
                <a href="data/view/{{$data->getId()}}"><button type="button" class="btn btn-success"
                   formaction="show">VIEW</button></a>
            </td>
            </tr>
        @endforeach
        </table>
       @if ($datas)
        	{!!$datas->render()!!}
        @endIf
    </div>
    </div>
    
    

@endsection
