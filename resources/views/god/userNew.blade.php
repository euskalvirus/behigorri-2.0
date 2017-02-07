@extends('layouts.master')
@section('content')
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div>
          <h1 class="page-header">{{trans('translations.newuser')}}:</h1>
          <ol class="breadcrumb">
            <li>
              <i class="fa fa-dashboard"></i>  <a href="/">{{trans('translations.dashboard')}}</a>
            </li>
            <li>
              <i class="fa fa-table"></i> <a href="/admin/user">{{trans('translations.useradministration')}}</a>
            </li>
            <li class="active">
              <i class="fa fa-edit"></i> {{trans('translations.newuser')}}
            </li>
          </ol>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">{{trans('translations.userinfo')}}</div>
        <div class="panel-body">
          @if (count($errors) > 0)
          @foreach ($errors->all() as $error)
          <div class="alert alert-danger">
            <li>{{$error}}</li>
          </div>
          @endforeach
          @endif

          {!! Form::open(['route' => 'doRegistration', 'class' => 'form']) !!}
          <div class="form-group">
            <label>{{trans('translations.name')}}</label>
            {!! Form::input('text', 'name', '', ['class'=> 'form-control', 'required' => 'required', 'autofocus'=>'autofocus']) !!}
          </div>
          <div class="form-group">
            <label>{{trans('translations.email')}}</label>
            {!! Form::email('email', '', ['class'=> 'form-control', 'required' => 'required']) !!}
          </div>
          <!--
          <div class="form-group">
            <label>{{trans('translations.password')}}</label>
            {!! Form::password('password', ['class'=> 'form-control', 'required' => 'required']) !!}
          </div>
          <div class="form-group">
            <label>{{trans('translations.passconfirm')}}</label>
            {!! Form::password('password_confirmation', ['class'=> 'form-control', 'required' => 'required']) !!}
          </div>-->
          <div class="form-group">
            <label for="GROUP">{{trans('translations.groups')}}</label>
            @if ($groups!=null && !empty($groups))
                <select  class="form-control" multiple="multiple" name="groups[]">
                    @foreach ($groups as $group)
                        <option value={{$group->getId()}}>{{$group->getName()}}</option>
                    @endforeach
                </select>
            @endif
          </div>

          <div>
            {!! Form::submit(trans('translations.save'),['class' => 'btn  btn-success']) !!}
            <a href="/admin/user"><button type="button" class="btn btn-danger">{{trans('translations.return')}}</button></a>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
      </div>
    </div>
  </div>
</div>

@endsection
