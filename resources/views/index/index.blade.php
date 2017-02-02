@extends('layouts.master')
@section('content')
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">

        <div>

          <h1 class="page-header">{{trans('translations.filelist')}}</h1>
          <ol class="breadcrumb">
            <li class="active">
              <i class="fa fa-dashboard"></i> {{trans('translations.dashboard')}}
            </li>
          </ol>
          <a href="data/new"><button type="button" class="btn btn-default btn-sm">{{trans('translations.new')}}</button></a>
          <a href="data/newFile"><button type="button" class="btn btn-default btn-sm">{{trans('translations.newfile')}}</button></a>
          <form method="post" action="/data/search" accept-charset="UTF-8" style="display:inline">
            <input type="text" name="search" placeholder={{trans('translations.searchplaceholder')}}>
            <input type="submit" value={{trans('translations.submit')}}>
          </form>
          {{trans('translations.tagsearch')}}:
          @foreach ($tags as $tag)
          <a href="/data/searchTag/{{$tag->getName()}}">{{$tag->getName()}}</a>,
          @endforeach
        </div ><br>
        <table class="table">
          <thead>
            <tr>
              <th>{{trans('translations.name')}}</th>
              <th>{{trans('translations.tags')}}</th>
              <th>{{trans('translations.action')}}</th>

            </tr>
          </thead>
          <tbody>
            @if ($user->getSalt()!=Null)

            @foreach ($datas as $data)
            <tr>
              <td>{{ $data->getName() }}</td>
              <td>
                @if($data->getTags())
                  @foreach($data->getTags() as $tag)
                      <a href="/data/searchTag/{{$tag->getName()}}">{{$tag->getName()}},</a>
                  @endforeach
                @endif
              </td>
                <td>
                  <a href="/data/edit/{{$data->getId()}}"><button type="button" class="btn btn-primary" title={{trans('translations.edit')}}>
                    <span class="glyphicon glyphicon-pencil"></button></a>
                    <!-- <a href="data/delete/{{$data->getId()}}"><button type="button" class="btn btn-danger"
                    formaction="delete" data-target="#confirmDelete" data-title="Delete User"
                    data-message="Are you sure you want to delete this data ?">DELETE</button></a> -->
                    <form class="delete" method="GET" action="/data/delete/{{$data->getId()}}" accept-charset="UTF-8" style="display:inline">
                      <button class="btn btn-danger" value="Delete" type="submit" data-toggle="modal" title={{trans('translations.delete')}}>
                        <span class="glyphicon glyphicon-trash"></span>
                      </button>
                    </form>
                    <a href="/data/view/{{$data->getId()}}"><button type="button" class="btn btn-success"
                      formaction="show" title={{trans('translations.view')}}><span class="glyphicon glyphicon-search"></button></a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @if ($datas)
              {!!$datas->render()!!}
              @endIf
              @else
              <a href="admin/generateSalt"><button type="button" class="btn btn-success"
                formaction="show">GENERATE SALT</button></a>
                @endif
              </div>
            </div>
          </div>
        </div>
        @endsection
