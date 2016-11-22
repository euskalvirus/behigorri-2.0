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
                    <div class="panel-heading">NEW USER</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                           <div class="alert alert-danger">
    
                           </div>
                        @endif
                        
                        {!! Form::open(['route' => 'doRegistration', 'class' => 'form']) !!}
                            <div class="form-group">
                                <label>name</label>
                                {!! Form::input('text', 'name', '', ['class'=> 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                {!! Form::email('email', '', ['class'=> 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                {!! Form::password('password', ['class'=> 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label>Password confirmation</label>
                                {!! Form::password('password_confirmation', ['class'=> 'form-control']) !!}
                            </div>
                            <div>
                                {!! Form::submit('SUBMIT',['class' => 'btn  btn-success']) !!}
                                <a href="/admin/user"><button type="button" class="btn btn-danger">RETURN</button></a>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
