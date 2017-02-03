@extends('layouts.master')
@section('content')
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
<div>
  <!--  <?php var_dump(App::getLocale() . '  ' . Session::get('locale'));?>-->
  <h1 class="page-header">{{trans('translations.userlist')}}</h1>
  <ol class="breadcrumb">
    <li>
      <i class="fa fa-dashboard"></i>  <a href="/">{{trans('translations.dashboard')}}</a>
    </li>
    <li class="active">
      <i class="fa fa-table"></i> {{trans('translations.useradministration')}}
    </li>
  </ol>

  <a href="user/new"><button type="button" class="btn btn-default btn-sm">{{trans('translations.new')}}</button></a>
  <form method="post" action="/admin/user/search" accept-charset="UTF-8" style="display:inline">
    <input type="text" name="search" placeholder={{trans('translations.searchplaceholder')}}>
    <input type="submit" value={{trans('translations.submit')}}>
  </form>
</div><br>
<div>
<table class="table">
  <tr bgcolor="#EDEDED">
    <th>{{trans('translations.name')}}</th>
    <th>{{trans('translations.email')}}</th>
    <th>{{trans('translations.status')}}</th>
    <th>{{trans('translations.action')}}</th>

  </tr>
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
      <a href="/admin/user/edit/{{$data->getId()}}">
        <button class="btn btn-primary"data-title="Edit"
              title={{trans('translations.edit')}}>
                <span class="glyphicon glyphicon-pencil"></span>
        </button></a>
        <form class="delete" method="GET" action="/admin/user/delete/{{$data->getId()}}"accept-charset="UTF-8" style="display:inline">
          @if($data->getId()==$user->getId())
            <button class="btn btn-danger" disabled  type="submit" title={{trans('translations.delete')}}>
          @else
            <button class="btn btn-danger" type="submit" data-toggle="modal"
              title={{trans('translations.delete')}} >
          @endif
          <span class="glyphicon glyphicon-trash"></span>
          </button>
        </form>
      <a href="/admin/user/view/{{$data->getId()}}"><button type="button" class="btn  btn-success"
          formaction="exit" title={{trans('translations.view')}}><span class="glyphicon glyphicon-search"></span></button></a>
    </td>
  </tr>
  @endforeach
</table>
    {!!$datas->render()!!}
</div>
</div>
</div>
</div>
</div>
@endsection
