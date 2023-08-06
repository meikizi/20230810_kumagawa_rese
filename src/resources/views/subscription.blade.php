@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/stripe.css') }}">
@endsection

@section('content')
<div class="stripe__container">
    <h3 class="stripe__title">ご登録フォーム</h3>

    <form action="{{route('stripe.afterpay')}}" method="post" class="payment-form" id="payment-form">
        @csrf

        {{-- <label class="label">
            サブスクリプション商品
            <select id="plan" name="plan" class="form-control">
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->productName }}</option>
                @endforeach
            </select>
        </label> --}}

        <label class="label">
            カード名義人
            <input type="test" class="input-name" id="card-holder-name" name="name" required>
        </label>

        <label class="label">
            カード番号
            <div class="card-element" id="card-element" name="card_number"></div>
        </label>

        <div id="card-errors" role="alert" style='color:red'></div>

        {{-- JavaScript 側で渡した intent を取得できるように data 属性を利用 --}}
        <button class="btn-primary" id="card-button" data-secret="{{ $intent->client_secret }}">支払う</button>
        {{-- <button class="btn-primary" id="card-button">支払う</button> --}}

    </form>
</div>

<script src="https://js.stripe.com/v3/"></script>

<script>
'use strict';

    window.onload = my_init;
    function my_init() {

        // Configに設定したStripeのAPIキーを読み込む
        const stripe = Stripe('{{ config('services.stripe.pb_key') }}');
        const elements = stripe.elements();

        // インスタンス作成時にカスタムスタイルをオプションに渡す
        var style = {
            base: {
                color: "#32325d",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#aab7c4"
                }
            },
            invalid: {
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        const cardElement = elements.create('card', {style: style, hidePostalCode: true});
        cardElement.mount('#card-element');

        const cardHolderName = document.getElementById('card-holder-name');
        // data 属性に設定した client_secret を取得
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;

        cardButton.addEventListener('click', async (e) => {
            // formのsubmitボタンのデフォルト動作を無効にする
            e.preventDefault();
            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: { name: cardHolderName.value }
                    }
                }
            );

            if (error) {
            // エラー処理
            console.log('error');

            } else {
            // 問題なければ、stripePaymentHandlerへ
            stripePaymentIdHandler(setupIntent.payment_method);
            }
        });
    }

    function stripePaymentIdHandler(paymentMethodId) {
        const form = document.getElementById('payment-form');

        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'paymentMethodId');
        hiddenInput.setAttribute('value', paymentMethodId);
        form.appendChild(hiddenInput);
        // フォームを送信
        form.submit();
    }
</script>
@endsection
