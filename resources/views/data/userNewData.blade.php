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
							<i class="fa fa-edit"></i> {{trans('translations.newdata')}}
						</li>
					</ol>
				</div>
				<div class="panel panel-default">
          <div class="panel-heading">{{trans('translations.sensitivedatainfo')}}</div>
        <div class="panel-body">
					@if (count($errors) > 0)
					@foreach ($errors->all() as $error)
					<div class="alert alert-danger">
						<li>{{$error}}</li>
					</div>
					@endforeach
					@endif

					<form class="upload-form" action="/data/save" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="NAME">{{trans('translations.name')}}</label>
							<input type="TEXT" class="form-control" name="name"  required autofocus>
						</div>
						<div class="form-group">
							<label for="TEXT">{{trans('translations.text')}}</label>
							<textarea class="form-control" name="text" rows="10"  required> </textarea>
						</div>
						<div class="form-group">
							<input  class="upload-file" type="file" name="dataFile" id="dataFile">
						</div>
						<div class="form-group">
							<label for="GROUP">{{trans('translations.groups')}}</label>
							<select class="form-control" multiple="multiple" name="groups[]">
								@foreach ($groups as $group)
								<option value="{{$group->getId()}}">{{$group->getName()}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label for="TAGS">{{trans('translations.tags')}}</label>
							<input type="text" name="tags" class="form-control"
							data-role="tagsinput" />
						</div>
						<div class="form-group">
							<button type="submit" onclick="submit1()" class="btn  btn-success">{{trans('translations.save')}}</button>
							<a href="/"><button type="button" class="btn btn-danger">{{trans('translations.return')}}</button></a>
						</div>

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

			</script>

				@endsection
