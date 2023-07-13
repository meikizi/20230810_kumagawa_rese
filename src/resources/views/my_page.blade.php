@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/my_page.css') }}">
@endsection

@section('content')
    <div class="mypage__container">
        <h2 class="user-name">{{ auth()->user()->name }}さん</h2>
        <div class="mypage__inner">
            <div class="reservation__content">
                <h3 class="mypage__title">予約状況</h3>
                @foreach (auth()->user()->shops as $shop)
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
                            <form action="{{ route('edit') }}" method="post">
                                @csrf
                                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                <button type="submit" name="delete" class="material-symbols-outlined cancel">
                                    cancel
                                </button>
                            </form>
                        </div>
                    </div>
                    <form action="{{ route('edit') }}" method="post">
                        @csrf
                        <div class="reservation__item">
                            <div class="reservation__item--title">Shop</div>
                            <div class="reservation__item--data">{{ $shop->name }}</div>
                        </div>
                        <div class="reservation__item">
                            <div class="reservation__item--title">Date</div>
                            <input name="date" type="text" class="reservation__item--data" value="{{ $shop->pivot->date }}">
                        </div>
                        @error('date')
                        <p class="error-message">{{ $errors->first('date') }}</p>
                        @enderror
                        <div class="reservation__item">
                            <div class="reservation__item--title">Time</div>
                            <input name="time" type="text" class="reservation__item--data" value="{{ substr($shop->pivot->time, 0, 5) }}">
                        </div>
                        @error('time')
                        <p class="error-message">{{ $errors->first('time') }}</p>
                        @enderror
                        @error('date_time')
                        <p class="error-message">{{ $errors->first('date_time') }}</p>
                        @enderror
                        <div class="reservation__item">
                            <div class="reservation__item--title">Number</div>
                            <input name="number" type="text" class="reservation__item--data number" value="{{ $shop->pivot->number }}">人
                        </div>
                        @error('number')
                        <p class="error-message">{{ $errors->first('number') }}</p>
                        @enderror
                        <button type="submit" class="update"></button>
                    </form>
                </div>
                @endforeach
            </div>
            <div class="favorite__content">
                <h3 class="mypage__title">お気に入り店舗</h3>
                <div class="favorite__area">
                <ul class="item-list">
                    @foreach ($items as $item)
                        @if(in_array($item->id, $book_marks))
                            <li class="item">
                                @if ($item->genre === '寿司')
                                <img src="{{ asset('storage/images/sushi.jpg')}}" alt="寿司屋のイメージ画像" class="image">
                                @endif
                                @if ($item->genre === '焼肉')
                                <img src="{{ asset('storage/images/yakiniku.jpg')}}" alt="焼肉店のイメージ画像" class="image">
                                @endif
                                @if ($item->genre === '居酒屋')
                                <img src="{{ asset('storage/images/izakaya.jpg')}}" alt="居酒屋のイメージ画像" class="image">
                                @endif
                                @if ($item->genre === 'イタリアン')
                                <img src="{{ asset('storage/images/italian.jpg')}}" alt="イタリア料理店のイメージ画像" class="image">
                                @endif
                                @if ($item->genre === 'ラーメン')
                                <img src="{{ asset('storage/images/ramen.jpg')}}" alt="ラーメン屋のイメージ画像" class="image">
                                @endif
                                <div class="item__content">
                                    <h2 class="shop-name">{{ $item->name }}</h2>
                                    <p class="category">#{{ $item->area }} #{{ $item->genre }}</p>
                                    <div class="item__inner">
                                        <form action="{{ route('shop_detail') }}" method="get">
                                            @csrf
                                            <input type="hidden" name="shop_id" value="{{ $item->id }}">
                                            <button type="submit" class="details">詳しくみる</button>
                                        </form>
                                        <a href="/unbookmark?id={{ $item->id }}" class="material-symbols-rounded bookmark">
                                            favorite
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endif
                        @endforeach
                </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
