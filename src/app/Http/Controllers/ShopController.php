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
use App\Models\Image;
use App\Models\AccountIcon;
use App\Models\Customer;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\ReviewRequest;

class ShopController extends Controller
{
    /**
     * 飲食店一覧ページ表示
     */
    public function index(Request $request)
    {
        // 本登録済みユーザーのみアクセス可
        // if (Auth::user()->status == config('const.USER_STATUS.REGISTER')) {

            $areas = Shop::groupBy('area')
                ->select('area', DB::raw('count(*) as total'))
                ->get();
            $genres = Shop::groupBy('genre')
                ->select('genre', DB::raw('count(*) as total'))
                ->get();

            if ($request) {
                $shops = Shop::AreaSearch($request->area)
                    ->GenreSearch($request->genre)
                    ->NameSearch($request->name)
                    ->get();
            } else {
                $shops = Shop::all();
            }

            $book_marks = BookMark::where('user_id', Auth::id())
                ->pluck('shop_id')
                ->toArray();

            $shop_images = Image::with('shops')
                ->get();

            // 平均評価と評価数を取得
            $shop_ids = Shop::pluck('id');
            $rate_averages = collect();
            $reviews_counts = collect();
            foreach ($shop_ids as $shop_id) {
                $rate_averages_lists = [];
                $rate_average = ShopReview::where('shop_id', $shop_id)
                    ->avg('rate');
                $rate_average = round($rate_average, 1);
                $rate_averages_lists['shop_id'] = $shop_id;
                $rate_averages_lists['rate_average'] = $rate_average;
                $rate_averages->push($rate_averages_lists);

                $reviews_counts_lists = [];
                $reviews_count = ShopReview::where('shop_id', $shop_id)
                    ->count('rate');
                $reviews_counts_lists['shop_id'] = $shop_id;
                $reviews_counts_lists['reviews_count'] = $reviews_count;
                $reviews_counts->push($reviews_counts_lists);
            }

            if ($rate_averages->isEmpty()) {
                return view('shop_list', compact('book_marks', 'areas', 'genres','shops'));
            }
            return view('shop_list', compact('book_marks', 'areas', 'genres','shops', 'shop_images', 'rate_averages', 'reviews_counts'));

        // }
        // Auth::logout();
        // $error_message = '本登録が完了していません。
        // 送信されたメールに記載されているURLにアクセスし、アカウントの本登録を完了させてください。';
        // return view('auth.login', compact('error_message'));

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
     * レビュー一覧ページ
     */
    public function reviewList(Request $request)
    {
        $shop_id = $request->id;
        $shop = Shop::find($shop_id);
        $reviews = ShopReview::where('shop_id', $shop_id)
            ->get();

        // レビュー投稿が有る場合にレビュー一覧ページを表示
        $account_icons = AccountIcon::with('users')
            ->get();
        $rete_average = ShopReview::where('shop_id', $shop_id)
            ->avg('rate');
        $rete_average = round($rete_average, 1);
        $reviews_count = ShopReview::where('shop_id', $shop_id)
            ->count('rate');

        if ($reviews->isEmpty()) {
            return view('review_list', compact('shop'));
        }
        return view('review_list', compact('shop', 'reviews', 'account_icons', 'rete_average', 'reviews_count'));
    }

    /**
     * 飲食店詳細ページ
     */
    public function detail(Request $request)
    {
        $shop_id = $request->shop_id;
        $shop = Shop::find($shop_id);
        $reviews = ShopReview::where('shop_id', $shop_id)
            ->get();

        // レビュー投稿が有る場合にレビュー一覧ページを表示
        $account_icons = AccountIcon::with('users')
            ->get();
        $rete_average = ShopReview::where('shop_id', $request->shop_id)
            ->avg('rate');
        $rete_average = round($rete_average, 1);
        $reviews_count = ShopReview::where('shop_id', $request->shop_id)
            ->count('rate');
        // 来店済みのお客様だけにレビュー投稿ボタン表示
        $customer = Customer::where('user_id', Auth::id())
            ->where('shop_id', $request->shop_id)
            ->get();

        if ($customer->isEmpty()) {
            if ($reviews->isEmpty()) {
                return view('shop_detail', compact('shop'));
            }
            return view('shop_detail', compact('shop', 'reviews', 'account_icons', 'rete_average', 'reviews_count'));
        }
        return view('shop_detail', compact('shop', 'reviews', 'account_icons', 'rete_average', 'reviews_count', 'customer'));
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
