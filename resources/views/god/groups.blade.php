@extends('layouts.master')
@section('title',  $title)
@section('content')
@include('god.godMenu')
<div class="row">
<div class="col-xs-12"><br><br>
<div>
    <h1 >GROUPS LIST:</h1>
    <a href="group/new"><button type="button" class="btn btn-default">NEW</button></a>
     <form method="post" action="/admin/group/search" accept-charset="UTF-8" style="display:inline">
           <input type="text" name="search" placeholder="Search..">
           <input type="submit" value="Submit">
         </form>
</div><br>  
    <table class="table">
    <tr>
    		<th>NAME</th>
    		<th>ACTION</th>

    	</tr>
        @foreach ($datas as $data)
        <tr>
       
    </tr>
        <tr>
            <td>{{ $data->getName() }}</td>
            <td>
                <a href="group/edit/{{$data->getId()}}"><button type="button" class="btn  btn-success">
                    EDIT</button></a>
                <!-- <a href="group/delete/{{$data->getId()}}"><button type="button" class="btn btn-danger"
                   formaction="delete">DELETE</button></a> -->
                <form method="GET" action="group/delete/{{$data->getId()}}" accept-charset="UTF-8" style="display:inline">
	    			<button class="btn btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="Delete Group" data-message="Are you sure you want to delete this group ?">
	        			DELETE
	    			</button>
				</form>   
                <a href="group/view/{{$data->getId()}}"><button type="button" class="btn btn-primary"
                   formaction="exit">VIEW</button></a>
            </td>
            </tr>
        @endforeach
        </table>
    </div>
    </div>

@endsection