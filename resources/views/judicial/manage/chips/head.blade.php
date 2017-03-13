<head>
    <title>后台管理--三门峡市司法局</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!-- jQuery -->
    <script src="{{ asset('/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('/js/jquery.form.js') }}"></script>
    <!-- Bootstrap Core CSS and JS -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel='stylesheet' type='text/css' />
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
    <!-- datetimepicker Core CSS and JS -->
    <link href="{{ asset('/css/jquery.datetimepicker.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('/js/jquery.datetimepicker.full.js') }}"></script>
    <!-- Custom CSS -->
    <link href="{{ asset('/css/style.css') }}" rel='stylesheet' type='text/css' />
    <link href="{{ asset('/css/orso.css') }}" rel='stylesheet' type='text/css' />
    <!-- Graph CSS -->
    <link href="{{ asset('/css/lines.css') }}" rel='stylesheet' type='text/css' />
    <link href="{{ asset('/css/font-awesome.css') }}" rel="stylesheet" type='text/css'>

    <!-- Nav CSS -->
    <link href="{{ asset('/css/custom.css') }}" rel="stylesheet" type='text/css' />
    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{ asset('/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('/js/custom.js') }}"></script>
    <!-- Graph JavaScript -->
    <script src="{{ asset('/js/d3.v3.js') }}"></script>
    <script src="{{ asset('/js/rickshaw.js') }}"></script>
    <!-- 司法局后台js -->
    <script src="{{ asset('/js/manage.js') }}"></script>
    <script src="{{ asset('/js/orso.js') }}"></script>
</head>