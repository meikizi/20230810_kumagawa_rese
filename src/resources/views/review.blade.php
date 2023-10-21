@extends('layouts.app')

@section('script')
<script src="{{ asset('js/review.js') }}"></script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/review.css') }}">
<link rel="stylesheet" href="{{ asset('css/rate.css') }}">
@endsection

@section('content')
    <div class="review__container" id="review">
        <div class="shop__content">
            <div class="shop__inner">
                <h2 class="review__header">今回のご利用はいかがでしたか？</h2>
                @isset ($shop)
                    <div class="item">
                        @isset ($shop_image)
                            @if ($shop_image->shop_id === $shop->id)
                                <img src="{{ asset($shop_image->path) }}" alt="店舗のイメージ画像" class="image">
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
                        @endisset
                        <div class="item__content">
                            <h2 class="shop-name">{{ $shop->name }}</h2>
                            <div class="category">
                                <p class="category--area">#{{ $shop->area }}</p>
                                <p class="category--genre">#{{ $shop->genre }}</p>
                            </div>
                            @isset ($rate_average)
                                <div class="rate__area">
                                    @if ($rate_average == 0)
                                        <span class="rate-average" data-rate="0"></span>
                                    @elseif ($rate_average > 0 && $rate_average < 1)
                                        <span class="rate-average" data-rate="0.5"></span>
                                    @elseif ($rate_average == 1)
                                        <span class="rate-average" data-rate="1"></span>
                                    @elseif ($rate_average > 1 && $rate_average < 2)
                                        <span class="rate-average" data-rate="1.5"></span>
                                    @elseif ($rate_average == 2)
                                        <span class="rate-average" data-rate="2"></span>
                                    @elseif ($rate_average > 2 && $rate_average < 3)
                                        <span class="rate-average" data-rate="2.5"></span>
                                    @elseif ($rate_average == 3)
                                        <span class="rate-average" data-rate="3"></span>
                                    @elseif ($rate_average > 3 && $rate_average < 4)
                                        <span class="rate-average" data-rate="3.5"></span>
                                    @elseif ($rate_average == 4)
                                        <span class="rate-average" data-rate="4"></span>
                                    @elseif ($rate_average > 4 && $rate_average < 5)
                                        <span class="rate-average" data-rate="4.5"></span>
                                    @elseif ($rate_average == 5)
                                        <span class="rate-average" data-rate="5"></span>
                                    @endif
                                    <span class="rate-average-value">{{ $rate_average }}</span>
                                    <a href="/reviewList?id={{ $shop->id }}" class="open-reviews"> ( <span class="reviews-count">{{ $review_count }}件</span> )</a>
                                </div>
                            @endisset
                            <div class="item__inner">
                                <form action="{{ route('shop_detail') }}" method="get">
                                    @csrf
                                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                    <button type="submit" class="details">詳しくみる</button>
                                </form>
                                @if ($shop->id === $book_mark)
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
                    </div>
                @endisset
            </div>
        </div>
        <div class="review__content">
            <form action="{{ route('post') }}" id="form_review" method="post" enctype="multipart/form-data">
                @csrf
                @error('exists')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                <div class="rate__area">
                    <h3 class="rate-title">体験を評価してください</h3>
                    <div class="rate-input__area">
                        <span id="star_value" class="star-value"></span>
                        <input id="star5" class="rate-item" type="radio" name="rate" value="5">
                        <label for="star5">★</label>
                        <input id="star4" class="rate-item" type="radio" name="rate" value="4">
                        <label for="star4">★</label>
                        <input id="star3" class="rate-item" type="radio" name="rate" value="3">
                        <label for="star3">★</label>
                        <input id="star2" class="rate-item" type="radio" name="rate" value="2">
                        <label for="star2">★</label>
                        <input id="star1" class="rate-item" type="radio" name="rate" value="1">
                        <label for="star1">★</label>
                    </div>
                    @error('rate')
                        <p class="error-message">{{ $errors->first('rate') }}</p>
                    @enderror
                </div>
                <div class="review__area">
                    <h3 class="review-title">口コミを投稿</h3>
                    <textarea class="review" name="review" maxlength="400" placeholder="カジュアルな夜のお出かけにおすすめのスポット" onkeyup="ShowLength(value);"></textarea>
                    <p id="input_length" class="input-length">0/400 (最高文字数)</p>
                    @error('review')
                        <p class="error-message">{{ $errors->first('review') }}</p>
                    @enderror
                </div>
                <div class="image__area">
                    <h3 class="image-title">画像の追加</h3>
                    <div class="upload-area">
                        <p class="p--click">クリックして写真を追加</p>
                        <p class="p--DragAndDrop">またはドラッグアンドドロップ</p>
                        <input type="file" name="upload_image" id="input-files">
                    </div>
                    @error('upload_image')
                        <p class="error-message">{{ $errors->first('upload_image') }}</p>
                    @enderror
                </div>
            </form>
        </div>
    </div>
    <button type="submit" form="form_review" class="btn">口コミを投稿</button>
@endsection
