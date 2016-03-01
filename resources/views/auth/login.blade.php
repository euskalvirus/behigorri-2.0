@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-4 col-lg-offset-4">
            <img src="http://www.hiru.com/image/image_gallery?uuid=fcb598a0-2ec9-4c29-8a1c-60c04e83864c&groupId=10137&t=1260818689203">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                       <div class="alert alert-danger">

                       </div>
                    @endif
                    {!! Form::open(['route' => 'postLogin', 'class' => 'form']) !!}
                        <div class="form-group">
                            <label>Email</label>
                            {!! Form::email('email', '', [
                                'class'=> 'form-control',
                                'required' => 'required'
                            ]) !!}
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            {!! Form::password('password', ['class'=> 'form-control']) !!}
                        </div>
                        <div class="checkbox">
                            <label><input name="remember" type="checkbox"> Remember me</label>
                        </div>
                        <div>                            
                            {!! Form::submit('login',['class' => 'btn btn-primary']) !!}
                            <a class="btn btn-success" href="{{ URL::to('auth/register')}}">Register</a>
                        </div>
                    {!! Form::close() !!}
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection