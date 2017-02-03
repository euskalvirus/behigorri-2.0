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
            <tr bgcolor="#EDEDED">
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
                    <form class="delete" method="GET" action="/data/delete/{{$data->getId()}}" accept-charset="UTF-8" style="display:inline">
                      @if ($data->getUser()->getId()==$user->getId())
                      <a href="/data/edit/{{$data->getId()}}"><button type="button" class="btn btn-primary" title={{trans('translations.edit')}}>
                        <span class="glyphicon glyphicon-pencil"></button></a>
                        <button class="btn btn-danger" value="Delete" type="submit" data-toggle="modal" title={{trans('translations.delete')}}>
                      @else
                      <a href="/data/edit/{{$data->getId()}}"><button disabled type="button" class="btn btn-primary" title={{trans('translations.edit')}}>
                        <span class="glyphicon glyphicon-pencil"></button></a>
                        <button disabled class="btn btn-danger" value="Delete" type="submit" data-toggle="modal" title={{trans('translations.delete')}}>
                      @endif
                      <span class="glyphicon glyphicon-trash"></span>
                    </button>
                    </form>
                    <a href="/data/view/{{$data->getId()}}">
                      @if (!$data->getIsFile())
                        <button type="button" class="btn btn-success"
                        formaction="show" title={{trans('translations.view')}}><span class="glyphicon glyphicon-search"></button>
                      @else
                        <button type="button" class="btn btn-success"
                        formaction="show" title={{trans('translations.download')}}><span class="glyphicon glyphicon-download-alt"></button>
                      @endif
                    </a>
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
