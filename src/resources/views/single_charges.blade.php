@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/stripe.css') }}">
@endsection

@section('content')
<div class="stripe__container">
    <h3 class="stripe__title">ご登録フォーム</h3>

    <form action="{{route('stripe.paid')}}" method="post" class="payment-form" id="payment-form">
        @csrf

        <label class="label">
            お支払い金額
            <input type="text" class="input" name="amount" value="{{old('amount')}}">
            @error('amount')
                <p class="error-message">{{ $errors->first('amount') }}</p>
            @enderror
        </label>

        <label class="label">
            カード名義人
            <input type="test" class="input" id="card-holder-name" name="name"  value="{{old('name')}}">
            @error('name')
                <p class="error-message">{{ $errors->first('name') }}</p>
            @enderror
        </label>

        <label class="label">
            カード番号
            <div class="card-element" id="card-element" name="card_number"></div>
        </label>

        <div id="card-errors" role="alert" style='color:red'></div>

        <button class="btn" id="card-button">支払う</button>

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
        const cardButton = document.getElementById('card-button');

        cardButton.addEventListener('click', async (e) => {
            // formのsubmitボタンのデフォルト動作を無効にする
            e.preventDefault();
            const { paymentMethod, error } = await stripe.createPaymentMethod(
                'card',
                cardElement,
                {
                    billing_details: { name: cardHolderName.value },
                }
            );

            if (error) {
            // エラー処理
            console.log('error');

            } else {
            // 問題なければ、stripePaymentHandlerへ
            stripePaymentIdHandler(paymentMethod.id);
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
