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
      <i class="fa fa-edit"></i> {{trans('translations.userlist')}}
    </li>
  </ol>

  <a href="user/new"><button type="button" class="btn btn-default">{{trans('translations.new')}}</button></a>
  <form method="post" action="/admin/user/search" accept-charset="UTF-8" style="display:inline">
    <input type="text" name="search" placeholder={{trans('translations.searchplaceholder')}}>
    <input type="submit" value={{trans('translations.submit')}}>
  </form>
</div><br>
<div>
<table class="table">
  <tr>
    <th>{{trans('translations.name')}}</th>
    <th>{{trans('translations.action')}}</th>

  </tr>
  @foreach ($datas as $data)
  <tr>
    <td>{{ $data->getName() }}</td>
    <td>
      <a href="/admin/user/edit/{{$data->getId()}}"><button type="button" class="btn  btn-success">
        {{trans('translations.edit')}}</button></a>

        <form method="GET" action="/admin/user/delete/{{$data->getId()}}"accept-charset="UTF-8" style="display:inline">
          @if($data->getId()==$user->getId())
          <button class="btn btn-danger" disabled  type="button">
            {{trans('translations.delete')}}
          </button>
          @else
          <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
            {{trans('translations.delete')}}
          </button>
          @endif
        </form>
      <a href="/admin/user/view/{{$data->getId()}}"><button type="button" class="btn btn-primary"
          formaction="exit">{{trans('translations.view')}}</button></a>
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
