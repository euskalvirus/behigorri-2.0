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
                    <div class="panel-heading">NEW GROUP</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                           <div class="alert alert-danger">
    
                           </div>
                        @endif
                        
                        {!! Form::open(['route' => 'saveGroup', 'class' => 'form']) !!}
                            <div class="form-group">
                                <label>name</label>
                                {!! Form::input('text', 'name', '', ['class'=> 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label for="USER">USERS</label>
					            <select multiple="multiple" name="users[]">
					               @foreach ($users as $user)
					                   <option value={{$user->getId()}}>{{$user->getName()}}</option>
					               @endforeach
					            </select>
                            </div>
                            <div>
                                {!! Form::submit('SUBMIT',['class' => 'btn  btn-success']) !!}
                                <a href="/admin/group"><button type="button" class="btn btn-danger">RETURN</button></a>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
