<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'name',
        'path',
    ];

    public static function store($request)
    {
        $img = $request->file('image');

        if (isset($img)) {
            $dir = 'images';

            // アップロードされたファイル名を取得
            $file_name = $img->getClientOriginalName();

            // imagesディレクトリに画像を保存
            $path = $img->storeAs('public/' . $dir, $file_name);

            if ($path) {
                // ファイル情報をDBに保存
                $image = new Image();
                $image->shop_id = $request->shop_id;
                $image->name = $file_name;
                $image->path = 'storage/' . $dir . '/' . $file_name;
                $image->save();
            }
        }
    }

    public function shops()
    {
        return $this->belongsTo(Shop::class);
    }
}
