@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
    <div class="admin__container">
        <h1 class="admin__title">管理者画面</h1>
        @isset($users)
        <div class="admin__header">
            <h2 class="header-item">氏名</h2>
            <h2 class="header-item">権限</h2>
            <h2 class="header-item">店舗名</h2>
            <h2 class="header-item">店舗選択</h2>
            <h2 class="header-item">付与</h2>
            <h2 class="header-item">削除</h2>
        </div>
        <div class="admin__content">
            @foreach ($users as $user)
            @continue ($user->id === 1)
            <div class="database__data">
                <p class="database__item">{{ $user['name'] }}</p>
                @foreach ($user->roles as $role)
                    @if ($role->pivot->role_id === 2)
                        <p class="database__item">店舗代表者</p>
                    @endif
                @endforeach
                @foreach ($items as $item)

                    @if ($item->name === $user['name'])
                    @isset($item->pivot->shop_id)
                    <p class="database__item">{{ $shops[$item->pivot->shop_id - 1]->name }}</p>
                    @endisset
                    @endif
                @endforeach
                <form class="form--Authorization" action="{{route('admin.attach')}}" method="post">
                    @csrf
                    @method('put')
                    <input type="hidden" name="user_id" value="{{ $user['id'] }}">
                    <input type="hidden" name="role_id" value="2">
                    <div class="select-wrap">
                        <select name="shop_id" class="select-shop">
                            <option value="">店舗を選択</option>
                            @foreach ($shops as $shop)
                            <option value="{{ $shop['id'] }}">{{ $shop['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn">権限付与</button>
                </form>
                <form class="form--remove" action="{{route('admin.detach')}}" method="post">
                    @csrf
                    @method('put')
                    <input type="hidden" name="user_id" value="{{ $user['id'] }}">
                    <input type="hidden" name="role_id" value="2">
                    <button class="btn">権限削除</button>
                </form>
            </div>
            @endforeach
        </div>
        <div class="paginate">
            @if ($users->hasPages())
                {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
            @else
                <a class="paginate__prev">&lt;</a><a class="current">1</a><a class="paginate__next">&gt;</a>
            @endif
        </div>
        @endisset
    </div>
@endsection
