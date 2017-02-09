@extends('layouts.master')
@section('content')
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div>
          <h1 class="page-header">{{trans('translations.grouplist')}}</h1>
          <ol class="breadcrumb">
            <li>
              <i class="fa fa-dashboard"></i>  <a href="/">{{trans('translations.dashboard')}}</a>
            </li>
            <li class="active">
              <i class="fa fa-table"></i> {{trans('translations.groupadministration')}}
            </li>
          </ol>
          <a href="/admin/group/new"><button type="button" class="btn btn-default btn-sm">{{trans('translations.new')}}</button></a>
          <form method="post" action="/admin/group/search" accept-charset="UTF-8" style="display:inline">
            <input type="text" name="search" placeholder={{trans('translations.searchplaceholder')}}>
            <input type="submit" value={{trans('translations.submit')}}>
          </form>
        </div><br>
        <div class="panel panel-default">
        <table class="table">
          <tr bgcolor="#EDEDED">
            <th>{{trans('translations.name')}}</th>
            <!--<th>{{trans('translations.action')}}</th>-->
            <th>{{trans('translations.action')}}</th>

          </tr>
          @foreach ($datas as $data)
          <tr>
            <td>{{ $data->getName() }}</td>
            <td>
              <a href="/admin/group/view/{{$data->getId()}}"><button type="button" class="btn  btn-success"
                formaction="exit" title={{trans('translations.view')}}><span class="glyphicon glyphicon-search"></span></button></a>
              <a href="/admin/group/edit/{{$data->getId()}}"><button type="button" class="btn btn-primary" title={{trans('translations.edit')}}>
                <span class="glyphicon glyphicon-pencil"></span></button></a>
                <!-- <a href="group/delete/{{$data->getId()}}"><button type="button" class="btn btn-danger"
                formaction="delete">DELETE</button></a> -->
                <form class="delete" method="GET" action="/admin/group/delete/{{$data->getId()}}" accept-charset="UTF-8" style="display:inline">
                  <button class="btn btn-danger" type="submit" data-toggle="modal" title={{trans('translations.delete')}}>
                      <span class="glyphicon glyphicon-trash"></span>
                  </button>
                </form>
                </td>
              </tr>
              @endforeach
            </table>
          </div>
          </div>
        </div>
      </div>
    </div>


    @endsection
