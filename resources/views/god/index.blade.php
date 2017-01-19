@extends('layouts.master')
@section('title',  $title)
@section('content')
@include('god.godMenu')
<br>
    <br>
    <h1 class="row">LISTA DE FICHEROS:</h1>
    <a href="data/new"><button type="button" class="btn btn-default">NEW</button></a>
    <a href="data/newFile"><button type="button" class="btn btn-default">NEW FILE</button></a>
    <table class="table">
    <tr>
    		<th>NAME</th>
    		<th>ACTION</th>

    	</tr>
    @if ($user->getSalt()!=Null)
    <tr>
       <tr>
         <form method="post" action="data/search" accept-charset="UTF-8" style="display:inline">
           <input type="text" name="search" placeholder="Search..">
           <input type="submit" value="Submit">
         </form>

       </tr>
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
	    			<button class="btn btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="Delete Data" data-message="Are you sure you want to delete this data ?">
	        			DELETE
	    			</button>
				</form>
                <a href="data/view/{{$data->getId()}}"><button type="button" class="btn btn-success"
                   formaction="show">VIEW</button></a>
            </td>
            </tr>
        @endforeach
        </table>
        {!!$datas->render()!!}
      @else
      	<a href="admin/generateSalt"><button type="button" class="btn btn-success"
                   formaction="show">GENERATE SALT</button></a>
      @endif
@endsection
