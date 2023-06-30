<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Shop;
use App\Models\BookMark;
use App\Http\Requests\EditReservationRequest;


class MypageController extends Controller
{
    public function mypage()
    {
        $items = Shop::all();
        $book_marks = BookMark::where('user_id', Auth::id())->pluck('shop_id')->toArray();
        return view('my_page', compact('book_marks', 'items'));
    }

    public function edit(EditReservationRequest $request)
    {
        if ($request->has('delete')) {
            $user_id = Auth::id();
            $shop_id = $request->shop_id;
            User::find($user_id)->shops()->detach($shop_id);
            $items = Shop::all();
            $book_marks = BookMark::where('user_id', Auth::id())
                ->pluck('shop_id')
                ->toArray();
            return view('my_page', compact('book_marks', 'items'));
        } else {
            $form = $request->all();
            unset($form['_token']);
            // dd($form);
            $id = Auth::id();
            $user = User::find($id);
            $user->shops()->update([
                'date' => $request->date,
                'time' => $request->time,
                'number' => $request->number,
            ]);
            $user->shops()
                ->sync($request->shop_id, []);
            $items = Shop::all();
            $book_marks = BookMark::where('user_id', Auth::id())
                ->pluck('shop_id')
                ->toArray();
            return view('my_page', compact('book_marks', 'items'));
        }
    }

}
