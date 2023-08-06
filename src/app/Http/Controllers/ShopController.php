<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Shop;
use App\Models\BookMark;
use App\Models\ShopReview;
use App\Models\AccountIcon;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\ReviewRequest;

class ShopController extends Controller
{
    /**
     * 飲食店一覧ページ表示
     */
    public function index(Request $request)
    {
        if (Auth::user()->status == config('const.USER_STATUS.REGISTER')) {
            $areas = Shop::groupBy('area')
                ->select('area', DB::raw('count(*) as total'))
                ->get();
            $genres = Shop::groupBy('genre')
                ->select('genre', DB::raw('count(*) as total'))
                ->get();

            if ($request) {
                $items = Shop::AreaSearch($request->area)
                    ->GenreSearch($request->genre)
                    ->NameSearch($request->name)
                    ->get();
            } else {
                $items = Shop::all();
            }


            $book_marks = BookMark::where('user_id', Auth::id())->pluck('shop_id')->toArray();


            return view('shop_list', compact('book_marks', 'areas', 'genres', 'items'));
        }
        Auth::logout();
        $error_message = '本登録が完了していません。
        送信されたメールに記載されているURLにアクセスし、アカウントの本登録を完了させてください。';
        return view('auth.login', compact('error_message'));
    }

    /**
     * レビュー投稿ページ表示
     */
    public function review(Request $request)
    {
        $shop_id = $request->shop_id;
        return view('review', compact('shop_id'));
    }

    /**
     * レビュー投稿
     */
    public function post(ReviewRequest $request)
    {
        $exists = ShopReview::where('user_id', Auth::id())
            ->where('shop_id', $request->shop_id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages(['exists' => '既に、この店舗のレビューは投稿済みです。']);
        }

        $review = new ShopReview();
        $review->shop_id = intval($request->shop_id);
        $review->user_id = Auth::id();
        $review->user_name = Auth::user()->name;
        $review->rate = $request->rate;
        $review->review = $request->review;
        $review->save();
        return view('reviewed');
    }

    /**
     * 飲食店詳細ページ
     */
    public function detail(Request $request)
    {
        $id = $request->shop_id;
        $item = Shop::find($id);
        $reviews = ShopReview::where('shop_id', $request->shop_id)->get();
        if ($reviews->isEmpty()) {
            return view('shop_detail', compact('item'));
        }
        $account_icons = AccountIcon::all();
        return view('shop_detail', compact('item', 'reviews', 'account_icons'));
    }

    /**
     * 店舗予約
     */
    public function reservation(ReservationRequest $request)
    {
        $id = Auth::id();
        $user = User::find($id);
        $user->shops()
            ->attach($request->shop_id, [
                'date' => $request->date,
                'time' => $request->time,
                'number' => $request->number,
            ]);
        return view('done');
    }

}
