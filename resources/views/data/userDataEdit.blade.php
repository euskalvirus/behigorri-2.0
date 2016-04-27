@extends('layouts.master')
@section('title',  $title)
@section('content')

    <div class="title">{{ $title }}</div> 
    <div>Buenas tardes {{ $user->getEmail() }} <a href="/auth/logout">Salir</a></div>
    <div class="row">
    <div class="col-xs-6">
    <h1 class="row"></h1>

    <form action="\data\update" method="post">
  <div class="form-group">
    <label for="ID">ID</label>
    <input type="TEXT" class="form-control" name="id" readonly value={{$data->getId()}}>
  </div>
  <div class="form-group">
    <label for="NAME">NAME</label>
    <input type="TEXT" class="form-control" name="name" value={{$data->getName()}} placeholder="Name">
  </div>
  <div class="form-group">
    <label for="OWNER">OWNER</label>
    <input type="TEXT" class="form-control" name="owner" readonly value={{$data->getUser()->getId()}}>
  </div>
  <div class="form-group">
    <label for="TEXT">TEXT</label>
    <textarea class="form-control" style="overflow:auto;resize:none" name="text" rows="10" placeholder="Text" >{{$text}}</textarea>
  </div>
  @if ($groups!=null)
      @if (count($groups)>1)
          <div class="form-group">
                <label for="GROUP">GROUP</label>
                <select  multiple="multiple" name="groups[]">
                   @foreach ($groups as $id => $group) :
                       @if ($group['active'])
                           <option selected value={{$id}}>{{$group['name']}}</option>
                       @else
                           <option value={{$id}}>{{$group['name']}}</option>
                       @endif
                   @endforeach
                </select>
           </div>
      @else :
           <div class="form-group">
                 <fieldset>
                        <legend>GROUPS</legend>
                        @foreach ($groups as $group)
                            <input checked="checked" type="checkbox" name="groups[]" value={{$group->getId()}} />{{$group->getName()}}<br />
                        @endforeach
                </fieldset>
           </div>
      @endif
  @endif
  <button type="submit" class="btn btn-default">Submit</button>
  <a href="\"><button type="button" class="btn btn-default">Return</button></a>
</form>
    
@endsection