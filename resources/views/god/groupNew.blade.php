@extends('layouts.master')
@section('content')
    <br>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans('translations.newgroup')}}</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                        	@foreach ($errors->all() as $error)
                           		<div class="alert alert-danger">
    								<li>{{$error}}</li>
                          		</div>
                           @endforeach
                        @endif

                        {!! Form::open(['route' => 'saveGroup', 'class' => 'form']) !!}
                            <div class="form-group">
                                <label>{{trans('translations.NAME')}}</label>
                                {!! Form::input('text', 'name', '', ['class'=> 'form-control', 'required' => 'required']) !!}
                            </div>
                            <div class="form-group">
                                <label for="USER">{{trans('translations.users')}}</label>
					            <select class="form-control" multiple="multiple" name="users[]">
					               @foreach ($users as $user)
					                   <option value={{$user->getId()}}>{{$user->getName()}}</option>
					               @endforeach
					            </select>
                            </div>
                            <div>
                                {!! Form::submit(trans('translations.save'),['class' => 'btn  btn-success']) !!}
                                <a href="/admin/group"><button type="button" class="btn btn-danger">{{trans('translations.return')}}</button></a>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
