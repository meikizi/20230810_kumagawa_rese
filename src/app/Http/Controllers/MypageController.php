<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Shop;
use App\Models\BookMark;
use App\Models\ShopReview;
use App\Models\Role;
use App\Models\Image;
use App\Models\Customer;
use App\Http\Requests\EditReservationRequest;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\ReviseRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReply;
use Illuminate\Support\Facades\Storage;

class MypageController extends Controller
{
    /**
     * マイページ表示
     */
    public function mypage()
    {
        $shops = Shop::all();
        $user_id = Auth::id();
        $favorite_ids = BookMark::where('user_id', $user_id)
            ->pluck('shop_id')
            ->all();

        // 平均評価と評価数を取得
        $rate_averages = collect();
        $reviews_counts = collect();
        foreach ($favorite_ids as $favorite_id) {
            $rate_averages_lists = [];
            $rate_average = ShopReview::where('shop_id', $favorite_id)
                ->avg('rate');
            $rate_average = round($rate_average, 1);
            $rate_averages_lists['favorite_id'] = $favorite_id;
            $rate_averages_lists['rate_average'] = $rate_average;
            $rate_averages->push($rate_averages_lists);

            $reviews_counts_lists = [];
            $reviews_count = ShopReview::where('shop_id', $favorite_id)
                ->count('rate');
            $reviews_counts_lists['favorite_id'] = $favorite_id;
            $reviews_counts_lists['reviews_count'] = $reviews_count;
            $reviews_counts->push($reviews_counts_lists);
        }


        // 来店済みのお客様を取得
        $customers = collect([]);
        foreach ($favorite_ids as $favorite_id) {
            $customer = Customer::where('user_id', $user_id)
                ->where('shop_id', $favorite_id)
                ->first();
            $customers->add($customer);
        }

        if ($rate_averages->isEmpty()) {
            if ($customers->isEmpty()) {
                return view('my_page', compact('shops', 'favorite_ids'));
            }
            return view('my_page', compact('shops', 'favorite_ids', 'customers'));
        }
        return view('my_page', compact('shops', 'favorite_ids', 'customers', 'rate_averages', 'reviews_counts'));
    }

    /**
     * 予約の編集・削除
     */
    public function edit(EditReservationRequest $request)
    {
        if ($request->has('delete')) {
            // 予約の削除
            User::find(Auth::id())->shops()->detach($request->shop_id);
            return back();
        } else {
            // 予約の編集
            $user = User::find(Auth::id());
            $shop_id = $request->shop_id;

            $user->shops()
                ->detach($shop_id);
            $user->shops()
                ->attach($shop_id,
            [
                'date' => $request->date,
                'time' => $request->time,
                'number' => $request->number,
            ]);
            return back();
        }
    }

    /**
     * マイQRコードページ表示
     */
    public function qrcode()
    {
        $user_id = Auth::id();
        return view('qrcode', compact('user_id'));
    }

    /**
     * 管理者ページ表示
     */
    public function admin()
    {
        $users = User::Paginate(5);
        $shops = Shop::all();
        $shopkeeper = Role::find(2);
        $shopkeepers = $shopkeeper->users->all();
        $image_paths = Storage::files('public/images');
        if (empty($image_paths)) {
            return view('admin', compact('users', 'shops', 'shopkeepers'));
        }
        return view('admin', compact('users', 'shops', 'shopkeepers', 'image_paths'));
    }

    /**
     * 店舗代表者の権限付与
     */
    public function attach(Request $request)
    {
        $shopkeeper = Role::find(2);
        $users_ids = $shopkeeper->users->pluck('id')->all();
        foreach ($users_ids as $users_id)
        {
            // 既に店舗代表者の権限が付与されている場合
            if ($users_id === (int) $request->user_id) {
                return back();
            }
        }

        $admin = Role::find(1);
        $admin_users_ids = $admin->users->pluck('id')->all();
        foreach ($admin_users_ids as $admin_users_id) {
            // 管理者に店舗代表者の権限を与えようとした場合
            if ($admin_users_id === (int) $request->user_id) {
                return back();
            }
        }

        // 店舗代表者の権限付与
        $user = User::find($request->user_id);
        $user->roles()->detach(3);
        $user->roles()
            ->attach($request->role_id, [
                'shop_id' => $request->shop_id,
            ]);
        return back();
    }

