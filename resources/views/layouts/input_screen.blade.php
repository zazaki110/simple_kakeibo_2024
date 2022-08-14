<!DOCTYPE html>
<html lang="ja">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="{{ asset('kakeibo.css') }}">
<script src="{{ asset('boot.js') }}"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"> -->
    <title>@yield('title')</title>
</head>

{{ csrf_field() }}
<header class="header_main"><div class="header_menu"><a class="tukibetu tukibetu_register" href="{{url('/tarukame_totalling',)}}" >月別データ</a>
    <div class="logo logo_register"> <a href="{{url('/tarukame_home',)}}"><img class="logo_pic logo_pic_register" src="{{ asset('img/mark_yen_okaikei.png') }}" alt="ホームロゴ"></a></div>
    <a class="nyuryoku nyuryoku_register" href="{{url('/register_member',)}}" >入力画面</a><a class="nenbetu nenbetu_register" href="{{url('/tarukame_nenbetu',)}}">年別データ</a><div class="user_name_touroku user_name_touroku_register">こんにちは&nbsp&nbsp{{$UserName}}さん</div>
    <div class="home_name home_name_register">家計簿タルカメ</div>
    <form action="{{ route('logout') }}" method="post">
  @csrf
  <input type="submit" class="logout_bu logout_bu_register" value="ログアウト">
</form>
    </header>
<body class="input_screen_backgrand">

<h2  class="kakeibo_h1">@yield('h1')</h1>
<div class="input_screen">
@yield('家計簿データ登録')
</div>
</body>
</html>