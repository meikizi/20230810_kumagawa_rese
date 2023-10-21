@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_list.css') }}">
<link rel="stylesheet" href="{{ asset('css/rate.css') }}">
@endsection

@section('content')
    <div class="sort-shop__area">
        <form action="{{ route('shop_list') }}" method="get">
            @csrf
            <select class="select--sort" onchange="submit(this.form)" name="sort">
                <option value="" selected hidden>並び替え：評価高/低</option>
                <option value="random">ランダム</option>
                <option value="desc">評価が高い順</option>
                <option value="asc">評価が低い順</option>
            </select>
        </form>
    </div>
    <div class="search-shop__area">
        <form class="search-form" action="{{ route('shop_list') }}" method="get">
            @csrf
            <div class="form--select">
                <select name="area" class="select--area">
                    <option value="">All area</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->area }}">{{ $area->area }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form--select">
                <select name="genre" class="select--genre">
                    <option value="">All genere</option>
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->genre }}">{{ $genre->genre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form--input">
                <span class="material-symbols-outlined search">search</span>
                <input type="search" class="input-search" name="name" placeholder="Search ..." maxlength="191">
            </div>
        </form>
    </div>
    <div class="shop-list__container">
        @isset ($shops)
        <ul class="item-list">
            @foreach ($shops as $shop)
            <li class="item">
                @isset ($shop_images)
                    @foreach ($shop_images as $shop_image)
                        @if ($shop_image->shop_id === $shop->id )
                            @isset ($shop_image)
                            <img src="{{ asset($shop_image->path) }}" alt="店舗のイメージ画像" class="image">
                            @endisset
                        @else
                            @if ($shop->path !== null && $shop->path !== "")
                                <img src="{{ asset("storage/images/{$shop->path}") }}" alt="店舗のイメージ画像" class="image">
                            @else
                                @if ($shop->genre === '寿司')
                                    <img src="{{ asset('storage/images/sushi.jpg') }}" alt="寿司屋のイメージ画像" class="image">
                                @endif
                                @if ($shop->genre === '焼肉')
                                    <img src="{{ asset('storage/images/yakiniku.jpg') }}" alt="焼肉店のイメージ画像" class="image">
                                @endif
                                @if ($shop->genre === '居酒屋')
                                    <img src="{{ asset('storage/images/izakaya.jpg') }}" alt="居酒屋のイメージ画像" class="image">
                                @endif
                                @if ($shop->genre === 'イタリアン')
                                    <img src="{{ asset('storage/images/italian.jpg') }}" alt="イタリア料理店のイメージ画像" class="image">
                                @endif
                                @if ($shop->genre === 'ラーメン')
                                    <img src="{{ asset('storage/images/ramen.jpg') }}" alt="ラーメン屋のイメージ画像" class="image">
                                @endif
                            @endif
                            @break
                        @endif
                    @endforeach
                @else
                    @if ($shop->path !== null)
                    <p>{{ $shop->path }}</p>
                        <img src="{{ asset("storage/images/{$shop->path}") }}" alt="店舗のイメージ画像" class="image">
                    @else
                        @if ($shop->genre === '寿司')
                            <img src="{{ asset('storage/images/sushi.jpg') }}" alt="寿司屋のイメージ画像" class="image">
                        @endif
                        @if ($shop->genre === '焼肉')
                            <img src="{{ asset('storage/images/yakiniku.jpg') }}" alt="焼肉店のイメージ画像" class="image">
                        @endif
                        @if ($shop->genre === '居酒屋')
                            <img src="{{ asset('storage/images/izakaya.jpg') }}" alt="居酒屋のイメージ画像" class="image">
                        @endif
                        @if ($shop->genre === 'イタリアン')
                            <img src="{{ asset('storage/images/italian.jpg') }}" alt="イタリア料理店のイメージ画像" class="image">
                        @endif
                        @if ($shop->genre === 'ラーメン')
                            <img src="{{ asset('storage/images/ramen.jpg') }}" alt="ラーメン屋のイメージ画像" class="image">
                        @endif
                    @endif
                @endisset
                <div class="item__content">
                    <h2 class="shop-name">{{ $shop->name }}</h2>
                    <div class="category">
                        <p class="category--area">#{{ $shop->area }}</p>
                        <p class="category--genre">#{{ $shop->genre }}</p>
                    </div>
                    @isset ($rate_averages)
                        <div class="rate__area">
                            @foreach ($rate_averages as $rate_average)
                                @if ($rate_average['shop_id'] === $shop->id)
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
                                        @if ($reviews_count['shop_id'] === $shop->id)
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
                        @if (in_array($shop->id, $book_marks))
                            <a href="/unbookmark?id={{ $shop->id }}" class="material-symbols-rounded bookmark">
                                favorite
                            </a>
                        @else
                            <a href="/bookmark?id={{ $shop->id }}" class="material-symbols-rounded unbookmark">
                                favorite
                            </a>
                        @endif
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        @endisset
    </div>
    <div class="top">
        <a href="#">
            <span class="material-symbols-outlined arrow-upward">
                arrow_upward
            </span>
        </a>
    </div>
@endsection
