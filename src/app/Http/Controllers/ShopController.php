<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Shop;
use App\Models\BookMark;
use App\Http\Requests\ReservationRequest;
use Carbon\Carbon;

class ShopController extends Controller
{
    public function index(Request $request)
    {
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

    public function detail(Request $request)
    {
        $id = $request->shop_id;
        $item = Shop::find($id);
        $data = [
            $data = 'item'=>$item,
        ];
        return view('shop_detail', $data);
    }

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
