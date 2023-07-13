@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review.css') }}">
@endsection

@section('content')
    <div class="review__container" id="review">
        <div class="review__header">
            <h2 class="review-title">レビューの投稿</h2>
            <a href="{{ route('shop_list') }}" class="close-review material-symbols-outlined">
                close
            </a>
        </div>
        <div class="review__content">
            <form action="{{ route('post') }}" method="post">
                @csrf
                @error('exists')
                <p class="error-message">{{ $message }}</p>
                @enderror
                <input type="hidden" name="shop_id" value="{{ $shop_id }}">
                <h3 class="rate">評価</h3>
                <div class="rate-form">
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
                <h3 class="review-content">レビュー内容</h3>
                <textarea class="review" name="review" maxlength="500"></textarea>
                @error('review')
                <p class="error-message">{{ $errors->first('review') }}</p>
                @enderror
                <button type="submit" class="post">投稿する</button>
            </form>
        </div>
    </div>
@endsection
