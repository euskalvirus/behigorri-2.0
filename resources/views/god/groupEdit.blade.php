@extends('layouts.master')
@section('content')
    <br>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans('translations.editgroup')}}</div>
                    <div class="panel-body">

   			 			<form action="/admin/group/update" method="post">
   			 				 <div class="form-group">
							    {!!Form::hidden('id', $group->getId(), array('id' => 'invisible_id'))!!}
							  </div>

						  <div class="form-group">
						    <label for="NAME">{{trans('translations.name')}}</label>
						    <input type="TEXT" class="form-control" name="name" value={{$group->getName()}} required>
						  </div>


                            @if ($users!=null)
					  			<div class="form-group">
					                <label for="GROUP">{{trans('translations.users')}}</label><p>
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
					  		<button type="submit" class="btn  btn-success">{{trans('translations.save')}}</button>
  							<a href="/admin/group"><button type="button" class="btn btn-danger">{{trans('translations.return')}}</button></a>
						</form>
                	</div>
                </div>
            </div>
        </div>
    </div>
@endsection
