@extends('layouts.app')

@section('script')
<script src="{{ asset('js/display_rates.js') }}"></script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/my_page.css') }}">
<link rel="stylesheet" href="{{ asset('css/rate.css') }}">
@endsection

@section('content')
    <div class="mypage__container">
        <h2 class="user-name">{{ auth()->user()->name }}様</h2>
        <div class="mypage__inner">
            <div class="reservation__content">
                <h3 class="mypage-title">予約状況</h3>
                @foreach (auth()->user()->shops as $shop)
                    <div class="reservation__area">
                        <div class="reservation__header">
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
                                    <button type="submit" name="delete" class="material-symbols-outlined cancel" onclick='return confirm("本当に削除しますか？")'>
                                        cancel
                                    </button>
                                </form>
                            </div>
                        </div>
                        <form action="{{ route('edit') }}" method="post">
                            @csrf
                            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                            <div class="reservation-item">
                                <div class="reservation-item--title">Shop</div>
                                <div class="reservation-item--data">{{ $shop->name }}</div>
                            </div>
                            <div class="reservation-item">
                                <label for="date" class="reservation-item--title">Date</label>
                                <input name="date" type="text" class="reservation-item--data" id="date" value="{{ $shop->pivot->date }}">
                            </div>
                            @error('date')
                                <p class="error-message">{{ $errors->first('date') }}</p>
                            @enderror
                            <div class="reservation-item">
                                <label for="time" class="reservation-item--title">Time</label>
                                <input name="time" type="text" class="reservation-item--data" id="time" value="{{ substr($shop->pivot->time, 0, 5) }}">
                            </div>
                            @error('time')
                                <p class="error-message">{{ $errors->first('time') }}</p>
                            @enderror
                            @error('date_time')
                                <p class="error-message">{{ $errors->first('date_time') }}</p>
                            @enderror
                            <div class="reservation-item">
                                <label for="number" class="reservation-item--title">Number</label>
                                <input name="number" type="text" class="reservation-item--data number" id="number" value="{{ $shop->pivot->number }}">人
                            </div>
                            @error('number')
                                <p class="error-message">{{ $errors->first('number') }}</p>
                            @enderror
                            <button type="submit" name="update" class="update"></button>
                        </form>
                    </div>
                @endforeach
            </div>
            <div class="favorite__content">
                <h3 class="mypage-title">お気に入り店舗</h3>
                <div class="favorite__area">
                <ul class="item-list">
                    @foreach ($shops as $shop)
                        @if(in_array($shop->id, $favorite_ids))
                            <li class="item">
                                @if ($shop->genre === '寿司')
                                <img src="{{ asset('storage/images/sushi.jpg')}}" alt="寿司屋のイメージ画像" class="image">
                                @endif
                                @if ($shop->genre === '焼肉')
                                <img src="{{ asset('storage/images/yakiniku.jpg')}}" alt="焼肉店のイメージ画像" class="image">
                                @endif
                                @if ($shop->genre === '居酒屋')
                                <img src="{{ asset('storage/images/izakaya.jpg')}}" alt="居酒屋のイメージ画像" class="image">
                                @endif
                                @if ($shop->genre === 'イタリアン')
                                <img src="{{ asset('storage/images/italian.jpg')}}" alt="イタリア料理店のイメージ画像" class="image">
                                @endif
                                @if ($shop->genre === 'ラーメン')
                                <img src="{{ asset('storage/images/ramen.jpg')}}" alt="ラーメン屋のイメージ画像" class="image">
                                @endif
                                <div class="item__content">
                                    <h2 class="shop-name">{{ $shop->name }}</h2>
                                    <p class="category">#{{ $shop->area }} #{{ $shop->genre }}</p>
                                    @isset ($rate_averages)
                                        <div class="rate__area">
                                            @foreach ($rate_averages as $rate_average)
                                                @if ($rate_average['favorite_id'] === $shop->id)
                                                    @if ($rate_average['rate_average'] == 0)
                                                        <span class="rate-average" data-rate="0"></span>
                                                    @elseif ($rate_average['rate_average'] > 0 && $rate_average['rate_average'] < 1)
                                                        <span class="rate-average" data-rate="0.5"></span>
                                                    @elseif ($rate_average['rate_average'] == 1)
                                                        <span class="rate-average" data-rate="1"></span>
                                                    @elseif ($rate_average['rate_average'] > 1 && $rate_average['rate_average'] < 2)
                                                        <span class="rate-average" data-rate="1.5"></span>
                                                    @elseif ($rate_average['rate_average'] == 2)
                                                        <span class="rate-average" data-rate="2"></span>
                                                    @elseif ($rate_average['rate_average'] > 2 && $rate_average['rate_average'] < 3)
                                                        <span class="rate-average" data-rate="2.5"></span>
                                                    @elseif ($rate_average['rate_average'] == 3)
                                                        <span class="rate-average" data-rate="3"></span>
                                                    @elseif ($rate_average['rate_average'] > 3 && $rate_average['rate_average'] < 4)
                                                        <span class="rate-average" data-rate="3.5"></span>
                                                    @elseif ($rate_average['rate_average'] == 4)
                                                        <span class="rate-average" data-rate="4"></span>
                                                    @elseif ($rate_average['rate_average'] > 4 && $rate_average['rate_average'] < 5)
                                                        <span class="rate-average" data-rate="4.5"></span>
                                                    @elseif ($rate_average['rate_average'] == 5)
                                                        <span class="rate-average" data-rate="5"></span>
                                                    @endif
                                                    @foreach ($reviews_counts as $reviews_count)
                                                        @if ($reviews_count['favorite_id'] === $shop->id)
                                                            <span class="rate-average-value">{{ $rate_average['rate_average'] }}</span>
                                                            <a href="/reviewList?id={{ $shop->id }}" class="open-reviews"> (<span class="reviews-count">{{ $reviews_count['reviews_count'] }}件</span>)</a>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                    @break
                                                @endif
                                            @endforeach
                                        </div>
                                    @endisset
                                    <div class="item__inner">
                                        <form action="{{ route('shop_detail') }}" method="get">
                                            @csrf
                                            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                            <input type="submit" class="details" value="詳しくみる">
                                        </form>
                                        <a href="/unbookmark?id={{ $shop->id }}" class="material-symbols-rounded bookmark">
                                            favorite
                                        </a>
                                    </div>
                                    @isset ($customers)
                                        @foreach ($customers as $customer)
                                            @isset ($customer->shop_id)
                                                @if ($shop->id === $customer->shop_id)
                                                    <form action="{{ route('review') }}" method="get">
                                                        @csrf
                                                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                                        <button  class="post-review">口コミを投稿</button>
                                                    </form>
                                                @endif
                                            @endisset
                                        @endforeach
                                    @endisset
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
