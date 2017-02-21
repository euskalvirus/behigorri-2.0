@extends('layouts.master')
@section('content')
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div>
          <h1 class="page-header">{{trans('translations.viewuser')}}:</h1>
          <ol class="breadcrumb">
            <li>
              <i class="fa fa-dashboard"></i>  <a class="tags" href="/">{{trans('translations.dashboard')}}</a>
            </li>
            <li>
              <i class="fa fa-table"></i> <a class="tags" href="/admin/user">{{trans('translations.useradministration')}}</a>
            </li>
            <li class="active">
              <i class="fa fa-edit"></i> {{trans('translations.viewuser')}}
            </li>
          </ol>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">{{trans('translations.userinfo')}}</div>
        <div class="panel-body">
          @if (count($errors) > 0)
          <div class="alert alert-danger">

          </div>
          @endif

          {!! Form::open(['class' => 'form']) !!}
          {!!Form::hidden('id', $data->getId(), array('id' => 'invisible_id'))!!}
          <div class="form-group">
            <label>{{trans('translations.name')}}</label>
            {!! Form::input('text', 'name', $data->getName(), ['class'=> 'form-control', 'readonly']) !!}
          </div>
          <div class="form-group">
            <label>{{trans('translations.email')}}</label>
            {!! Form::email('email', $data->getEmail(), ['class'=> 'form-control', 'readonly']) !!}
          </div>

          @if ($groups!=null)
          <div class="form-group">
            <label for="GROUP">{{trans('translations.groups')}}</label>
            <select  class="form-control" multiple="multiple" readonly name="groups[]">
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
</div>

@endsection
