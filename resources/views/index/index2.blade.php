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
          @if (count($errors) > 0)
          @foreach ($errors->all() as $error)
          <div class="alert alert-danger">
            <li>{{$error}}</li>
          </div>
          @endforeach
          @endif
          @if ($user->getSalt()!=Null)
          <a href="/data/new" class="nav navbar-right top-nav"><button type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span> {{trans('translations.newData')}}</button></a>
          <form method="post" action="/data/search" accept-charset="UTF-8" style="display:inline">
            <input type="text" name="search" placeholder={{trans('translations.searchplaceholder')}}>
            <input type="submit" value={{trans('translations.submit')}}>
          </form>
          @if($tags)
          {{trans('translations.tagsearch')}}:
          @foreach ($tags as $tag)
          <a class="tags" href="/data/searchTag/{{$tag->getName()}}">{{$tag->getName()}}</a>,
          @endforeach
          @endif
        </div ><br>
        <div class="panel panel-default">
          <table class="table">
            <tr bgcolor="#EDEDED">
              <th>{{trans('translations.name')}}</th>
              <th>{{trans('translations.tags')}}</th>
              <th>{{trans('translations.owner')}}</th>
              <th>{{trans('translations.action')}}</th>

            </tr>


            @foreach ($datas as $data)
            <tr>
              <td>{{ $data->getName() }}</td>
              <td>
                @if($data->getTags())
                @foreach($data->getTags() as $tag)
                <a class="tags" href="/data/searchTag/{{$tag->getName()}}">{{$tag->getName()}},</a>
                @endforeach
                @endif
              </td>
              <td>
                @if($data->getUser())
                {{ $data->getUser()->getName() }}
                @else
                {{$data->getGroup()->getName()}}
                @endif
              </td>
              <td>
                      <button type="button" data-toggle="modal" data-button-action="view" data-data-id="{{$data->getId()}}"  class="btn btn-success"
                      form-action="show" onclick="decryptionPass(this)" title={{trans('translations.view')}}><span class="glyphicon glyphicon-search"></button>
                      @if ($data->getUser() && $data->getUser()->getId()==$user->getId())
                      <button type="button" onclick="decryptionPass(this)" data-button-action="edit" data-data-id="{{$data->getId()}}" data-toggle="modal" class="btn btn-primary"  data-id=""
                      data-title="" title={{trans('translations.edit')}}>
                      <span class="glyphicon glyphicon-pencil"></button>
                        <button onclick="decryptionPass(this)" data-button-action="delete" data-data-id="{{$data->getId()}}" class="btn btn-danger" value="Delete" type="submit" data-toggle="modal" title={{trans('translations.delete')}}>
                          @else
                          <button disabled type="button" class="btn btn-primary" title={{trans('translations.edit')}}>
                            <span class="glyphicon glyphicon-pencil"></button></a>
                              <button disabled class="btn btn-danger" data-toggle="modal" title={{trans('translations.delete')}}>
                                @endif
                                <span class="glyphicon glyphicon-trash"></span>
                              </button>

                          </a>
                        </td>
                      </tr>
                      @endforeach
                    </table>
                    </div>
                    @if ($datas)
                        {!!$datas->render()!!}
                        <a href="/data/new" class="nav navbar-right top-nav"><button type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span> {{trans('translations.newData')}}</button></a>
                    @endIf
                    @else

                  <a href="admin/generateSalt"><button type="button" class="btn btn-success"
                    formaction="show">GENERATE SALT</button></a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="passModal"
            tabindex="-1" role="dialog"
            aria-labelledby="favoritesModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close"
                  data-dismiss="modal"
                  aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title"
                  id="favoritesModalLabel">{{trans('translations.authenticate')}}</h4>
                </div>
                {!! Form::open(['id'=>'passForm', 'url' => '/data/confirmPassword', 'class' => 'form', 'method' => 'post']) !!}
                <div class="form-group">
                  <input type="hidden" name="id" id="dataId" value="" />
                  <input type="hidden" name="action" id="dataAction" value="" />
                </div>
                <div class="modal-body">
                  <label>{{trans('translations.authenticate')}}</label>
                  {!! Form::password('password', ['class'=> 'form-control', 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                </div>
                <div class="modal-footer">
                  {!! Form::submit(trans('translations.save'),['class' => 'btn  btn-success']) !!}
                  <button type="button" data-dismiss="modal" class="btn btn-danger">{{trans('translations.return')}}</button></a>
                </div>
                {!! Form::close() !!}
              </div>
            </div>
          </div>

<script>
function decryptionPass(identifier) {
    id = $(identifier).data('data-id');
    action = $(identifier).data('button-action');
    console.log(action);
    $('#passModal').find('input[name="id"]').val(id);
    if(action == "edit")
    {
      $('#passModal').find('input[name="action"]').val('edit');
        //document.getElementById('passForm').action = '/data/edit';
    }else if (action == "view"){
      $('#passModal').find('input[name="action"]').val('view');
        //document.getElementById('passForm').action = '/data/view';
    }else if (action == "delete"){
        $('#passModal').find('input[name="action"]').val('delete');
    }
    $('#passModal').modal('show')
}

</script>
@endsection
