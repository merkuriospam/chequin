<?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
<!doctype html>
<html lang="en">
    <head>
        <title>CheQuin</title>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="{{ asset('media/img/doorbell.png') }}">
        <meta name="description" content="Crea tu Timbre Digital y comparte el vínculo con quien quieras">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta property="og:title" content="CheQuin" />
        <meta property="og:url" content="<?php echo $actual_link; ?>" />
        <meta property="og:description" content="Crea tu Timbre Digital y comparte el vínculo con quien quieras">
        <meta property="og:image" content="{{ asset('media/img/DoorEntrance01.jpg') }}">
        <meta property="og:type" content="website" />
        <meta property="og:locale" content="es_ES" />
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap4/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/pretty-json/pretty-json.css') }}">
        <link href="{{ asset('vendor/mdb/css/mdb.min.css') }}" rel="stylesheet">
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        @yield('content')
        <script type="text/javascript" src="{{ asset('vendor/jquery/jquery-3.2.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/popperjs/popper.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/bootstrap4/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/underscore/underscore-min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/backbonejs/backbone-min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/pretty-json/pretty-json-min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/cookie/jquery.cookie.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/mdb/js/mdb.min.js') }}"></script>
		<script src="{{ asset('js/app.js') }}"></script>
		<script type="text/javascript">var BASE_URL = '{{ url('/') }}';</script>
		@stack('scripts')
    </body>
</html>