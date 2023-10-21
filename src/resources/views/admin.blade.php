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
            <h1 class="admin-title">管理者画面</h1>
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
                            <ul class="database-data">
                                <li class="database-item">{{ $user['name'] }}</li>
                                @foreach ($user->roles as $role)
                                    @if ($role->pivot->role_id === 1)
                                        <li class="database-item">管理者</li>
                                    @elseif ($role->pivot->role_id === 2)
                                        <li class="database-item">店舗代表者</li>
                                    @elseif ($role->pivot->role_id === 3)
                                        <li class="database-item">利用者</li>
                                    @endif
                                @endforeach
                                @foreach ($shopkeepers as $shopkeeper)
                                    @if ($shopkeeper->name === $user['name'])
                                        @isset ($shopkeeper->pivot->shop_id)
                                            <li class="database-item">{{ $shops[$shopkeeper->pivot->shop_id - 1]->name }}</li>
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
                                    <li class="database-item--authorization">
                                        <input type="submit" class="btn" value="付与">
                                    </li>
                                </form>
                                <form class="form--remove" action="{{route('admin_detach')}}" method="post">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="user_id" value="{{ $user['id'] }}">
                                    <input type="hidden" name="role_id" value="2">
                                    <li class="database-item--remove">
                                        <input type="submit" class="btn" value="削除" onclick='return confirm("本当に削除しますか？")'>
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
                    <a class="paginate-prev">&lt;</a><a class="current">1</a><a class="paginate-next">&gt;</a>
                @endif
            </div>
            @endisset
        </div>
        <div class="admin__contact">
            <h2 class="admin-title">お知らせメール</h2>
            <div class="contact-form">
                @if ( Session::has('sent'))
                <div>
                    <p class="success-message">{{old('name')}}様宛てのメッセージが{{ session('sent') }}</p>
                </div>
                @endif
                <form action="{{ route('admin_store') }}" method="post">
                    @csrf
                    <div class="form-item">
                        <label><span class="label--title">名前</span>
                            <input type="text" name="name" class="input" value="{{old('name')}}">
                        </label>
                        @error('name')
                            <p class="error-message">{{ $errors->first('name') }}</p>
                        @enderror
                    </div>
                    <div class="form-item">
                        <label><span class="label--title">メールアドレス</span>
                            <input type="email" name="email" class="input" value="{{old('email')}}">
                        </label>
                        @error('email')
                            <p class="error-message">{{ $errors->first('email') }}</p>
                        @enderror
                    </div>


                    <div class="form-item">
                        <label><span class="label--title">タイトル</span>
                            <input type="text" name="title" class="input" value="{{old('title')}}">
                        </label>
                        @error('title')
                            <p class="error-message">{{ $errors->first('title') }}</p>
                        @enderror
                    </div>


                    <div class="form-item">
                        <label><span class="label--message">メッセージ</span>
                            <textarea name="message" class="textarea">{{ old('message') }}</textarea>
                        </label>
                        @error('message')
                            <p class="error-message">{{ $errors->first('message') }}</p>
                        @enderror
                    </div>
                    <input type="submit" class="submit" name="send" value="送信">
                </form>
            </div>
        </div>
        <div class="admin__import">
            <h2 class="admin-title">店舗情報登録</h2>
            <form action="{{ route('admin_store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="import-csv">
                    <label for="csv" class="label--csv">csvファイルを選択</label>
                    <input type="file" name="csv" class="input-csv" id="csv">
                    <label for="csv" class="select-csv" id="csv_name">ファイル選択...</label>
                </div>
                <div class="message">
                    <li class="success-message">{{ session('success_message') }}</li>
                    <li class="error-message">{{ session('error_message') }}</li>
                    @error('name')
                        <li class="error-message">{{$message}}</li>
                    @enderror
                    @error('area')
                        <li class="error-message">{{$message}}</li>
                    @enderror
                    @error('genre')
                        <li class="error-message">{{$message}}</li>
                    @enderror
                    @error('overview')
                        <li class="error-message">{{$message}}</li>
                    @enderror
                    @error('path')
                        <li class="error-message">{{$message}}</li>
                    @enderror
                </div>
                <input type="submit" class="submit" name="import" value="送信">
            </form>
        </div>
        <div class="admin__upload">
            <h2 class="admin-title">店舗画像保存・削除</h2>
            <div class="message">
                <li class="success-message">{{ session('success_upload') }}</li>
            </div>
            <form action="{{ route('admin_store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="upload-image">
                    <label for="file" class="label--image">画像ファイルを選択</label>
                    <input type="file" name="image" class="input-image" id="file">
                    <label for="file" class="select" id="file_name">ファイル選択...</label>
                </div>
                <div class="select-image__area">
                    <select name="shop_id" class="select-image">
                        <option value="">店舗を選択...</option>
                        @foreach ($shops as $shop)
                            <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="message">
                    @error('image')
                        <li class="error-message">{{ $errors->first('image') }}</li>
                    @enderror
                </div>
                <input type="submit" class="submit" name="upload" value="アップロード">
            </form>
            @isset ($image_paths)
                <div class="message">
                    <li class="success-message">{{ session('success_delete') }}</li>
                </div>
                <form action="{{ route('admin_store') }}" method="post" class="form-delete">
                    @csrf
                    <div class="delete-image__area">
                        <span>削除する画像を選択</span>
                        <select name="image_path" class="select-image">
                            <option value="">-----</option>
                            @foreach ($image_paths as $image_path)
                                <option value="{{ $image_path }}">{{ substr($image_path, 14)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="submit" class="delete-image" name="delete" value="削除" onclick='return confirm("本当に削除しますか？")'>
                    <div class="message">
                        @error('image_path')
                            <li class="error-message">{{ $errors->first('image_path') }}</li>
                        @enderror
                    </div>
                </form>
            @endisset
        </div>
    </div>
@endsection
