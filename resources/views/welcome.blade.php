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
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900&display=swap" rel="stylesheet">

    <!-- Styles -->
    <!-- Styles -->
    <link href="{{ asset('css/website.css') }}" rel="stylesheet">
</head>
<body>
<div class="box">
    <div class="box-top">
        <div class="box-top-content"></div>
    </div>
    <div class="box-middle">
        <div class="box-middle-content">
            <div class="container">
                <div class="about">
                    <div class="row">
                        <div class="col-12">
                            <img class="about-brand" src="/svg/brand2.svg" title="Game In Class">
                        </div>
                        <div class="col-12 col-md-8 col-lg-8">
                            <h1 class="about-title">
                                Plataforma de gamificação
                            </h1>
                            <h3 class="about-description">
                                Gic é uma plataforma de gamificação voltada para aplicação de gamificação em
                                disciplinas do ensino superior. Esta aplicação controla o acesso aos usuários
                                aos recursos dos usuários e autoriza aplicações de terceiros.
                            </h3>
                            <h3 class="about-access">
                                Já é cadastrado? <a href="{{route('login')}}">login</a>. Ainda não é cadastrado?
                                <a href="{{route('register')}}">registrar</a> =)
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
