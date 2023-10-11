@extends('layouts.app')

@section('script')
<script src="{{ asset('js/display_reservation.js') }}"></script>
<script src="{{ asset('js/display_rates.js') }}"></script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
<link rel="stylesheet" href="{{ asset('css/rate.css') }}">
@endsection

@section('content')
    <div class="shop-detail__container">
        <div class="shop-detail__area">
            <div class="shop-detail__title">
                <a class="prev" href="{{ route('shop_list') }}">&lt;</a>
                @isset ($shop)
                <h2 class="shop-name">{{ $shop->name }}</h2>
            </div>
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
            <p class="category">#{{ $shop->area }} #{{ $shop->genre }}</p>
            <div class="rate__area">
                @isset($reviews)
                    @if ($rete_average == 0)
                        <span class="rate-average" data-rate="0"></span>
                    @elseif ($rete_average > 0 && $rete_average < 1)
                        <span class="rate-average" data-rate="0.5"></span>
                    @elseif ($rete_average == 1)
                        <span class="rate-average" data-rate="1"></span>
                    @elseif ($rete_average > 1 && $rete_average < 2)
                        <span class="rate-average" data-rate="1.5"></span>
                    @elseif ($rete_average == 2)
                        <span class="rate-average" data-rate="2"></span>
                    @elseif ($rete_average > 2 && $rete_average < 3)
                        <span class="rate-average" data-rate="2.5"></span>
                    @elseif ($rete_average == 3)
                        <span class="rate-average" data-rate="3"></span>
                    @elseif ($rete_average > 3 && $rete_average < 4)
                        <span class="rate-average" data-rate="3.5"></span>
                    @elseif ($rete_average == 4)
                        <span class="rate-average" data-rate="4"></span>
                    @elseif ($rete_average > 4 && $rete_average < 5)
                        <span class="rate-average" data-rate="4.5"></span>
                    @elseif ($rete_average == 5)
                        <span class="rate-average" data-rate="5"></span>
                    @endif
                    <span class="rate-average-value">{{ $rete_average }}</span>
                    <a class="open-reviews"> ( <span class="reviews-count">{{ $reviews_count }}件</span> )</a>
                @endisset
                @isset ($customer)
                    <form class="form--post-review" action="{{ route('review') }}" method="get">
                        @csrf
                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                        <button  class="post-review">レビュー投稿</button>
                    </form>
                @endisset
            </div>
            <p class="about">{{ $shop->overview }}</p>
            @endisset
        </div>
        @isset($reviews)
        <div class="review__area" id="review">
            <div class="review__inner">
                <div class="review__header">
                    <h2 class="review-title">レビューの一覧</h2>
                    <button class="close-review material-symbols-outlined" id="close_review">
                        close
                    </button>
                </div>
                @foreach ($reviews as $review)
                    <div class="review__content">
                        <div class="user-area">
                            @isset ($account_icons)
                                @foreach ($account_icons as $account_icon)
                                    @if ($account_icon->user_id === $review->user_id)
                                        <img src="{{ Storage::url($account_icon->path) }}" alt="プロフィール画像" class="user-icon">
                                        @break
                                    @endif
                                @endforeach
                            @endisset
                            <img src="{{ asset('storage/images/default_icon.png') }}" alt="デフォルトのプロフィール画像" class="user-icon--default">
                            <p class="reviewer">{{ $review->user_name }}</p>
                        </div>
                        <p class="updated_at">
                            投稿日：<strong>{{$review->created_at->diffForHumans()}}</strong>
                        </p>
                        @switch($review->rate)
                            @case(1)
                                <p class="rate">
                                    <span class="rated">★</span><span class="unrated">★★★★</span>
                                    <span class="rate-value">1</span>
                                </p>
                                @break
                            @case(2)
                                <p class="rate">
                                    <span class="rated">★★</span><span class="unrated">★★★</span>
                                    <span class="rate-value">2</span>
                                </p>
                                @break
                            @case(3)
                                <p class="rate">
                                    <span class="rated">★★★</span><span class="unrated">★★</span>
                                    <span class="rate-value">3</span>
                                </p>
                                @break
                            @case(4)
                                <p class="rate">
                                    <span class="rated">★★★★</span><span class="unrated">★</span>
                                    <span class="rate-value">4</span>
                                </p>
                                @break
                            @case(5)
                                <p class="rate">
                                    <span class="rated">★★★★★</span>
                                    <span class="rate-value">5</span>
                                </p>
                                @break
                            @default
                        @endswitch
                        <p class="review__comment">{!! nl2br(e($review->review)) !!}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endisset
        <div class="reservation__area">
            <div class="reservation__content">
                <h2 class="reservation__title">予約</h2>
                <form id="reservation" action="{{ route('reservation') }}" method="post">
                    @csrf
                    @isset ($shop)
                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    @else
                        <input type="hidden" name="shop_id" value="">
                    @endisset
                    <div class="form--input">
                        <input name="date" type="date" class="input--date" id="input_date">
                        @error('date')
                            <p class="error-message">{{ $errors->first('date') }}</p>
                        @enderror
                    </div>
                    <div class="form--select">
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
                    <div class="form--select">
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
                        @isset ($shop)
                        <input class="reservation__item--data" value="{{ $shop->name }}">
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
