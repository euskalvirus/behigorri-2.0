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
                           <div class="alert alert-danger">
    
                           </div>
                        @endif
    <form action="/data/update" method="post">
  <div class="form-group">
    {!!Form::hidden('id', $data->getId(), array('id' => 'invisible_id'))!!}
  </div>
  <div class="form-group">
    <label for="NAME">NAME</label>
    <input type="TEXT" class="form-control" name="name"  value="{{$data->getName()}}">
  </div>
  <div class="form-group">
    <label for="OWNER">OWNER</label>
    <input type="TEXT" class="form-control" name="owner" value="{{$data->getUser()->getName()}}" readonly >
  </div>
  @if(!$data->getIsFile())
  	<div class="form-group">
    	<label for="TEXT">TEXT</label>
    	<textarea class="form-control" style="overflow:auto;resize:none" name="text" rows="10" >{{$text}}</textarea>
  	</div>
  @endif
  	
  @if ($groups!=null)
  		<div class="form-group">
                <label for="GROUP">GROUP</label>
                <select  multiple="multiple" name="groups[]">
                   @foreach ($groups as $id => $group) :
                       @if ($group['active'])
                           	<option selected="selected" value={{$id}}>{{$group['name']}}</option>                           
                       @else:
                           <option value={{$id}}>{{$group['name']}}</option>
                       @endif
                   @endforeach
                </select>
           </div>
  @endif
  
  <div class="form-group">
        	<label for="TAGS">TAGS</label>
            <input type="text" name="tags" class="form-control"
                    data-role="tagsinput" value="{{$tags}}" />
    	</div>
  <button type="submit" class="btn  btn-success">SUBMIT</button>
  <a href="/"><button type="button" class="btn btn-danger">RETURN</button></a>
</form>
    
@endsection