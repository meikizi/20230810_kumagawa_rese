@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register__container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">仮会員登録確認</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('pre_register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">氏名</label>

                            <div class="col-md-6">
                                @isset($path)
                                    <img src="{{ Storage::url($path) }}" alt="プロフィール画像" class="icon">
                                    <input type="hidden" name="path" value="{{ $path }}">
                                @else
                                    <img src="{{ asset('storage/images/default_icon.png') }}" alt="デフォルトのプロフィール画像" class="icon">
                                @endisset
                                <span class="name">{{ $name }}</span>
                                <input type="hidden" name="name" value="{{ $name }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>

                            <div class="col-md-6">
                                <span class="mail material-symbols-rounded">
                                    mail
                                </span>
                                <span>{{ $email }}</span>
                                <input type="hidden" name="email" value="{{ $email }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>

                            <div class="col-md-6">
                                <span class="lock material-symbols-rounded">
                                    lock
                                </span>
                                <span>{{$password_mask}}</span>
                                <input type="hidden" name="password" value="{{ $password }}">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    仮登録
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
