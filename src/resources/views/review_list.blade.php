@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/rate.css') }}">
<link rel="stylesheet" href="{{ asset('css/review_list.css') }}">
@endsection

@section('content')
    <div class="review-list_container">
        <h2 class="header-title">商品レビュー</h2>
        <div class="review-list_inner">
            <div class="shop-area">
                <div class="shop-about">
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
                    <div class="shop-name">
                        <h3 class="shop-name">{{ $shop->name }}</h3>
                        <p class="category">#{{ $shop->area }} #{{ $shop->genre }}</p>
                    </div>
                </div>
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
                        <span class="rate-average-value">{{ $rete_average }}</span> ( <span>{{ $reviews_count }}件</span> )
                    @endisset
                </div>
            </div>
            @isset($reviews)
            <div class="review__area" id="review">
                <div class="review__inner">
                    <div class="review__header">
                        <h2 class="review-title">レビューの一覧</h2>
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
        </div>
    </div>
@endsection
