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
								<a class="tags" href="/data/download/{{$data->getId()}}">
									<input type="TEXT" class="form-control" value="{{$data->getFIlename()}}.{{$data->getFileExtension()}}" readonly >
								</a>
							@endif
							<input  class="upload-file" type="file" name="dataFile" id="dataFile">
						</div>

						<div class="form-group">
							<label for="GROUP">{{trans('translations.groups')}}</label>
							<select class="form-control" name="group">
								@if ($data->getGroup())
									<option selected="selected" value="{{$data->getGroup()->getId()}}">{{$data->getGroup()->getName()}}</option>
									<option value=null>{{trans('translations.none')}}</option>
								@else
									<option selected value=null>{{trans('translations.none')}}</option>
								@endif
								@foreach ($groups as $id => $group) :
										<option value={{$id}}>{{$group['name']}}</option>
								@endforeach
							</select>
						</div>


						<div class="form-group">
							<label for="TAGS">TAGS</label>
							<input type="text" name="tags" class="form-control"
							data-role="tagsinput" value="{{$tags}}" />
						</div>
						<button type="submit" onclick="submit1()" class="btn  btn-success">{{trans('translations.save')}}</button>
						<a href="/"><button type="button" class="btn btn-danger">{{trans('translations.return')}}</button></a>
					</form>
				</div>
			</div>
			<script>

			function submit1() {
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

			</script>


					@endsection
