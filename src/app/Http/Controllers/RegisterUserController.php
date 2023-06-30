<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;

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

        Auth::login($user);

        // 打刻ページへリダイレクト
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
}
