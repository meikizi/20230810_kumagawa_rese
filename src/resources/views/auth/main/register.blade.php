@extends('layouts.app')

@section('script')
<script src="{{ asset('js/concatenation.js') }}"></script>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
    <div class="main-register__container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">本会員登録</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('register.main.check', ['token' => $email_token]) }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="phone_number" class="col-md-4 col-form-label text-md-right">電話番号</label>
                                    <div class="col-md-6">
                                        <input id="phone_number1" type="text"
                                            class="phone-number1 form-control"
                                            name="phone_number1" value="{{ old('phone_number1') }}" onchange="inputNumber()">
                                        <span class="hyphen">-</span>

                                        <input id="phone_number2" type="text"
                                            class="phone-number2 form-control"
                                            name="phone_number2" value="{{ old('phone_number2') }}" onchange="inputNumber()">

                                        <span class="hyphen">-</span>
                                        <input id="phone_number3" type="text"
                                            class="phone-number3 form-control"
                                            name="phone_number3" value="{{ old('phone_number3') }}" onchange="inputNumber()">

                                        <input id="phone_number" class="phone-number" type="text" name="phone_number" value="{{ old('phone_number') }}">
                                    </div>

                                    <div class="form__error">
                                        @error('phone_number')
                                        {{ $message }}
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="birthday" class="col-md-4 col-form-label text-md-right">生年月日</label>
                                    <div class="col-md-6">
                                        <select id="year" class="year" name="year" onchange="selectYmd()">
                                            <option value="">---</option>
                                            {{ My_functions::yearSelect(old('year'),'1900','2023') }}
                                        </select>年

                                        <select id="month" class="month" name="month" onchange="selectYmd()">
                                            <option value="">---</option>
                                            {{ My_functions::monthSelect(old('month')) }}
                                        </select>月

                                        <select id="day" class="day" name="day" onchange="selectYmd()">
                                            <option value="">---</option>
                                            {{ My_functions::daySelect(old('day')) }}
                                        </select>日

                                        <input id="birthday" class="birthday" type="text" name="birthday" value="{{ old('birthday') }}">
                                    </div>
                                    <div class="form__error">
                                        @error('birthday')
                                        {{ $message }}
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="postcode" class="col-md-4 col-form-label text-md-right">郵便番号</label>
                                    <div class="input--postcode col-md-6">
                                        <input id="postcode" type="text" name="postcode"
                                            class="postcode form-control"
                                            onKeyUp="AjaxZip3.zip2addr('postcode', '', 'address', 'address');"
                                            value="{{ old('postcode', session('postcode')) }}" size="8" maxlength="8">
                                        <p class="placeholder">例）123-4567</p>

                                        <div class="form__error">
                                            @error('postcode')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="address" class="col-md-4 col-form-label text-md-right">住所</label>
                                    <div class="input--address col-md-6">
                                        <input id="address" type="text"
                                            class="address form-control"
                                            name="address" value="{{ old('address') }}"
                                            onchange="inputAddress()">
                                        <p class="placeholder">例）東京都渋谷区千駄ヶ谷1-2-3</p>

                                        <div class="form__error">
                                            @error('address')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="building_name" class="col-md-4 col-form-label text-md-right">建物名</label>
                                    <div class="input--building_name col-md-6">
                                        <input
                                            id="building_name" type="text"
                                            class="building_name form-control"
                                            name="building_name" value="{{ old('building_name') }}"
                                            onchange="inputAddress()">
                                        <p class="placeholder">例）千駄ヶ谷マンション101</p>

                                        <div class="form__error">
                                            @error('building_name')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <input id="full_address" class="full-address" type="text" name="full_address" value="{{ old('full_address') }}">

                                <div class="form-group row mb-0">
                                    <button type="submit" class="btn btn-primary">
                                        確認画面へ
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
