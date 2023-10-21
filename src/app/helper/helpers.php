<?php

use App\Models\Shop;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;

/**
 * csvImportが定義済みでない場合に、shopモデルから、コレクション作成
 */
if (!function_exists('csvImport')) {
    function csvImport($request) {
        // CSV ファイル保存
        $tmpname = uniqid("CSVUP_") . "." . $request->file('csv')->guessExtension();
        $request->file('csv')->move(public_path() . "/csv/tmp", $tmpname);
        $tmppath = public_path() . "/csv/tmp/" . $tmpname;
        // Goodby CSVのconfig設定
        $config = new LexerConfig();
        //CharsetをUTF-8に変換
        $config
            ->setFromCharset("SJIS-win")
            ->setToCharset("UTF-8")
            ->setIgnoreHeaderLine(true);
        $lexer = new Lexer($config);

        $datalist = array();

        $interpreter = new Interpreter();
        $interpreter->addObserver(function (array $row) use (&$datalist) {
            // 各列のデータを取得
            $datalist[] = $row;
        });

        // CSVデータをパース
        $lexer->parse($tmppath, $interpreter);

        // TMPファイル削除
        unlink($tmppath);

        $data = array();

        // CSVのデータを配列化
        foreach ($datalist as $key => $value) {
            $shop = array();
            foreach ($value as $k => $v) {
                switch ($k) {
                    case 0:
                        $shop['name'] = $v;
                        break;
                    case 1:
                        $shop['area'] = $v;
                        break;
                    case 2:
                        $shop['genre'] = $v;
                        break;
                    case 3:
                        $shop['overview'] = $v;
                        break;
                    case 4:
                        $shop['path'] = $v;
                        break;
                    default:
                        break;
                }
            }

            // バリデーション処理
            $rules = [
                'name' => ['required', 'string', 'max:50'],
                'area' => ['required', Rule::in(['東京都', '大阪府', '福岡県'])],
                'genre' => ['required', Rule::in(['寿司', '焼肉', 'イタリアン', '居酒屋', 'ラーメン'])],
                'overview' => ['required', 'string', 'max:400'],
                'path' => ['required', 'string', 'regex:/.jpeg$|.png$/']
            ];

            $message = [
                'name.required' => '店舗名を入力してください',
                'name.string' => '店舗名を文字列で入力してください',
                'name.max' => '店舗名を50文字以下で入力してください',
                'area.required' => '地域を入力してください',
                'area.in' => '「東京都」「大阪府」「福岡県」のいずれかの地域を入力してください',
                'genre.required' => 'ジャンルを入力してください',
                'genre.in' => '「寿司」「焼肉」「イタリアン」「居酒屋」「ラーメン」のいずれかを入力してください',
                'overview.required' => '店舗概要を入力してください',
                'overview.string' => '店舗概要を文字列で入力してください',
                'overview.max' => '店舗概要を400文字以下で入力してください',
                'path.required' => '画像URLを入力してください',
                'path.string' => '画像URLを文字列で入力してください',
                'path.regex' => '画像URLに拡張子がjpeg、pngのみ入力してください',
            ];

            $validator = Validator::make($shop, $rules, $message);

            if ($validator->fails()) {
                return $validator;
                break;
            }

            $data[] = $shop;
        }
        return $data;
    }
}
