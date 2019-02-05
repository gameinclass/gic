<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GiC</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#b91d47">
    <meta name="msapplication-config" content="/favicon/browserconfig.xml">
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
