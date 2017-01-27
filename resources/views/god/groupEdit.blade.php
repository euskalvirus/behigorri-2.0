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

   			 			<form action="/admin/group/update" method="post">
   			 				 <div class="form-group">
							    {!!Form::hidden('id', $group->getId(), array('id' => 'invisible_id'))!!}
							  </div>

						  <div class="form-group">
						    <label for="NAME">GROUP NAME</label>
						    <input type="TEXT" class="form-control" name="name" value={{$group->getName()}} placeholder="Name" required>
						  </div>


                            @if ($users!=null)
					  			<div class="form-group">
					                <label for="GROUP">USERS</label><p>
					                <select  class="form-control" multiple="multiple" name="updatedUsers[]">
					                   @foreach ($users as $id => $groupUser) :
					                       @if ($groupUser['active'])
					                           	<option selected="selected" value={{$id}}>{{$groupUser['name']}}</option>
					                       @else:
					                           <option value={{$id}}>{{$groupUser['name']}}</option>
					                       @endif
					                   @endforeach
					                </select>
					           </div>
					  		@endif
					  		<button type="submit" class="btn  btn-success">Submit</button>
  							<a href="/admin/group"><button type="button" class="btn btn-danger">Return</button></a>
						</form>
                	</div>
                </div>
            </div>
        </div>
    </div>
@endsection
