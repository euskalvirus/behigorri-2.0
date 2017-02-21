<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}" />

        <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular-route.min.js"></script>
        <script src="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>
      <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />

      <link rel="stylesheet" type="text/css"  href="{{ asset('css/metisMenu.min.css') }}" />
      <link rel="stylesheet" type="text/css"  href="{{ asset('css/bootstrap.min.css') }}" />
      <link rel="stylesheet" type="text/css"  href="{{ asset('css/sb-admin-2.css') }}" />
      <link rel="stylesheet" type="text/css"  href="{{ asset('css/plugins/morris.css') }}" />
      <link rel="stylesheet" type="text/css" href="{{ asset('font-awesome/css/font-awesome.min.css') }}" />


      <!-- DataTables CSS -->
      <link href="datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

      <!-- DataTables Responsive CSS -->
      <link href="datatables-responsive/dataTables.responsive.css" rel="stylesheet">
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
      <meta name="viewport" content="width=device-width, initial-scale=0, maximum-scale=1, user-scalable=1">
      <meta name="description" content="">
      <meta name="author" content="">

      <title>SB Admin - Behigorri Password Manager</title>



      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->


    </head>
    <body>
        <!--<div class="container-fluid">
            @{{greeting}}
        </div>  -->
        <div id="wrapper">
            <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
              @include('index.menu')
              @include( ($user->getGod()) ? 'god.godMenuSidebar' : 'user.userMenuSidebar')
            </nav>
            @yield('content')
          </div>

        </div>
        <!-- /#wrapper -->

        <!-- jQuery -->
        <script src="{{ asset('js/jquery.min.js') }}"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>

        <!-- Morris Charts JavaScript -->
        <script src="{{ asset('js/plugins/morris/raphael.min.js')}}"></script>
        <script src="{{ asset('js/plugins/morris/morris.min.js')}}"></script>
        <script src="{{ asset('js/plugins/morris/morris-data.js')}}"></script>

        <!-- Metis Menu Plugin JavaScript -->
    <script src="{{ asset('js/metisMenu.min.js')}}"></script>

        <!-- Custom Theme JavaScript -->
        <script src="{{ asset('js/sb-admin-2.js') }}"></script>

        <!-- DataTables JavaScript -->
        <script src="{{ asset('datatables/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset('datatables-plugins/dataTables.bootstrap.min.js')}}"></script>
        <script src="{{ asset('datatables-responsive/dataTables.responsive.js')}}"></script>
        <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true
            });
        });
        </script>
      </body>

</html>
