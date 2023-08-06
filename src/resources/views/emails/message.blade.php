<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reseからのお知らせ</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <h1>{!! nl2br( $data['title'] ) !!}</h1>
    <p>{{ $data['name'] }}様</p>
    <br>
    <p>{!! nl2br( $data['message'] ) !!}</p>
</body>
</html>
