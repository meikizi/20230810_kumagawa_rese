@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/registered.css') }}">
@endsection

@section('content')
<div class="registered__container">
    <div class="registered__text">
        会員登録ありがとうございます
    </div>
    <a class="login-button" href="{{ route('login') }}">
        ログインする
    </a>
</div>

@endsection
