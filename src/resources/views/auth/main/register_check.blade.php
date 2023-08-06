@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
    <div class="main-register__container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">本会員登録確認</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register.main.registered', ['token' => $email_token]) }}">
                            @csrf

                            <div class="form-group row">
                                <label for="phone_number" class="col-md-4 col-form-label text-md-right">電話番号</label>
                                <div class="col-md-6">
                                    <span class="">{{$user->phone_number}}</span>
                                    <input type="hidden" name="phone_number" value="{{$user->phone_number}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="birthday" class="col-md-4 col-form-label text-md-right">生年月日</label>
                                <div class="col-md-6">
                                    <span class="">{{$user->birthday}}</span>
                                    <input type="hidden" name="birthday" value="{{$user->birthday}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="postcode" class="col-md-4 col-form-label text-md-right">郵便番号</label>
                                <div class="col-md-6">
                                    <span class="">{{$user->postcode}}</span>
                                    <input type="hidden" name="postcode" value="{{$user->postcode}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address" class="col-md-4 col-form-label text-md-right">住所</label>
                                <div class="col-md-6">
                                    <span class="">{{$user->address}}</span>
                                    <input type="hidden" name="address" value="{{$user->address}}">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        本登録
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
