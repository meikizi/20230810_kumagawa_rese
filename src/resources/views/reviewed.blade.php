@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/done.css') }}">
@endsection

@section('content')
<div class="done__container">
    <div class="done__text">
        レビューありがとうございます
    </div>
    <a class="done-button" href="{{ route('my_page') }}">
        戻る
    </a>
</div>

@endsection
