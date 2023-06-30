@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_list.css') }}">
@endsection

@section('content')
    <div class="header-form">
        <form class="search-form" action="/" method="get">
            @csrf
            <select name="area" class="select--area">
                <option value="">All area</option>
                @foreach ($areas as $area)
                <option value="{{ $area->area }}">{{ $area->area }}</option>
                @endforeach
            </select>
            <select name="genre" class="select--genre">
                <option value="">All genere</option>
                @foreach ($genres as $genre)
                <option value="{{ $genre->genre }}">{{ $genre->genre }}</option>
                @endforeach
            </select>
            <span class="material-symbols-outlined search">
                search
            </span>
            <input type="search" class="input-search" name="name" placeholder="Search ...">
        </form>
    </div>
    <div class="shop-list__container">
        @isset ($items)
        <ul class="item-list">
            @foreach ($items as $item)
            <li class="item">
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
                <div class="item__content">
                    <h2 class="shop-name">{{ $item->name }}</h2>
                    <div class="category">
                        <p class="category--area">#{{ $item->area }}</p>
                        <p class="category--genre">#{{ $item->genre }}</p>
                    </div>
                    <div class="item__inner">
                        <form action="{{ route('shop_detail') }}" method="get">
                            @csrf
                            <input type="hidden" name="shop_id" value="{{ $item->id }}">
                            <button type="submit" class="details">詳しくみる</button>
                        </form>
                        @if(in_array($item->id, $book_marks))
                            <a href="/unbookmark?id={{ $item->id }}" class="material-symbols-rounded bookmark">
                                favorite
                            </a>
                        @else
                            <a href="/bookmark?id={{ $item->id }}" class="material-symbols-rounded unbookmark">
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
@endsection
