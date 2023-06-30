@extends('layouts.app')

@section('script')
<script src="{{ asset('js/display-value.js') }}"></script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
@endsection

@section('content')
    <div class="shop-detail__container">
        <div class="shop-detail__area">
            <a class="prev" href="{{ route('shop_list') }}">&lt;</a>
            @isset ($item)
            <h2 class="shop-name">{{ $item->name }}</h2>
            @if ($item->genre === '寿司')
            <img src="{{ asset('images/sushi.jpg')}}" alt="寿司屋のイメージ画像" class="image">
            @endif
            @if ($item->genre === '焼肉')
            <img src="{{ asset('images/yakiniku.jpg')}}" alt="焼肉店のイメージ画像" class="image">
            @endif
            @if ($item->genre === '居酒屋')
            <img src="{{ asset('images/izakaya.jpg')}}" alt="居酒屋のイメージ画像" class="image">
            @endif
            @if ($item->genre === 'イタリアン')
            <img src="{{ asset('images/italian.jpg')}}" alt="イタリア料理店のイメージ画像" class="image">
            @endif
            @if ($item->genre === 'ラーメン')
            <img src="{{ asset('images/ramen.jpg')}}" alt="ラーメン屋のイメージ画像" class="image">
            @endif
            <p class="category">#{{ $item->area }} #{{ $item->genre }}</p>
            <p class="about">{{ $item->overview }}</p>
            @endisset
        </div>
        <div class="reservation__area">
            <div class="reservation__content">
                <h2 class="reservation__title">予約</h2>
                <form id="reservation" action="{{ route('reservation') }}" method="post">
                    @csrf
                    @isset ($item)
                    <input type="hidden" name="shop_id" value="{{ $item->id }}">
                    @else
                    <input type="hidden" name="shop_id" value="">
                    @endisset
                    <div class="form-item">
                        <input name="date" type="date" class="input--date" id="input_date">
                        @error('date')
                        <p class="error-message">{{ $errors->first('date') }}</p>
                        @enderror
                    </div>
                    <div class="form-item">
                        <select name="time" class="select--time" id="select_time">
                            <option value="">予約時間を選択</option>
                            <option value="00:00">00:00</option>
                            <option value="01:00">01:00</option>
                            <option value="02:00">02:00</option>
                            <option value="03:00">03:00</option>
                            <option value="04:00">04:00</option>
                            <option value="05:00">05:00</option>
                            <option value="06:00">06:00</option>
                            <option value="07:00">07:00</option>
                            <option value="08:00">08:00</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00">11:00</option>
                            <option value="12:00">12:00</option>
                            <option value="13:00">13:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                            <option value="17:00">17:00</option>
                            <option value="18:00">18:00</option>
                            <option value="19:00">19:00</option>
                            <option value="20:00">20:00</option>
                            <option value="21:00">21:00</option>
                            <option value="22:00">22:00</option>
                            <option value="23:00">23:00</option>
                            <option value="24:00">24:00</option>
                        </select>
                        @error('time')
                        <p class="error-message">{{ $errors->first('time') }}</p>
                        @enderror
                        @error('date_time')
                        <p class="error-message">{{ $errors->first('date_time') }}</p>
                        @enderror
                    </div>
                    <div class="form-item">
                        <select name="number" class="select--number" id="select_number">
                            <option value="">人数を選択</option>
                            <option value="1">1人</option>
                            <option value="2">2人</option>
                            <option value="3">3人</option>
                            <option value="4">4人</option>
                            <option value="5">5人</option>
                            <option value="6">6人</option>
                            <option value="7">7人</option>
                            <option value="8">8人</option>
                            <option value="9">9人</option>
                            <option value="10">10人</option>
                        </select>
                        @error('number')
                        <p class="error-message">{{ $errors->first('number') }}</p>
                        @enderror
                    </div>
                </form>
                <div class="reservation__items">
                    <div class="reservation__item">
                        <div class="reservation__item--title">Shop</div>
                        @isset ($item)
                        <input class="reservation__item--data" value="{{ $item->name }}">
                        @else
                        <input class="reservation__item--data">
                        @endisset
                    </div>
                    <div class="reservation__item">
                        <div class="reservation__item--title">Date</div>
                        <input type="text" class="reservation__item--data" id="date">
                    </div>
                    <div class="reservation__item">
                        <div class="reservation__item--title">Time</div>
                        <input type="text" class="reservation__item--data" id="time">
                    </div>
                    <div class="reservation__item">
                        <div class="reservation__item--title">Number</div>
                        <input type="text" class="reservation__item--data" id="number">
                    </div>
                </div>
            </div>
            <button type="submit" class="reservation__button" form="reservation">予約する</button>
        </div>
    </div>
@endsection
