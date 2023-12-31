@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/qrcode.css') }}">
@endsection

@section('content')
    <div class="qrcode__container">
        <div class="qrcode__content">
            <h2 class="qrcode-title">My QR Code</h2>
            <div id="qrCode" class="qrcode"></div>
            <div id="user_id" style="display: none">{{ $user_id }}</div>
            <div id="url_top" style="display: none">{{ $url_top }}</div>
        </div>
    </div>

    <!-- QR Code Styling -->
    <script src="https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>
    <script>
    const $user_id = document.getElementById('user_id').innerHTML;
    const $url_top = document.getElementById('url_top').innerHTML;
    const $url = $url_top + "/shopkeeper/confirmReservation?id=" + $user_id;
    const qrCode = new QRCodeStyling({
        width: 250,
        height: 250,
        type: "canvas",
        data:  $url,
        image: "",
        qrOptions: {
            errorCorrectionLevel: 'H'
        },
        dotsOptions: {
            color: "#000",
            type: "square"
        },
        cornersSquareOptions:{
            type: "square"
        },
        cornersDotOptions: {
            type: "square"
        },
        backgroundOptions: {
            color: "#fff",
        },
    });


    const $qrCode = document.getElementById('qrCode');
    qrCode.append($qrCode);
    </script>

@endsection
