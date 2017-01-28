@extends('layouts.master')
@section('content')
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
<div>
  <h1 class="page-header">USERS LIST:</h1>
  <ol class="breadcrumb">
    <li>
      <i class="fa fa-dashboard"></i>  <a href="/">Dashboard</a>
    </li>
    <li class="active">
      <i class="fa fa-edit"></i> User Administration
    </li>
  </ol>

  <a href="user/new"><button type="button" class="btn btn-default">NEW</button></a>
  <form method="post" action="/admin/user/search" accept-charset="UTF-8" style="display:inline">
    <input type="text" name="search" placeholder="Search..">
    <input type="submit" value="Submit">
  </form>
</div><br>
<div>
<table class="table">
  <tr>
    <th>NAME</th>
    <th>ACTION</th>
    <th>{!!$datas->render()!!}</th>

  </tr>
  @foreach ($datas as $data)
  <tr>
    <td>{{ $data->getName() }}</td>
    <td>
      <a href="/admin/user/edit/{{$data->getId()}}"><button type="button" class="btn  btn-success">
        EDIT</button></a>

        <form method="GET" action="/admin/user/delete/{{$data->getId()}}"accept-charset="UTF-8" style="display:inline">
          @if($data->getId()==$user->getId())
          <button class="btn btn-danger" disabled  type="button">
            DELETE
          </button>
          @else
          <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="Delete User" data-message="Are you sure you want to delete this user ?">
            DELETE
          </button>
          @endif
        </form>
      <a href="/admin/user/view/{{$data->getId()}}"><button type="button" class="btn btn-primary"
          formaction="exit">VIEW</button></a>
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
