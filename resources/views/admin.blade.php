<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    {{-- <link href="{{ mix('css/admin.css') }}" rel="stylesheet" type="text/css" /> --}}
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=default,Array.prototype.find,Array.prototype.findIndex,Array.prototype.includes"></script>
</head>
<body>
    <div id="app-admin">

    </div>
</body>
{{-- <script src="{{ mix('/js/admin_lib.js') }}"></script> --}}
<script src="{{ mix('/js/admin.js') }}"></script>
</html>