@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="registered__container">
    <div class="card">
        <div class="card-header">仮会員登録完了</div>

        <div class="card-body">
            <p class="text">
                この度は、ご登録いただき、誠にありがとうございます。
            </p>
            <p class="text">
                ご本人様確認のため、ご登録いただいたメールアドレスに、<br>
                本登録のご案内のメールが届きます。
            </p>
            <p class="text">
                そちらに記載されているURLにアクセスし、<br>
                アカウントの本登録を完了させてください。
            </p>
        </div>
    </div>
</div>
@endsection
