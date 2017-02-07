@extends('layouts.master')
@section('content')
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div>
          <h1 class="page-header">{{trans('translations.viewgroup')}}:</h1>
            <ol class="breadcrumb">
              <li>
                <i class="fa fa-dashboard"></i>  <a href="/">{{trans('translations.dashboard')}}</a>
              </li>
              <li>
                <i class="fa fa-table"></i> <a href="/admin/group">{{trans('translations.groupadministration')}}</a>
              </li>
              <li class="active">
                <i class="fa fa-edit"></i> {{trans('translations.viewgroup')}}
              </li>
            </ol>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading">{{trans('translations.groupinfo')}}</div>
          <div class="panel-body">
            <div class="form-group">
              {!!Form::hidden('id', $group->getId(), array('id' => 'invisible_id'))!!}
            </div>

            <div class="form-group">
              <label for="NAME">{{trans('translations.name')}}</label>
              <input type="TEXT" class="form-control" readonly name="name" value={{$group->getName()}} placeholder="Name">
            </div>


            @if ($users!=null)
            <div class="form-group" readonly>
              <label for="GROUP">{{trans('translations.users')}}</label><p>
                <select  class="form-control" multiple="multiple" readonly name="updatedUsers[]">
                  @foreach ($users as $id => $groupUser) :
                  <option disabled value={{$id}}>{{$groupUser['name']}}</option>
                  @endforeach
                </select>
              </div>
              @endif
              <a href="/admin/group/edit/{{$group->getId()}}"><button type="button" class="btn  btn-success">{{trans('translations.edit')}}</button></a>
              <a href="/admin/group"><button type="button" class="btn btn-danger">{{trans('translations.return')}}</button></a>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>
    @endsection
