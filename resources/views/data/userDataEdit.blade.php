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
							<i class="fa fa-edit"></i> {{trans('translations.editdata')}}
						</li>
					</ol>
				</div>
				<div class="panel panel-default">
          <div class="panel-heading">{{trans('translations.sensitivedatainfo')}}</div>
        <div class="panel-body">
					@if (count($errors) > 0)
					<div class="alert alert-danger">

					</div>
					@endif
					<form class="upload-form" action="/data/update" method="post" enctype="multipart/form-data">
						<div class="form-group">
							{!!Form::hidden('id', $data->getId(), array('id' => 'invisible_id'))!!}
							{!!Form::hidden('dataToken', $user->getDataToken(), array('id' => 'invisible_id'))!!}
						</div>
						<div class="form-group">
							<label for="NAME">{{trans('translations.name')}}</label>
							<input type="TEXT" class="form-control" name="name"  value="{{$data->getName()}}" required>
						</div>
						<div class="form-group">
							<label for="OWNER">{{trans('translations.owner')}}</label>
							<input type="TEXT" class="form-control" name="owner" value="{{$data->getUser()->getName()}}" readonly >
						</div>
						<div class="form-group">
							<label for="TEXT">{{trans('translations.text')}}</label>
							<textarea class="form-control" required style="overflow:auto;resize:none" name="text" rows="10" >{{$text}}</textarea>
						</div>

						<div class="form-group">

							<label for="DATAFILE">{{trans('translations.file')}}</label>
							@if ($data->getHasFile())
								<input type="TEXT" class="form-control" value="{{$data->getFIlename()}}.{{$data->getFileExtension()}}" readonly >
								<button type="button" data-toggle="modal" data-button-action="downloadFile" data-data-id="{{$data->getId()}}"  class="btn btn-success"
								form-action="show" onclick="decryptionPass(this)" title={{trans('translations.downloadFile')}}><span class="glyphicon glyphicon-download"></button>
							@endif
							<input  class="upload-file" type="file" name="dataFile" id="dataFile">
						</div>
						<div class="form-group">
							<label for="GROUP">{{trans('translations.groups')}}</label>
							<select class="form-control" name="group">
								@if ($data->getGroup())
									<option  onClick="hideDiv()" selected="selected" value="{{$data->getGroup()->getId()}}">{{$data->getGroup()->getName()}}</option>
									<option value=null onClick="showDiv()">{{trans('translations.none')}}</option>
								@else
									<option selected onClick="hideDiv()" value=null>{{trans('translations.none')}}</option>
								@endif
								@foreach ($groups as $id => $group) :
										<option value={{$id}} onClick="showDiv()">{{$group['name']}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label for="oldPassword">{{trans('translations.decryptpassword')}}</label>
							<input type="PASSWORD" name="oldPassword" class="form-control" >
						</div>
						<div id="hidden_div" class="form-group" style="display: none;">
								<label for="newPassword">{{trans('translations.newDecryptPassword')}}</label>
								<input id="newPass" type="PASSWORD" name="newPassword" class="form-control">
						</div>
						<div class="form-group">
							<label for="TAGS">TAGS</label>
							<input type="text" name="tags" class="form-control"
							data-role="tagsinput" value="{{$tags}}" />
						</div>
						<button type="submit" onclick="fileValidation()" class="btn  btn-success">{{trans('translations.save')}}</button>
						<a href="/"><button type="button" class="btn btn-danger">{{trans('translations.return')}}</button></a>
					</form>
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

			<script>

			function fileValidation() {
				var maxSize="15728640"
				var fileInput = $('.upload-file');
				//var maxSize = fileInput.data('data-max-size');
				if(fileInput.get(0).files.length){
					var fileSize = fileInput.get(0).files[0].size; // in bytes
					if(fileSize>maxSize){
						alert('file size is more then' + maxSize + ' bytes');
						return false;
					}else{
						$('.upload-form' ).submit();
					}

				}
			}

			function showDiv(){
				$('#newPass').prop('required', true);
				$('#hidden_div').show();
			}

			function hideDiv(){
				$('#newPass').prop('required', false);
				$('#hidden_div').hide();
			}

			</script>


					@endsection
