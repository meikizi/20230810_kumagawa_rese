@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shopkeeper.css') }}">
@endsection

@section('content')
    <div class="shopkeeper__container">
        <h1 class="shopkeeper__title">店舗代表者画面</h1>
        <div class="shopkeeper__inner">
            <div class="reservation__content">
                <h2 class="reservation__title">予約状況</h2>
                @isset ($reservations)
                @foreach ($reservations as $reservation)
                <div class="reservation__area">
                    <div class="reservation-header">
                        <div class="reservation-title">
                            <p class="material-symbols-rounded clock">
                                schedule
                            </p>
                            <p class="reservation-name">
                                予約{{ $loop->iteration }}
                            </p>
                        </div>
                        <div class="reservation-cancel">
                            <form action="{{ route('revise') }}" method="post">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $reservation->id }}">
                                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                <button type="submit" name="delete" class="material-symbols-outlined cancel">
                                    cancel
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="reservation__item">
                        <p class="reservation__item--title">Name</p>
                        <p class="reservation__item--data">{{ $reservation->name }}</p>
                    </div>
                    <div class="reservation__item">
                        <p class="reservation__item--title">Date</p>
                        <p class="reservation__item--data">{{ $reservation->pivot->date }}</p>
                    </div>
                    <div class="reservation__item">
                        <p class="reservation__item--title">Time</p>
                        <p class="reservation__item--data">{{ substr($reservation->pivot->time, 0, 5) }}</p>
                    </div>
                    <div class="reservation__item">
                        <p class="reservation__item--title">Number</p>
                        <p class="reservation__item--data">{{ $reservation->pivot->number }}人</p>
                    </div>
                </div>
                @endforeach
                @endisset
            </div>
            <div class="shop__inner">
                <h2 class="shop__title">店舗情報</h2>
                <div class="shop__content">
                    @isset($shop)
                    <p class="success-message">{{ session('success_update') }}</p>
                    <p class="success-message">{{ session('success_create') }}</p>
                    <form action="{{ route('revise') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $shop->id }}">
                        <div class="shop__item">
                            <label class="label" for="name">店舗名</label>
                            <input class="input" type="text" name="name" value="{{ $shop->name }}">
                        </div>
                        @error('name')
                            <p class="error-message">{{ $errors->first('name') }}</p>
                        @enderror
                        <div class="shop__item">
                            <label class="label" for="area">地域</label>
                            <div class="form--select">
                                <select name="area" class="select">
                                    <option value="{{ $shop->area }}">{{ $shop->area }}</option>
                                    @foreach ($areas as $area)
                                    <option value="{{ $area->area }}">{{ $area->area }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @error('area')
                            <p class="error-message">{{ $errors->first('area') }}</p>
                        @enderror
                        <div class="shop__item">
                            <label class="label" for="genre">ジャンル</label>
                            <div class="form--select">
                                <select name="genre" class="select">
                                    <option value="{{ $shop->genre }}">{{ $shop->genre }}</option>
                                    @foreach ($genres as $genre)
                                    <option value="{{ $genre->genre }}">{{ $genre->genre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @error('genre')
                            <p class="error-message">{{ $errors->first('genre') }}</p>
                        @enderror
                        <div class="shop__item--overview">
                            <label class="label--overview" for="overview">概要</label>
                            <textarea class="overview" name="overview">{{ $shop->overview }}</textarea>
                        </div>
                        @error('overview')
                            <p class="error-message">{{ $errors->first('overview') }}</p>
                        @enderror
                        <div class="button-area">
                            <button type="submit" name="update" class="update">更新</button>
                        </div>
                    </form>
                    @else
                    <form action="{{ route('revise') }}" method="post">
                        @csrf
                        <div class="shop__item">
                            <label class="label" for="name">店舗名</label>
                            <input class="input" type="text" name="name">
                        </div>
                        @error('name')
                            <p class="error-message">{{ $errors->first('name') }}</p>
                        @enderror
                        <div class="shop__item">
                            <label class="label" for="area">地域</label>
                            <div class="form--select">
                                <select name="area" class="select">
                                    <option value=""></option>
                                    @foreach ($areas as $area)
                                    <option value="{{ $area->area }}">{{ $area->area }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @error('area')
                            <p class="error-message">{{ $errors->first('area') }}</p>
                        @enderror
                        <div class="shop__item">
                            <label class="label" for="genre">ジャンル</label>
                            <div class="form--select">
                                <select name="genre" class="select">
                                    <option value=""></option>
                                    @foreach ($genres as $genre)
                                    <option value="{{ $genre->genre }}">{{ $genre->genre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @error('genre')
                            <p class="error-message">{{ $errors->first('genre') }}</p>
                        @enderror
                        <div class="shop__item--overview">
                            <label class="label--overview" for="overview">概要</label>
                            <textarea class="overview" name="overview"></textarea>
                        </div>
                        <div class="button-area">
                            <button  type="submit" name="create" class="create">作成</button>
                        </div>
                        @error('overview')
                            <p class="error-message">{{ $errors->first('overview') }}</p>
                        @enderror
                    </form>
                    @endisset
                </div>
            </div>
        </div>
    </div>
@endsection
