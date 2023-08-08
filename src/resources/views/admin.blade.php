@extends('layouts.app')

@section('script')
<script src="{{ asset('js/input_file.js') }}"></script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
    <div class="admin__container">
        <div class="admin__inner">
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
                @continue ($user->id === 0)
                <div class="database__data">
                    <p class="database__item">{{ $user['name'] }}</p>
                    @foreach ($user->roles as $role)
                        @if ($role->pivot->role_id === 1)
                            <p class="database__item">管理者</p>
                        @endif
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
        <h3 class="contact__title">お知らせメール送信</h3>
        <div class="admin__contact">
            @if ( Session::has('sent'))
            <div>
                <p class="success-message">{{old('name')}}様宛てのメッセージが{{ session('sent') }}</p>
            </div>
            @endif

            <form action="{{ route('admin.send') }}" method="POST">
                @csrf
                <div class="form__item">
                    <label><span class="label">名前</span>
                        <input type="text" name="name" class="input" value="{{old('name')}}">
                    </label>
                    @error('name')
                        <p class="error-message">{{ $errors->first('name') }}</p>
                    @enderror
                </div>
                <div class="form__item">
                    <label><span class="label">メールアドレス</span>
                        <input type="email" name="email" class="input" value="{{old('email')}}">
                    </label>
                    @error('email')
                        <p class="error-message">{{ $errors->first('email') }}</p>
                    @enderror
                </div>


                <div class="form__item">
                    <label><span class="label">タイトル</span>
                        <input type="text" name="title" class="input" value="{{old('title')}}">
                    </label>
                    @error('title')
                        <p class="error-message">{{ $errors->first('title') }}</p>
                    @enderror
                </div>


                <div class="form__item">
                    <label><span class="label--message">メッセージ</span>
                        <textarea name="message" class="textarea">{{ old('message') }}</textarea>
                    </label>
                    @error('message')
                        <p class="error-message">{{ $errors->first('message') }}</p>
                    @enderror
                </div>

                <div class="btn">
                    <input type="submit" class="submit" name="send" value="送信">
                </div>
            </form>
        </div>
        <div class="admin__upload">
            <h2 class="admin__title">店舗画像保存</h2>
            <form action="{{ route('admin.send') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="upload-image">
                    <label for="file" class="label--image">画像ファイルを選択</label>
                    <input type="file" name="image" class="input--image" id="file">
                    <span class="select-image" id="file_name">選択されていません</span>
                </div>
                @error('image')
                    <p class="error-message">{{ $errors->first('image') }}</p>
                @enderror
                <input type="submit" class="submit--image" name="upload" value="アップロード">
            </form>
            {{-- <form action="{{ route('admin.send') }}" method="POST">
                @csrf

            </form> --}}
        </div>
    </div>
@endsection
