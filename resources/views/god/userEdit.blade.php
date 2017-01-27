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

                        {!! Form::open(['route'=>'updateUser', 'class' => 'form']) !!}
                        	{!!Form::hidden('id', $data->getId(), array('id' => 'invisible_id'))!!}
                            <div class="form-group">
                                <label>name</label>
                                {!! Form::input('text', 'name', $data->getName(), ['class'=> 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                {!! Form::email('email', $data->getEmail(), ['class'=> 'form-control']) !!}
                            </div>
                            @if($user->getGod())
	                            @if ($groups!=null)
							  		<div class="form-group">
							                <label for="GROUP">GROUP</label>
							                <select  class="form-control" multiple="multiple" name="groups[]">
							                   @foreach ($groups as $id => $group) :
							                       @if ($group['active'])
							                           	<option selected="selected" value={{$id}}>{{$group['name']}}</option>
							                       @else:
							                           <option value={{$id}}>{{$group['name']}}</option>
							                       @endif
							                   @endforeach
							                </select>
							           </div>
							  	@endif
						  	@endif

                            <div>
                                {!! Form::submit('SUBMIT',['class' => 'btn  btn-success']) !!}
                                <a href="/admin/user"><button type="button" class="btn btn-danger">RETURN</button></a>
                            </div>
                        {!! Form::close() !!}

                    </div>
                </div>
                <div class="panel panel-default">
                <div class="panel-heading">CHANGE PASSWORD</div>
                <div class="panel-body">
						{!! Form::open(['route'=>'updateUserPassword', 'class' => 'form']) !!}
							{!!Form::hidden('id', $data->getId(), array('id' => 'invisible_id'))!!}
                            <div class="form-group">
                                <label>Password</label>
                                {!! Form::password('password', ['class'=> 'form-control', ]) !!}
                            </div>
                            <div class="form-group">
                                <label>Password confirmation</label>
                                {!! Form::password('password_confirmation', ['class'=> 'form-control']) !!}
                            </div>
                            <div>
                                {!! Form::submit('SUBMIT',['class' => 'btn  btn-success']) !!}
                                <a href="/admin/user"><button type="button" class="btn btn-danger">RETURN</button></a>
                            </div>
                        {!! Form::close() !!}
                </div>
                </div>
            </div>
        </div>
    </div>

@endsection
