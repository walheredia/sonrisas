<!DOCTYPE html>
<html>
    <head>
        <title>Sonrisas | Administración</title>

        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/main.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/fancyAlert.min.css') }}" />
    </head>
    <body>
        <div class="login-container">
            @yield('content')
        </div>

        <script type="text/javascript" src="{{ URL::asset('js/fancyAlert.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/easyModal.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/main.js') }}"></script>
    </body>
</html>