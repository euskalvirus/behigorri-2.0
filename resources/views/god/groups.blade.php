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
                                <i class="fa fa-edit"></i> {{trans('translations.grouplist')}}
                            </li>
                        </ol>
    <a href="group/new"><button type="button" class="btn btn-default">{{trans('translations.new')}}</button></a>
    <form method="post" action="/admin/group/search" accept-charset="UTF-8" style="display:inline">
        <input type="text" name="search" placeholder={{trans('translations.searchplaceholder')}}>
        <input type="submit" value={{trans('translations.submit')}}>
    </form>
</div><br>
    <table class="table">
    <tr>
      <th>{{trans('translations.name')}}</th>
      <th>{{trans('translations.action')}}</th>

    	</tr>
        @foreach ($datas as $data)
        <tr>

    </tr>
        <tr>
            <td>{{ $data->getName() }}</td>
            <td>
                <a href="/admin/group/edit/{{$data->getId()}}"><button type="button" class="btn  btn-success">
                    {{trans('translations.edit')}}</button></a>
                <!-- <a href="group/delete/{{$data->getId()}}"><button type="button" class="btn btn-danger"
                   formaction="delete">DELETE</button></a> -->
                <form method="GET" action="/admin/group/delete/{{$data->getId()}}" accept-charset="UTF-8" style="display:inline">
	    			<button class="btn btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="Delete Group" data-message="Are you sure you want to delete this group ?">
	        			{{trans('translations.delete')}}
	    			</button>
				</form>
                <a href="/admin/group/view/{{$data->getId()}}"><button type="button" class="btn btn-primary"
                   formaction="exit">{{trans('translations.view')}}</button></a>
            </td>
            </tr>
        @endforeach
        </table>
    </div>
    </div>
</div>
</div>
</div>
</div>

@endsection
