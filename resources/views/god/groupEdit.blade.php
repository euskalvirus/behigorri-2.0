@extends('layouts.master')
@section('content')
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div>
          <h1 class="page-header">{{trans('translations.editgroup')}}:</h1>
          <ol class="breadcrumb">
            <li>
              <i class="fa fa-dashboard"></i>  <a class="tags" href="/">{{trans('translations.dashboard')}}</a>
            </li>
            <li>
              <i class="fa fa-table"></i> <a class="tags" href="/admin/group">{{trans('translations.groupadministration')}}</a>
            </li>
            <li class="active">
              <i class="fa fa-edit"></i> {{trans('translations.editgroup')}}
            </li>
          </ol>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">{{trans('translations.groupinfo')}}</div>
        <div class="panel-body">
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                  @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                  @endforeach
                </div>
                @endif

   			 			<form action="/admin/group/update" method="post">
   			 				 <div class="form-group">
							    {!!Form::hidden('id', $group->getId(), array('id' => 'invisible_id'))!!}
							  </div>

						  <div class="form-group">

						    <label for="NAME">{{trans('translations.name')}}</label>
						    <input type="TEXT" class="form-control" name="name" value="{{$group->getName()}}" required>
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
    </div>
@endsection
