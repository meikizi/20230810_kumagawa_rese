@extends('layouts.app')

@section('script')
<script src="{{ asset('js/confirm.js') }}"></script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endsection

@section('content')
<div class="payment__container">
    <h3 class="payment__title">ご登録フォーム</h3>

    @if ( Session::has('sent'))
        <p class="success-message">{{ session('sent') }}</p>
    @endif

    <form action="{{route('stripe_paid')}}" method="post" class="payment__form" id="payment_form">
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
            <input type="test" class="input" id="card_holder_name" name="name"  value="{{old('name')}}">
            @error('name')
                <p class="error-message">{{ $errors->first('name') }}</p>
            @enderror
        </label>

        <label class="label">
            カード番号
            <div class="card-element" id="card_element" name="card_number">
            </div>
        </label>

        <div class="error-message" id="card_errors" role="alert">
        </div>

        <button type="button" class="btn" id="confirm_button">
            支払う
        </button>

        <div class="buy-confirm-modal" id="buy_confirm_modal">
            <div class="modal__inner">
                <div class="modal__header">
                    <h4>支払いを確定しますか？</h4>
                    <button type="button" class="close-confirm material-symbols-outlined" id="close_confirm_button">
                        close
                    </button>
                </div>
                <button type="submit" class="btn" id="card_button">支払う</button>
            </div>
        </div>

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
                fontSize: "14px",
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
        cardElement.mount('#card_element');

        const cardHolderName = document.getElementById('card_holder_name');
        const cardButton = document.getElementById('card_button');
        const cardErrors = document.getElementById('card_errors');

        cardButton.addEventListener('click', async (e) => {
            // formのsubmitボタンのデフォルト動作を無効にする
            e.preventDefault();

            // 二重購入対策
            cardButton.classList.add('disable');

            const { paymentMethod, error } = await stripe.createPaymentMethod(
                'card',
                cardElement,
                {
                    billing_details: { name: cardHolderName.value },
                }
            );

            if (error) {
                // エラー処理
                cardErrors.textContent = "カードの情報を入力してください"
            } else {
                // 問題なければ、stripePaymentHandlerへ
                stripePaymentIdHandler(paymentMethod.id);
            }
        });

        const closeConfirmButton = document.getElementById('close_confirm_button');
        closeConfirmButton.addEventListener('click', function () {
            if (cardButton.classList.contains('disable')) {
                cardButton.classList.remove('disable');
            }
        });
    }

    function stripePaymentIdHandler(paymentMethodId) {
        const form = document.getElementById('payment_form');

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
