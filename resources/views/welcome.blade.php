<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GiC</title>
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!-- Fonts -->

    <!-- Styles -->
    <style>
        html,
        body {
            margin: 0;
            height: 100%;
            background: url("/svg/500.svg") no-repeat center center;
            background-size: cover;
        }

        .box {
            width: 100%;
            height: 100%;
            background: url("/svg/desenhado-6.svg") no-repeat center center;
            background-size: cover;
            display: table;
        }

        .box-middle {
            height: 100%;
            display: table-row;
        }

        .box-middle-content {
            vertical-align: middle;
            display: table-cell;
        }
        .brand {
            margin: 1px auto;
            width: 350px;
            display: block;
        }
    </style>
</head>
<body>
<div class="box">
    <div class="box-middle">
        <div class="box-middle-content">
            <img class="brand" src="/svg/brand.svg" title="Game In Class">
        </div>
    </div>
</div>
</body>
</html>
