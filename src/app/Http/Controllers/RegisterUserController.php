<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AccountIcon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\DefiniteRegisterRequest;
use App\Mail\EmailVerification;
use Carbon\Carbon;

class RegisterUserController extends Controller
{

    protected function getRegister()
    {
        return view('auth.register');
    }

    protected function postRegister(RegisterRequest $request)
    {
        // ユーザ登録処理
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        event(new Registered($user));

        // 最初に会員登録した者を管理者に設定
        $id = $user->id;
        if ($id === 1) {
            $user = User::find($id);
            $user->roles()
                ->attach(1);
        } else {
            $user = User::find($id);
            $user->roles()
                ->attach(3);
        }

        // アイコンをデフォルトから変更する場合の処理
        $img = $request->file('image');

        if (isset($img)) {

            $dir = 'images';

            // imagesディレクトリに画像を保存
            $path = $img->store('public/' . $dir);

            if ($path) {
                // ファイル情報をDBに保存
                $image = new AccountIcon();
                $image->path = $path;
                $image->user_id = $user->id;
                $image->save();
            }
        }

        Auth::login($user);

        return redirect('/');
    }

    public function registered(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        // CSRF トークンを再生成して、二重送信対策
        $request->session()->regenerateToken();
        return view('auth.main.registered');
    }

    /**
     * 仮会員登録内容確認
     */
    public function pre_check(RegisterRequest $request)
    {
        //emailだけ指定し、フラッシュデータとして保持
        $request->flashOnly('email');

        $login_data = $request->all();
        // password マスキング
        $login_data['password_mask'] = '******';

        $img = $request->file('image');

        if (isset($img)) {

            $dir = 'images';

            // imagesディレクトリに画像を保存
            $path = $img->store('public/' . $dir);
            return view('auth.pre_register_check', compact('path'))->with($login_data);
        }

        return view('auth.pre_register_check')->with($login_data);
    }

    /**
     * 仮会員登録完了
     */
    public function register(Request $request)
    {
        // ユーザ登録処理
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'email_verify_token' => base64_encode($request['email']),
        ]);

        $email = new EmailVerification($user);
        Mail::to($user->email)->send($email);

        event(new Registered($user));

        // 最初に会員登録した者を管理者に設定
        $id = $user->id;
        if ($id === 1) {
            $user = User::find($id);
            $user->roles()
                ->attach(1);
        }

        // アイコンをデフォルトから変更する場合の処理
        $path = $request->path;

        if ($path) {
            // ファイル情報をDBに保存
            $image = new AccountIcon();
            $image->path = $path;
            $image->user_id = $user->id;
            $image->save();
        }

        return view('auth.pre_registered');
    }

    /**
     * 本会員登録フォーム
     */
    public function showForm(Request $request, $email_token)
    {
        // 使用可能なトークンか
        if (!User::where('email_verify_token', $email_token)->exists()) {
            return view('auth.main.register')->with('message', '無効なトークンです。');
        } else {
            $user = User::where('email_verify_token', $email_token)->first();
            // 本登録済みユーザーか
            // ステータス値は config/const.php で管理
            if ($user->status == config('const.USER_STATUS.REGISTER')) {
                logger("status" . $user->status);
                return view('auth.main.register')->with('message', 'すでに本登録されています。ログインして利用してください。');
            }
            // ユーザーステータスをメール認証済に更新
            $user->status = config('const.USER_STATUS.MAIL_AUTHED');;
            $user->email_verified_at = Carbon::now();

            if ($user->save()) {
                return view('auth.main.register', compact('email_token'));
            } else {
                return view('auth.main.register')->with('message', 'メール認証に失敗しました。再度、メールからリンクをクリックしてください。');
            }
        }
    }

    /**
     * 本会員登録内容確認
     */
    public function mainCheck(DefiniteRegisterRequest $request, $email_token)
    {
        $user = new User();
        $user->phone_number = $request->phone_number;
        $user->birthday = $request->birthday;
        $user->postcode = $request->postcode;
        $user->address = $request->full_address;

        return view('auth.main.register_check', compact('user', 'email_token'));
    }

    /**
     * 本会員登録完了
     */
    public function mainRegister(Request $request, $email_token)
    {
        $user = User::where('email_verify_token', $email_token)->first();
        // ユーザーステータスを本登録済に更新
        $user->status = config('const.USER_STATUS.REGISTER');
        $user->phone_number = $request->phone_number;
        $user->birthday = $request->birthday;
        $user->postcode = $request->postcode;
        $user->address = $request->address;
        $user->save();

        // CSRF トークンを再生成して、二重送信対策
        $request->session()->regenerateToken();

        return view('auth.main.registered');
    }

}
