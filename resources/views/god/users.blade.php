@extends('layouts.master')
@section('content')
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div>
          <h1 class="page-header">{{trans('translations.userlist')}}</h1>
          <ol class="breadcrumb">
            <li>
              <i class="fa fa-dashboard"></i>  <a href="/">{{trans('translations.dashboard')}}</a>
            </li>
            <li class="active">
              <i class="fa fa-table"></i> {{trans('translations.useradministration')}}
            </li>
          </ol>

          <a href="/admin/user/new" class="nav navbar-right top-nav"><button type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span> {{trans('translations.newUser')}}</button></a>
          <form method="post" action="/admin/user/search" accept-charset="UTF-8" style="display:inline">
            <input type="text" name="search" placeholder={{trans('translations.searchplaceholder')}}>
            <input type="submit" value={{trans('translations.submit')}}>
          </form>
        </div><br>
        <div class="panel panel-default">
          <table width="100%" class="table table-striped table-bordered table-hover" id="dataTable">
            <thead>
            <tr bgcolor="#EDEDED">
              <th>{{trans('translations.name')}}</th>
              <th>{{trans('translations.email')}}</th>
              <th>{{trans('translations.status')}}</th>
              <th>{{trans('translations.action')}}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($datas as $data)
            <tr >
              <td>{{ $data->getName() }}</td>
              <td>{{ $data->getEmail()}}</td>
              <td>
                @if ($data->getUserActive())
                <span class="label label-default">
                  {{trans('translations.activated')}}
                </span>
                @else
                <span class="label label-warning">
                  {{trans('translations.notactivated')}}
                </span>
                @endif
              </td>
              <td>
                <a href="/admin/user/view/{{$data->getId()}}"><button type="button" class="btn  btn-success"
                  formaction="exit" title={{trans('translations.view')}}><span class="glyphicon glyphicon-search"></span></button></a>
                <a href="/admin/user/edit/{{$data->getId()}}">
                  <button class="btn btn-primary"data-title="Edit"
                  title={{trans('translations.edit')}}>
                  <span class="glyphicon glyphicon-pencil"></span>
                </button></a>
                <form class="delete" method="GET" action="/admin/user/delete/{{$data->getId()}}"accept-charset="UTF-8" style="display:inline">
                  @if($data->getId()==$user->getId()  || $data->getGod())
                  <button class="btn btn-danger" disabled  type="submit" title={{trans('translations.delete')}}>
                    @else
                    <button class="btn btn-danger" type="submit" data-toggle="modal"
                    title={{trans('translations.delete')}} >
                    @endif
                    <span class="glyphicon glyphicon-trash"></span>
                  </button>
                </form>
                </td>
              </tr>
              @endforeach
            </tbody>
            </table>
          </div>
            {!!$datas->render()!!}
          <a href="/admin/user/new" class="nav navbar-right top-nav"><button type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span> {{trans('translations.newUser')}}</button></a>
        </div>
      </div>
    </div>
  </div>
  @endsection
