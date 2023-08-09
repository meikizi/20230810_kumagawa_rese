<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>【Rese】ご予約確認</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <h1>【Rese】ご予約確認</h1>
    <p>{{ $user->name }}様</p>
    <br>
    <p>
        この度は、{{ $shop_name }} をご予約いただき誠にありがとうございます。<br>
        ご予約日の当日になりましたため、お知らせいたします。
        {{ $user->name }}様のご予約内容は以下のとおりです。<br>
        日時：{{ $today }} {{ substr($time, 0, 5) }}<br>
        当日は、下記の店舗にご来店ください。<br>
        住所 : ～～～～<br>
        地図 :（画像やURLを記載）<br>
        アクセス：◯◯線◯◯駅◯番出口より徒歩◯分<br>
        連絡先電話番号：～～～～（担当◯◯）<br>
    </p>
</body>
</html>
