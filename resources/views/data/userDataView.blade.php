@extends( (!$user->getGod()) ? 'layouts.master' : 'layouts.master')
@section('content')
<br>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">USER DATA</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                           <div class="alert alert-danger">

                           </div>
                        @endif
  <div class="form-group">
    {!!Form::hidden('id', $data->getId(), array('id' => 'invisible_id'))!!}
  </div>
  <div class="form-group">
    <label for="NAME">NAME</label>
    <input type="TEXT" class="form-control" name="name" readonly value="{{$data->getName()}}" placeholder="Name">
  </div>
  <div class="form-group">
    <label for="OWNER">OWNER</label>
    <input type="TEXT" class="form-control" name="owner" readonly value="{{$data->getUser()->getName()}}">
  </div>
  @if(!$data->getIsFile())
  	<div class="form-group">
    	<label for="TEXT">TEXT</label>
    	<textarea class="form-control" readonly style="overflow:auto;resize:none" name="text" rows="10" placeholder="Text" >{{$text}}</textarea>
  	</div>
  @endif

  @if ($groups!=null)
  		<div class="form-group">
                <label for="GROUP">GROUP</label>
                <select  class="form-control" multiple="multiple" readonly name="groups[]">
                   @foreach ($groups as $id => $group) :
                       @if ($group['active'])
                           	<option  disabled value={{$id}}>{{$group['name']}}</option>
                       @endif
                   @endforeach
                </select>
           </div>
  @endif

  <div class="form-group">
        	<label for="TAGS">TAGS</label>
            <input type="text" name="tags" class="form-control"
                    data-role="tagsinput" value="{{$tags}}" readonly disabled/>
    	</div>
  @if ($user->getId() == $data->getUser()->getId())
  	<a href="/data/edit/{{$data->getId()}}"><button type="button" class="btn  btn-success">EDIT</button></a>
  @endif
  <a href="/"><button type="button" class="btn btn-danger">RETURN</button></a>

@endsection
