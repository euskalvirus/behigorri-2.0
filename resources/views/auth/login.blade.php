<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"></link>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}"></link>
    <script src="{{ asset('js/app.js') }}"></script>
    <!--<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>-->
    <!--<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular-route.min.js"></script>-->


  <title>SB Admin - Behigorri</title>



  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->


</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-4 col-lg-offset-4">
            <img src="http://www.hiru.com/image/image_gallery?uuid=fcb598a0-2ec9-4c29-8a1c-60c04e83864c&groupId=10137&t=1260818689203">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                       <div class="alert alert-danger">
							Email or Password not correct!
                       </div>
                    @endif
                    @if (isset($error) && $error="not activated")
                       <div class="alert alert-danger">
							User account still not activated, check your email for activation!
                       </div>
                    @endif
                    {!! Form::open(['route' => 'postLogin', 'class' => 'form']) !!}
                        <div class="form-group">
                            <label>Email</label>
                            {!! Form::email('email', '', [
                                'class'=> 'form-control',
                                'required' => 'required'
                            ]) !!}
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            {!! Form::password('password', ['class'=> 'form-control']) !!}
                        </div>
                        <div class="checkbox">
                            <label><input name="remember" type="checkbox"> Remember me</label>
                        </div>
                        <div>
                            {!! Form::submit('login',['class' => 'btn btn-primary']) !!}
                            <!--<a class="btn btn-success" href="{{ URL::to('auth/register')}}">Register</a>-->
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
