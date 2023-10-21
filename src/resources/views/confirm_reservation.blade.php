@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/confirm_reservation.css') }}">
@endsection

@section('content')
    <div class="reservation__container">
        <h2 class="reservation-title">来店したお客様の予約情報の確認</h2>
        @isset ($reservation)
        <div class="reservation__area">
            <div class="reservation__item">
                <p class="reservation__item--title">Name</p>
                <p class="reservation__item--data">{{ $reservation->name }}</p>
            </div>
            <div class="reservation__item">
                <p class="reservation__item--title">Date</p>
                <p class="reservation__item--data">{{ $reservation->pivot->date }}</p>
            </div>
            <div class="reservation__item">
                <p class="reservation__item--title">Time</p>
                <p class="reservation__item--data">{{ substr($reservation->pivot->time, 0, 5) }}</p>
            </div>
            <div class="reservation__item">
                <p class="reservation__item--title">Number</p>
                <p class="reservation__item--data">{{ $reservation->pivot->number }}人</p>
            </div>
        </div>
        @endisset
    </div>
@endsection
