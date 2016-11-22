@extends('layouts.master')
@section('title',  $title)
@section('content')

<div class="title">{{ $title }}</div> 
<div>Buenas tardes {{ $user->getEmail() }} <a href="/auth/logout">Salir</a></div>
<div class="row">
<div class="col-xs-6">
    <h1 class="row"></h1>
	<form  class="upload-form" action="/data/saveFile" method="post"  enctype="multipart/form-data">
		<div class="form-group">
			<input class="upload-file" type="file" name="dataFile" >
		</div>
		<div class="form-group">
            <label for="GROUP">GROUP</label>
            <select multiple="multiple" name="groups[]">
               @foreach ($groups as $group)
                   <option value={{$group->getId()}}>{{$group->getName()}}</option>
               @endforeach
            </select>
    	</div>
    	<div class="form-group">
    		
    	</div>
	</form>
	<button  class="btn  btn-success" onclick="submit1()">VERIFY</button>
	<a href="/"><button type="button" class="btn btn-danger">RETURN</button></a>
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
	}else{
		alert('choose file, please');
		return false;
	}

}
</script>
	
@endsection