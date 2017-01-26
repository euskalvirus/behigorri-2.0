<html ng-app="prueba">
    <head>
      <link rel="stylesheet" href="{{ asset('css/app.css') }}"></link>
      <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"></link>
      <link rel="stylesheet" href="{{ asset('css/sb-admin.css') }}"></link>
      <link rel="stylesheet" href="{{ asset('css/plugins/morris.css') }}"></link>
      <link rel="stylesheet" type="text/css" href="{{ asset('font-awesome/css/font-awesome.min.css') }}"></link>
      <script src="{{ asset('js/app.js') }}"></script>
      <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
      <link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />
      <script src="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>

      <style type="text/css">
        .bootstrap-tagsinput {
          width: 100%;
        }
        .label {
          line-height: 2 !important;
      }
      </style>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">

      <title>SB Admin - Bootstrap Admin Template</title>



      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->


    </head>
    <body ng-controller="pruebacontroller">
        <!--<div class="container-fluid">
            @{{greeting}}
        </div>  -->
        <div id="wrapper">
          @include('god.godMenu')
          @include('god.godMenuSidebar')
          <div id="page-wrapper">
            <div class="container-fluid">
              @yield('content')
            </div>
          </div>

        </div>
        <!-- /#wrapper -->

        <!-- jQuery -->
        <script src="{{ asset('js/jquery.js')}}"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="{{ asset('js/bootstrap.min.js')}}"></script>

        <!-- Morris Charts JavaScript -->
        <script src="{{ asset('js/plugins/morris/raphael.min.js') }}"></script>
        <script src="{{ asset('js/plugins/morris/morris.min.js') }}"></script>
        <script src="{{ asset('js/plugins/morris/morris-data.js') }}"></script>

    </body>

</html>
