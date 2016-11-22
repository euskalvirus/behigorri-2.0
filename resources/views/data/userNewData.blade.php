@extends('layouts.master')
@section('title',  $title)
@section('content')

<div class="title">{{ $title }}</div> 
<div>Buenas tardes {{ $user->getEmail() }} <a href="/auth/logout">Salir</a></div>
<div class="row">
<div class="col-xs-6">
    <h1 class="row"></h1>
    
    <form action="/data/save" method="post">
        <div class="form-group">
            <label for="NAME">NAME</label>
            <input type="TEXT" class="form-control" name="name" placeholder="Name">
        </div>
        <div class="form-group">
            <label for="TEXT">TEXT</label>
            <textarea class="form-control" name="text" rows="10" placeholder="Text"> </textarea>
        </div>
        <div class="form-group">
            <label for="GROUP">GROUP</label>
            <select multiple="multiple" name="groups[]">
               @foreach ($groups as $group)
                   <option value={{$group->getId()}}>{{$group->getName()}}</option>
               @endforeach
            </select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn  btn-success">SUBMIT</button>
            <a href="/"><button type="button" class="btn btn-danger">RETURN</button></a>
        </div>
        
    </form>
</div>
@endsection