    /**
     * 店舗代表者の権限削除
     */
    public function detach(Request $request)
    {
        $shopkeeper = Role::find(2);
        $users_ids = $shopkeeper->users->pluck('id')->all();
        foreach ($users_ids as $users_id) {
            // 店舗代表者の権限が付与されている場合
            if ($users_id === (int) $request->user_id) {
                $user = User::find($request->user_id);
                $role_id = $request->role_id;
                $user->roles()->detach($role_id);
                $user->roles()->attach(3);
            } else {
                continue;
            }
        }
        return back();
    }

    /**
     * メールの自動送信設定 店舗情報登録 画像の保存削除
     */
    public function store(StoreRequest $request)
    {
        if ($request->has('send')) {
            // メールの自動送信設定
            $data = $request->all();
            unset($data['_token']);

            Mail::to($request->email)
                ->send(new ContactReply($data));

            return back()->withInput()->with('sent', '送信完了しました。');
        } elseif ($request->has('import')) {
            // 店舗情報登録
            if ($request->file('csv')->isValid()) {
                if ($request->file('csv')->getClientOriginalExtension() !== "csv") {
                    return back()->with('error_message', '不適切な拡張子です。');
                }
                // csvファイルのアップロードに成功し、拡張子がcsvである場合

                // ヘルパー関数 csvImport()
                // Goodby CSVライブラリーを使用
                // csvファイルを一時保存、文字コードの変更、csvファイルの各データを配列化、バリデーション処理後連想配列で格納
                // バリデーションに失敗した場合、Validatorを返す
                $data = csvImport($request);
                if (!is_array($data)) {
                    // バリデーションに失敗した場合
                    return back()->withErrors($data)->withInput();
                }
                // shopsテーブルに一括保存
                Shop::insert($data);
                return back()->with('success_message', '店舗情報を登録しました。');
            }
            return back()->with('error_message', 'CSVの送信エラーが発生しましたので、送信を中止しました。');

        } elseif ($request->has('upload')) {
            // 画像の保存
            Image::store($request);
            return back()->with('success_upload', '画像を保存しました。');
        } elseif ($request->has('delete')) {
            // 画像の削除
            $image_path = $request->image_path;
            $image_name = substr($image_path, 14);
            Image::whereName($image_name)->delete();
            Storage::delete($image_path);
            return back()->with('success_delete', '画像を削除しました。');
        } else {
            return back();
        }
    }

    /**
     * 店舗代表者ページ表示
     */
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

        $images = Image::where('shop_id', $shop_id)->first();
        // 店舗代表者で店舗を登録している場合
        if ($shop_id) {
            $shop = Shop::find($shop_id);
            $reservations = $shop->users;
            if ($images) {
                return view('shopkeeper', compact('areas', 'genres', 'shop', 'reservations', 'images'));
            }
            return view('shopkeeper', compact('areas', 'genres', 'shop', 'reservations'));
        } else {
            return view('shopkeeper', compact('areas', 'genres'));
        }
    }

    /**
     * 予約の削除、店舗情報の更新・作成
     */
    public function revise(ReviseRequest $request)
    {
        if ($request->has('delete')) {
            // 予約の削除
            $user_id = $request->user_id;
            $shop_id = $request->shop_id;
            User::find($user_id)->shops()->detach($shop_id);
            return back();
        } elseif ($request->has('update')) {
            // 店舗情報の更新
            $shop = Shop::find($request->id);
            $shop->update([
                'name' => $request->name,
                'area' => $request->area,
                'genre' => $request->genre,
                'overview' => $request->overview,
                'path' => $request->path,
            ]);
            return back()->with('success_update', '店舗情報を更新しました。');
        } else {
            // 店舗情報の作成
            $form = $request->all();
            unset($form['_token']);
            Shop::create($form);
            $user_id = Auth::id();
            $user = User::find($user_id);
            $user->roles()->detach(2);
            $shop_id = Shop::orderBy('id', 'desc')->first()->id;
            $user->roles()
            ->attach(2, [
                'shop_id' => $shop_id,
            ]);
            return back()->with('success_create', '店舗情報を作成しました。');
        }
    }

    /**
     * QRコードを読み込んで表示される。来店したお客様の予約情報ページ表示
     */
    public function confirmReservation(Request $request)
    {
        $shopkeeper = Auth::user();
        $shop_id = $shopkeeper->roles->first()->pivot->shop_id;
        $shop = Shop::find($shop_id);
        $reservation = $shop->users->find($request->id);

        // 来店したお客様を登録
        $customer = new Customer();
        $customer->shop_id = $shop_id;
        $customer->user_id = $request->id;
        $customer->save();
        return view('confirm_reservation', compact('shop', 'reservation'));
    }

}
