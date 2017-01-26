@extends('layouts.master')
@section('content')
    <br>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">EDIT GROUP</div>
                    <div class="panel-body">
   			 				 <div class="form-group">
							    {!!Form::hidden('id', $group->getId(), array('id' => 'invisible_id'))!!}
							  </div>

						  <div class="form-group">
						    <label for="NAME">GROUP NAME</label>
						    <input type="TEXT" class="form-control" readonly name="name" value={{$group->getName()}} placeholder="Name">
						  </div>


                            @if ($users!=null)
					  			<div class="form-group" readonly>
					                <label for="GROUP">USERS</label><p>
					                <select  multiple="multiple" readonly name="updatedUsers[]">
					                   @foreach ($users as $id => $groupUser) :
					                           	<option disabled value={{$id}}>{{$groupUser['name']}}</option>
					                   @endforeach
					                </select>
					           </div>
					  		@endif
					  		<a href="/admin/group/edit/{{$group->getId()}}"><button type="button" class="btn  btn-success">EDIT</button></a>
  							<a href="/admin/group"><button type="button" class="btn btn-danger">Return</button></a>
                	</div>
                </div>
            </div>
        </div>
    </div>
@endsection
