@extends('layouts.master')
@section('content')
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div>
          <h1 class="page-header">{{trans('translations.edituser')}}:</h1>
          <ol class="breadcrumb">
            <li>
              <i class="fa fa-dashboard"></i>  <a class="tags" href="/">{{trans('translations.dashboard')}}</a>
            </li>
            <li>
              <i class="fa fa-table"></i> <a  class="tags"href="/admin/user">{{trans('translations.useradministration')}}</a>
            </li>
            <li class="active">
              <i class="fa fa-edit"></i> {{trans('translations.edituser')}}
            </li>
          </ol>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">{{trans('translations.changeinfo')}}</div>
        <div class="panel-body">
          @if (count($errors) > 0)
          <div class="alert alert-danger">
            @foreach($errors->all() as $error)
              <li>{{$error}}</li>
            @endforeach
          </div>
          @endif

          {!! Form::open(['route'=>'updateUser', 'class' => 'form']) !!}
          {!!Form::hidden('id', $data->getId(), array('id' => 'invisible_id'))!!}
          <div class="form-group">
            <label>{{trans('translations.name')}}</label>
            {!! Form::input('text', 'name', $data->getName(), ['class'=> 'form-control', 'required' => 'required']) !!}
          </div>
          <div class="form-group">
            <label>{{trans('translations.email')}}</label>
            {!! Form::email('email', $data->getEmail(), ['class'=> 'form-control', 'required' => 'required', 'disabled' => 'disabled']) !!}
          </div>
          @if ($groups!=null)
            @if($user->getGod())
              <div class="form-group">
                <label for="GROUP">{{trans('translations.groups')}}</label>
                <select  class="form-control" multiple="multiple" name="groups[]">
                  @foreach ($groups as $id => $group) :
                    @if ($group['active'])
                      <option selected="selected" value={{$id}}>{{$group['name']}}</option>
                    @else:
                      <option value="{{$id}}>{{$group['name']}}"</option>
                    @endif
                  @endforeach
                </select>
              </div>
            @else
              <div class="form-group">
                <label for="GROUP">{{trans('translations.groups')}}</label>
                <select readonly  class="form-control" multiple="multiple" name="groups[]">
                @foreach ($groups as $id => $group) :
                  @if ($group['active'])
                    <option value={{$id}}>{{$group['name']}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            @endif
          @endif

          <div>
            {!! Form::submit(trans('translations.save'),['class' => 'btn  btn-success']) !!}
            <a href="/admin/user"><button type="button" class="btn btn-danger">{{trans('translations.return')}}</button></a>
          </div>
          {!! Form::close() !!}

        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">{{trans('translations.changepass')}}</div>
        <div class="panel-body">
          {!! Form::open(['route'=>'updateUserPassword', 'class' => 'form']) !!}
          {!!Form::hidden('id', $data->getId(), array('id' => 'invisible_id'))!!}
          <div class="form-group">
            <label>{{trans('translations.password')}}</label>
            {!! Form::password('password', ['class'=> 'form-control', 'required' => 'required' ]) !!}
          </div>
          <div class="form-group">
            <label>{{trans('translations.passconfirm')}}</label>
            {!! Form::password('password_confirmation', ['class'=> 'form-control', 'required' => 'required']) !!}
          </div>
          <div>
            {!! Form::submit(trans('translations.save'),['class' => 'btn  btn-success']) !!}
            <a href="/admin/user"><button type="button" class="btn btn-danger">{{trans('translations.return')}}</button></a>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
      @if($user->getId() === $data->getId())
      <div class="panel panel-default">
        <div class="panel-heading">{{trans('translations.changedecryptpass')}}</div>
        <div class="panel-body">
          {!! Form::open(['route'=>'updateUserDecryptPassword', 'class' => 'form']) !!}
          {!!Form::hidden('id', $data->getId(), array('id' => 'invisible_id'))!!}
          <div class="form-group">
            <label>{{trans('translations.password')}}</label>
            {!! Form::password('password', ['class'=> 'form-control', 'required' => 'required' ]) !!}
          </div>
          <div class="form-group">
            <label>{{trans('translations.decryptpassword')}}</label>
            {!! Form::password('decryptpassword', ['class'=> 'form-control', 'required' => 'required' ]) !!}
          </div>
          <div class="form-group">
            <label>{{trans('translations.decryptpassconfirm')}}</label>
            {!! Form::password('decryptpassword_confirmation', ['class'=> 'form-control', 'required' => 'required']) !!}
          </div>
          <div>
            {!! Form::submit(trans('translations.save'),['class' => 'btn  btn-success']) !!}
            <a href="/admin/user"><button type="button" class="btn btn-danger">{{trans('translations.return')}}</button></a>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
      @endif
    </div>
    </div>
  </div>
</div>

@endsection
