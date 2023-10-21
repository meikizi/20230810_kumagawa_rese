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
use App\Models\Role;
use App\Models\Image;
use App\Models\AccountIcon;
use App\Models\Customer;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\EditReviewRequest;

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

            $book_marks = BookMark::where('user_id', Auth::id())
                ->pluck('shop_id')
                ->toArray();

            $shop_images = Image::with('shops')
                ->get();
            $exists = Image::with('shops')
                ->exists();

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

            if ($request->area || $request->genre || $request->name) {
                // 店舗検索
                $shops = Shop::AreaSearch($request->area)
                    ->GenreSearch($request->genre)
                    ->NameSearch($request->name)
                    ->get();
            } elseif($request->sort) {
                // 評価順並び替え
                $sort = $request->sort;
                if ($sort === 'random') {
                    $shops = Shop::inRandomOrder()->get()->values();
                } elseif ($sort === 'desc') {
                    // 平均評価を評価が高い順にソート
                    $desc_rates = $rate_averages
                        ->sortByDesc('rate_average')
                        ->values();
                    $desc_ids = $desc_rates
                        ->pluck('shop_id')
                        ->toArray();

                    $placeholder = '';
                    foreach ($desc_ids  as $key => $value) {
                        $placeholder .= ($key == 0) ? '?' : ',?';
                    }
                    // 配列$desc_idsの並び順に並び替える
                    $shops = Shop::whereIn('id', $desc_ids)
                        ->orderByRaw("FIELD(id, $placeholder)", $desc_ids)
                        ->get();
                } elseif ($sort === 'asc') {
                    // 平均評価の評価が無いものを除いて評価が低い順にソート
                    $asc_rates = $rate_averages
                        ->whereNotIn('rate_average', [0])
                        ->sortBy('rate_average')
                        ->values();
                    // 平均評価の評価が無いものを配列に格納
                    $null_rates = $rate_averages
                        ->whereIn('rate_average', [0])
                        ->toArray();
                    // 評価があるものは評価が低い順にソート、平均評価の評価が無いものは最後尾に配置
                    foreach ($null_rates as $null_rate) {
                        $asc_rates = $asc_rates->add($null_rate);
                    }
                    $asc_ids = $asc_rates->pluck('shop_id')
                        ->toArray();

                    $placeholder = '';
                    foreach ($asc_rates as $key => $value) {
                        $placeholder .= ($key == 0) ? '?' : ',?';
                    }

                    // 配列$desc_idsの並び順に並び替える
                    $shops = Shop::whereIn('id', $asc_ids)
                        ->orderByRaw("FIELD(id, $placeholder)", $asc_ids)
                        ->get();
                } else {
                    $shops = Shop::all();
                }
            } else {
                $shops = Shop::all();
            }

            if ($exists) {
                if ($rate_averages->isEmpty()) {
                    return view('shop_list', compact('book_marks', 'areas', 'genres', 'shops', 'shop_images'));
                } else {
                    return view('shop_list', compact('book_marks', 'areas','genres', 'shops', 'shop_images', 'rate_averages', 'reviews_counts'));
                }
            } else {
                if ($rate_averages->isEmpty()) {
                    return view('shop_list', compact('book_marks', 'areas','genres','shops'));
                } else {
                    return view('shop_list', compact('book_marks', 'areas','genres','shops' , 'rate_averages', 'reviews_counts'));
                }
            }

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
        $shop = Shop::find($shop_id);

        $book_mark = BookMark::where('user_id', Auth::id())
            ->where('shop_id', $shop_id)
            ->pluck('shop_id')
            ->first();

        $shop_images = Image::with('shops')
            ->get();

        $exists = Image::with('shops')
            ->exists();

        $rate_average = ShopReview::where('shop_id', $shop_id)
            ->avg('rate');
        $rate_average = round($rate_average, 1);
        $review_count = ShopReview::where('shop_id', $shop_id)
            ->count('rate');

        if ($exists) {
            if (!$rate_average) {
                return view('review', compact('shop','book_mark', 'shop_images'));
            } else {
                return view('review', compact('shop', 'book_mark', 'shop_images', 'rate_average', 'review_count'));
            }
        } else {
            if (!$rate_average) {
                return view('review', compact('shop', 'book_mark'));
            } else {
                return view('review', compact('shop', 'book_mark', 'rate_average', 'review_count'));
            }
        }
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
     * 口コミの編集・削除
     */
    public function edit(EditReviewRequest $request)
    {
        if ($request->has('delete')) {
            // 口コミの削除
            ShopReview::where('user_id', Auth::id())
                ->where('shop_id', $request->shop_id)
                ->delete();
            return back();
        }
        if ($request->has('update')) {
            // 口コミの編集
            $review = ShopReview::where('user_id', Auth::id())
                ->where('shop_id', $request->shop_id);

            $review->update([
                'rate' => $request->rate,
                'review' => $request->review,
            ]);
            return back();
        }
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

        $account_icons = AccountIcon::with('users')
            ->get();
        $rate_average = ShopReview::where('shop_id', $shop_id)
            ->avg('rate');
        $rate_average = round($rate_average, 1);
        $reviews_count = ShopReview::where('shop_id', $shop_id)
            ->count('rate');

        // 管理者だけに口コミ削除ボタン表示
        $admins = Role::find(1);
        $admin_ids = $admins->users->pluck('id')->all();
        foreach ($admin_ids as $admin_id) {
            if ($admin_id === Auth::id()) {
                $admin = User::where('id', $admin_id)
                    ->get();
            } else {
                continue;
            }
        }

        if ($reviews->isEmpty()) {
            return view('review_list', compact('shop'));
        }
        if (isset($admin)) {
            return view('review_list', compact('shop', 'reviews', 'account_icons', 'rate_average','reviews_count', 'admin'));
        }
        return view('review_list', compact('shop', 'reviews', 'account_icons', 'rate_average', 'reviews_count'));
    }

    /**
     * 管理者ユーザーのみ口コミの削除
     */
    public function reviewListEdit(Request $request)
    {
        ShopReview::where('user_id', $request->user_id)
            ->where('shop_id', $request->shop_id)
            ->delete();
        return back();
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

        $user_review = ShopReview::where('user_id', Auth::id())
            ->where('shop_id', $request->shop_id)
            ->first();

        // レビュー投稿が有る場合にレビュー一覧ページを表示
        $account_icons = AccountIcon::with('users')
            ->get();
        $rate_average = ShopReview::where('shop_id', $request->shop_id)
            ->avg('rate');
        $rate_average = round($rate_average, 1);
        $reviews_count = ShopReview::where('shop_id', $request->shop_id)
            ->count('rate');

        // 来店済みのお客様だけにレビュー投稿ボタン表示
        // $customer = Customer::where('user_id', Auth::id())
        //     ->where('shop_id', $request->shop_id)
        //     ->get();

        // 一般利用者だけに口コミ投稿ボタン表示
        $customers = Role::find(3);
        $users_ids = $customers->users->pluck('id')->all();
        foreach ($users_ids as $user_id) {
            if ($user_id === Auth::id()) {
                $customer = User::where('id', $user_id)
                    ->get();
            } else {
                continue;
            }
        }

        if ($reviews->isEmpty()) {
            if (isset($customer)) {
                if (isset($user_review)) {
                    return view('shop_detail', compact('shop', 'customer', 'user_review'));
                }
                return view('shop_detail', compact('shop', 'customer'));
            }
            return view('shop_detail', compact('shop'));
        } else {
            if (isset($customer)) {
                if (isset($user_review)) {
                    return view('shop_detail', compact('shop', 'reviews', 'account_icons', 'rate_average', 'reviews_count', 'customer', 'user_review'));
                }
                return view('shop_detail', compact('shop', 'reviews', 'account_icons', 'rate_average','reviews_count', 'customer'));
            }
            return view('shop_detail', compact('shop', 'reviews', 'account_icons', 'rate_average', 'reviews_count'));
        }
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
