@extends('layouts.app')

@section('script')
<script src="{{ asset('js/input_file.js') }}"></script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
    <div class="admin__container">
        <div class="admin__wrapper">
            <h1 class="admin__title">管理者画面</h1>
            @isset($users)
            <div class="admin__outer">
                <div class="admin__inner">
                    <ul class="admin__header">
                        <li class="header-item">氏名</li>
                        <li class="header-item">権限</li>
                        <li class="header-item">店舗名</li>
                        <li class="header-item--select">店舗選択</li>
                        <li class="header-item--authorization">付与</li>
                        <li class="header-item--remove">削除</li>
                    </ul>
                    <div class="admin__content">
                        @foreach ($users as $user)
                            <ul class="database__data">
                                <li class="database__item">{{ $user['name'] }}</li>
                                @foreach ($user->roles as $role)
                                    @if ($role->pivot->role_id === 1)
                                        <li class="database__item">管理者</li>
                                    @elseif ($role->pivot->role_id === 2)
                                        <li class="database__item">店舗代表者</li>
                                    @elseif ($role->pivot->role_id === 3)
                                        <li class="database__item">利用者</li>
                                    @endif
                                @endforeach
                                @foreach ($items as $item)
                                    @if ($item->name === $user['name'])
                                        @isset ($item->pivot->shop_id)
                                            <li class="database__item">{{ $shops[$item->pivot->shop_id - 1]->name }}</li>
                                        @endisset
                                    @endif
                                @endforeach
                                <form class="form--authorization" action="{{route('admin_attach')}}" method="post">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="user_id" value="{{ $user['id'] }}">
                                    <input type="hidden" name="role_id" value="2">
                                    <li class="select-wrap">
                                        <select name="shop_id" class="select-shop">
                                            <option value="">店舗を選択</option>
                                            @foreach ($shops as $shop)
                                                <option value="{{ $shop['id'] }}">{{ $shop['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </li>
                                    <li class="database__item--authorization">
                                        <button class="btn">付与</button>
                                    </li>
                                </form>
                                <form class="form--remove" action="{{route('admin_detach')}}" method="post">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="user_id" value="{{ $user['id'] }}">
                                    <input type="hidden" name="role_id" value="2">
                                    <li class="database__item--remove">
                                        <button class="btn">削除</button>
                                    </li>
                                </form>
                            </ul>
                        @endforeach
                    </div>
                </div>
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
        <h3 class="contact__title">お知らせメール</h3>
        <div class="admin__contact">
            @if ( Session::has('sent'))
            <div>
                <p class="success-message">{{old('name')}}様宛てのメッセージが{{ session('sent') }}</p>
            </div>
            @endif

            <form action="{{ route('admin_send') }}" method="POST">
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
            <h2 class="admin__title">店舗画像保存・削除</h2>
            <p class="success-message">{{ session('success_upload') }}</p>
            <form action="{{ route('admin_send') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="upload-image">
                    <label for="file" class="label-image">画像ファイルを選択</label>
                    <input type="file" name="image" class="input-image" id="file">
                    <span class="select-image" id="file_name">選択されていません</span>
                </div>
                <select name="shop_id" class="select-image">
                    <option value="">店舗を選択</option>
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                    @endforeach
                </select>
                @error('image')
                    <p class="error-message">{{ $errors->first('image') }}</p>
                @enderror
                <input type="submit" class="submit-image" name="upload" value="アップロード">
            </form>
            @isset ($image_paths)
                <p class="success-message">{{ session('success_delete') }}</p>
                <form action="{{ route('admin_send') }}" method="POST" class="form-delete">
                    @csrf
                    <span>削除する画像を選択</span>
                    <select name="image_path" class="select-image">
                        <option value="">-----</option>
                        @foreach ($image_paths as $image_path)
                            <option value="{{ $image_path }}">{{ substr($image_path, 14)}}</option>
                        @endforeach
                    </select>
                    <input type="submit" class="delete-image" name="delete" value="削除">
                    @error('image_path')
                        <p class="error-message">{{ $errors->first('image_path') }}</p>
                    @enderror
                </form>
            @endisset
        </div>
    </div>
@endsection
