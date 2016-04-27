<html ng-app="prueba">
    <head>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}"></link>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
        <script src="{{ asset('js/prueba.js') }}"></script>
        
    </head>
    <body ng-controller="pruebacontroller">
        <!--<div class="container-fluid">
            @{{greeting}}
        </div>  -->
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>