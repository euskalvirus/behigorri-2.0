@extends('layouts.master')
@section('title',  $title)
@section('content')
    @include('god.godMenu')
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
                        
                        {!! Form::open(['class' => 'form']) !!}
                        	{!!Form::hidden('id', $data->getId(), array('id' => 'invisible_id'))!!}
                            <div class="form-group">
                                <label>name</label>
                                {!! Form::input('text', 'name', $data->getName(), ['class'=> 'form-control', 'readonly']) !!}
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                {!! Form::email('email', $data->getEmail(), ['class'=> 'form-control', 'readonly']) !!}
                            </div>
              				
                            @if ($groups!=null)
						  		<div class="form-group">
						                <label for="GROUP">GROUP</label>
						                <select  multiple="multiple" readonly name="groups[]">
						                   @foreach ($groups as $id => $group) :
						                       @if ($group['active'])
						                           	<option  disabled value={{$id}}>{{$group['name']}}</option>                           
						                       @endif
						                   @endforeach
						                </select>
						           </div>
						  @endif
						  
						  	<div class="form-group">
                                <a href="/admin/user/edit/{{$data->getId()}}"><button type="button" class="btn  btn-success">EDIT</button></a>
                                <a href="/admin/user"><button type="button" class="btn btn-danger">RETURN</button></a>
                            </div>
						  
                        {!! Form::close() !!}
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
