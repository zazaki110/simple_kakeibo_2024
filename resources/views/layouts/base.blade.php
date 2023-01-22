<!DOCTYPE html>
<html lang="ja">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="{{ asset('kakeibo.css') }}">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <!-- <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0"> -->
     <!-- <meta name="viewport" content="width=device-width, initial-scale=0.5,minimum-scale=0.5, maximum-scale=0.5,user-scalable=1.0"> -->
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="{{ asset('boot.js') }}"></script>
    
    <title>@yield('title')</title>
</head>


<body>
<h1>@yield('h1')</h1>

@yield('データ一覧')
</body>
</html>