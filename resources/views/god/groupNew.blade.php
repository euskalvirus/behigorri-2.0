@extends('layouts.master')
@section('content')
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div>
          <h1 class="page-header">{{trans('translations.newgroup')}}:</h1>
          <ol class="breadcrumb">
            <li>
              <i class="fa fa-dashboard"></i>  <a href="/">{{trans('translations.dashboard')}}</a>
            </li>
            <li>
              <i class="fa fa-table"></i> <a href="/admin/group">{{trans('translations.groupadministration')}}</a>
            </li>
            <li class="active">
              <i class="fa fa-edit"></i> {{trans('translations.newgroup')}}
            </li>
          </ol>
        </div>
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
            <label>{{trans('translations.name')}}</label>
            {!! Form::input('text', 'name', '', ['class'=> 'form-control', 'required' => 'required']) !!}
          </div>
          <div class="form-group">
            <label for="USER">{{trans('translations.users')}}</label>
            <select class="form-control" multiple="multiple" name="users[]">
              @foreach ($users as $groupUser)
              <option value={{$groupUser->getId()}}>{{$groupUser->getName()}}</option>
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
