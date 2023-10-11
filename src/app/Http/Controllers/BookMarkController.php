<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BookMark;

class BookMarkController extends Controller
{
    /**
     * お気に入り追加
     */
    public function bookmark(Request $request)
    {
        $book_mark = new BookMark();
        $book_mark->shop_id = $request->id;
        $book_mark->user_id = Auth::user()->id;
        $book_mark->save();
        return back();
    }

    /**
     * お気に入り削除
     */
    public function unbookmark(Request $request)
    {
        $user = Auth::user()->id;
        $book_mark = BookMark::where('shop_id', $request->id)
            ->where('user_id', $user)
            ->first();
        $book_mark->delete();
        return back();
    }
}
