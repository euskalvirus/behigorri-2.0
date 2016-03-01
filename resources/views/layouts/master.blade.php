<html>
    <head>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}"></link>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
        
    </head>
    <body>
        <div class="container-fluid">
            
        </div>
        <div class="container-fluid">
            @yield('content')
        </div>
    </body>
</html>