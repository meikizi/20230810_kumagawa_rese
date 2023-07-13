<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Shop;
use App\Models\BookMark;
use App\Http\Requests\EditReservationRequest;
use App\Http\Requests\ReviseRequest;
use App\Models\Role;

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

    public function admin()
    {
        $users = User::Paginate(5);
        $shops = Shop::all();
        $shopkeeper = Role::find(2);
        $items = $shopkeeper->users->all();
        // dd($items);
        return view('admin', compact('users', 'shops', 'items'));
    }

    public function attach(Request $request)
    {
        $user = User::find($request->user_id);
        $user->roles()
            ->attach($request->role_id, [
                'shop_id' => $request->shop_id,
            ]);
        return back();
    }

    public function detach(Request $request)
    {
        $user = User::find($request->user_id);
        $role_id = $request->role_id;
        $user->roles()->detach($role_id);
        return back();
    }

    public function shopkeeper()
    {
        $areas = Shop::groupBy('area')
        ->select('area', DB::raw('count(*) as total'))
        ->get();
        $genres = Shop::groupBy('genre')
        ->select('genre', DB::raw('count(*) as total'))
        ->get();
        $user = Auth::user();
        $shop_id = $user->roles->first()->pivot->shop_id;
        if ($shop_id) {
            $shop = Shop::find($shop_id);
            $reservations = $shop->users;
            return view('shopkeeper', compact('areas', 'genres', 'shop', 'reservations'));
        } else {
            return view('shopkeeper', compact('areas', 'genres'));
        }
    }

    public function revise(ReviseRequest $request)
    {
        if ($request->has('delete')) {
            $user_id = $request->user_id;
            $shop_id = $request->shop_id;
            User::find($user_id)->shops()->detach($shop_id);
            return back();
        } elseif ($request->has('update')) {
            $form = $request->all();
            unset($form['_token']);
            $shop = Shop::find($request->id);
            $shop->update([
                'name' => $request->name,
                'area' => $request->area,
                'genre' => $request->genre,
                'overview' => $request->overview,
            ]);
            return back();
        } else {
            $form = $request->all();
            unset($form['_token']);
            Shop::create($form);
            $user_id = Auth::id();
            $user = User::find($user_id);
            $user->roles()->detach(2);
            $shop_id = Shop::latest('id')->first()->id;
            $user->roles()
            ->attach(2, [
                'shop_id' => $shop_id,
            ]);
            return back();
        }
    }

}
