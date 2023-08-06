<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Shop;
use App\Models\BookMark;
use App\Http\Requests\EditReservationRequest;
use App\Http\Requests\MailSendRequest;
use App\Http\Requests\ReviseRequest;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReply;
use Carbon\Carbon;

class MypageController extends Controller
{
    /**
     * マイページ表示
     */
    public function mypage()
    {
        $items = Shop::all();
        $book_marks = BookMark::where('user_id', Auth::id())
            ->pluck('shop_id')
            ->toArray();
        return view('my_page', compact('book_marks', 'items'));
    }

    /**
     * 予約の編集・削除
     */
    public function edit(EditReservationRequest $request)
    {
        if ($request->has('delete')) {
            // submitボタンのname属性の値がdeleteのときの予約の削除
            $user_id = Auth::id();
            $shop_id = $request->shop_id;
            User::find($user_id)->shops()->detach($shop_id);
            return back();
        } else {
            // 予約の編集
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
            return back();
        }
    }

    /**
     * 管理者ページ表示
     */
    public function admin()
    {
        $users = User::Paginate(5);
        $shops = Shop::all();
        $shopkeeper = Role::find(2);
        $items = $shopkeeper->users->all();
        return view('admin', compact('users', 'shops', 'items'));
    }

    /**
     * 店舗代表者の権限付与
     */
    public function attach(Request $request)
    {
        $shopkeeper = Role::find(2);
        $users_ids = $shopkeeper->users->pluck('id')->toArray();
        foreach ($users_ids as $users_id)
        {
            // 既に店舗代表者の権限が付与されている場合
            if ($users_id === (int) $request->user_id) {
                return back();
            }
        }

        $admin = Role::find(1);
        $admin_users_ids = $admin->users->pluck('id')->toArray();
        foreach ($admin_users_ids as $admin_users_id) {
            // 管理者に店舗代表者の権限を与えようとした場合
            if ($admin_users_id === (int) $request->user_id) {
                return back();
            }
        }

        // 店舗代表者の権限付与
        $user = User::find($request->user_id);
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
        $user = User::find($request->user_id);
        $role_id = $request->role_id;
        $user->roles()->detach($role_id);
        return back();
    }

    /**
     * メールの自動送信設定
     */
    public function send(MailSendRequest $request)
    {
        $data = $request->all();
        // 必要のない_tokenプロパティまで取得してしまうのでunsetメソッドで削除
        unset($data['_token']);

        Mail::to($request->email)
            ->send(new ContactReply($data));

        return back()->withInput()->with('sent', '送信完了しました。');
    }

    /**
     * 店舗代表者ページ表示
     */
    public function shopkeeper()
    {
        // areaカラムの値を重複なしで取得
        $areas = Shop::groupBy('area')
            ->select('area', DB::raw('count(*) as total'))
            ->get();
        // genreカラムの値を重複なしで取得
        $genres = Shop::groupBy('genre')
            ->select('genre', DB::raw('count(*) as total'))
            ->get();
        $user = Auth::user();
        $shop_id = $user->roles->first()->pivot->shop_id;
        // 店舗代表者で店舗を登録している場合
        if ($shop_id) {
            $shop = Shop::find($shop_id);
            $reservations = $shop->users;
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
        // submitボタンのname属性の値がdeleteのときの予約の削除
        if ($request->has('delete')) {
            $user_id = $request->user_id;
            $shop_id = $request->shop_id;
            User::find($user_id)->shops()->detach($shop_id);
            return back();
        // submitボタンのname属性の値がupdateのときの店舗情報の更新
        } elseif ($request->has('update')) {
            $shop = Shop::find($request->id);
            $shop->update([
                'name' => $request->name,
                'area' => $request->area,
                'genre' => $request->genre,
                'overview' => $request->overview,
            ]);
            return back();
        // 店舗情報の作成
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
