@extends('layouts.master')
@section('content')
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div>
					<h1 class="page-header">{{trans('translations.userdata')}}:</h1>
					<ol class="breadcrumb">
						<li>
							<i class="fa fa-table"></i> <a class="tags" href="/">{{trans('translations.dashboard')}}</a>
						</li>
						<li class="active">
							<i class="fa fa-edit"></i> {{trans('translations.viewdata')}}
						</li>
					</ol>
				</div>
				<div class="panel panel-default">
          <div class="panel-heading"><h4>{{trans('translations.sensitivedatainfo')}}</h4></div>
        <div class="panel-body">
					@if (count($errors) > 0)
					<div class="alert alert-danger">

					</div>
					@endif
					<div class="form-group">
						{!!Form::hidden('id', $data->getId(), array('id' => 'invisible_id'))!!}
					</div>
					<div class="form-group">
						<label for="NAME">{{trans('translations.name')}}</label>
						<input type="TEXT" class="form-control" name="name" readonly value="{{$data->getName()}}" placeholder="Name">
					</div>
					<div class="form-group">
						<label for="OWNER">{{trans('translations.owner')}}</label>
						<input type="TEXT" class="form-control" name="owner" readonly value="{{$data->getUser()->getName()}}">
					</div>
					<div class="form-group">
						<label for="TEXT">{{trans('translations.text')}}</label>
						<textarea class="form-control" readonly style="overflow:auto;resize:none" name="text" rows="10" placeholder="Text" >{{$text}}</textarea>
					</div>
					<div class="form-group">
						<label for="DATAFILE">{{trans('translations.file')}}</label>
						@if ($data->getHasFile())
							<a class="tags" href="/data/download/{{$data->getId()}}">
								<input type="TEXT" class="form-control" name="owner" value="{{$data->getFIlename()}}.{{$data->getFileExtension()}}" readonly >
							</a>
						@else
								<input type="TEXT" class="form-control" name="owner" value="" readonly >
						@endif
					</div>


					@if ($data->getGroup())
					<div class="form-group">
						<label for="GROUP">{{trans('translations.groups')}}</label>
						<select  class="form-control" readonly disabled>
							<option selected disabled value="{{$data->getGroup()->getId()}}">{{$data->getGroup()->getName()}}</option>
						</select>
					</div>
					@endif

					<div class="form-group">
						<label for="TAGS">{{trans('translations.tags')}}</label>
						<input type="text" name="tags" class="form-control"
						data-role="tagsinput" value="{{$tags}}" readonly disabled/>
					</div>

					@if ($user->getId() == $data->getUser()->getId())
					<a href="/data/edit/{{$data->getId()}}/{{$user->getDataToken()}}"><button type="button" class="btn  btn-success">{{trans('translations.edit')}}</button></a>
					@endif
					<a href="/"><button type="button" class="btn btn-danger">{{trans('translations.return')}}</button></a>
				</div>
				</div>
					@endsection
