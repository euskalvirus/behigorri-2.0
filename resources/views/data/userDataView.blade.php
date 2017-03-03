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
							<input type="TEXT" class="form-control" name="owner" value="{{$data->getFIlename()}}.{{$data->getFileExtension()}}" readonly >
							<button type="button" data-toggle="modal" data-button-action="downloadFile" data-data-id="{{$data->getId()}}"  class="btn btn-success"
							form-action="show" onclick="decryptionPass(this)" title={{trans('translations.downloadFile')}}><span class="glyphicon glyphicon-download"></button>
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
				<!--<a href="/"><button type="button" class="btn btn-danger">{{trans('translations.return')}}</button></a>-->
					<a href="/"><button type="button" class="btn btn-danger">{{trans('translations.return')}}</button></a>
				</div>
				</div>




				<div class="modal fade" id="passModal"
				tabindex="-1" role="dialog"
				aria-labelledby="favoritesModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close"
							data-dismiss="modal"
							aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title"
							id="favoritesModalLabel">{{trans('translations.authenticate')}}</h4>
						</div>
						{!! Form::open(['id'=>'passForm', 'url' => '/data/confirmPassword', 'class' => 'form', 'method' => 'post']) !!}
						<div class="form-group">
							<input type="hidden" name="id" id="dataId" value="" />
							<input type="hidden" name="action" id="dataAction" value="" />
						</div>
						<div class="modal-body">
							<label>{{trans('translations.authenticate')}}</label>
							{!! Form::password('password', ['class'=> 'form-control', 'required' => 'required', 'autofocus' => 'autofocus']) !!}
						</div>
						<div class="modal-footer">
							{!! Form::button(trans('translations.save'),['class' => 'btn  btn-success', "onClick"=>"submitDownload()"]) !!}
							<button type="button" data-dismiss="modal" class="btn btn-danger">{{trans('translations.return')}}</button></a>
						</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>

@endsection
