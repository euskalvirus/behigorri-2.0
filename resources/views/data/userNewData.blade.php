@extends('layouts.master')
@section('title',  $title)
@section('content')

@if(!$user->getGod())
    @include('user.userMenu')
@else
	@include('god.godMenu')

@endif

	<br>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">USER DATA</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                        	@foreach ($errors->all() as $error)
                           		<div class="alert alert-danger">
    								<li>{{$error}}</li>
                          		</div>
                           @endforeach
                        @endif
    
    <form action="/data/save" method="post">
        <div class="form-group">
            <label for="NAME">NAME</label>
            <input type="TEXT" class="form-control" name="name"  required>
        </div>
        <div class="form-group">
            <label for="TEXT">TEXT</label>
            <textarea class="form-control" name="text" rows="10"  required> </textarea>
        </div>
        <div class="form-group">
            <label for="GROUP">GROUP</label>
            <select multiple="multiple" name="groups[]">
               @foreach ($groups as $group)
                   <option value="{{$group->getId()}}">{{$group->getName()}}</option>
               @endforeach
            </select>
        </div>
		<div class="form-group">
        	<label for="TAGS">TAGS</label>
            <input type="text" name="tags" class="form-control"
                    data-role="tagsinput" />
    	</div>
        <div class="form-group">
            <button type="submit" class="btn  btn-success">SUBMIT</button>
            <a href="/"><button type="button" class="btn btn-danger">RETURN</button></a>
        </div>
        
    </form>
</div>
@endsection