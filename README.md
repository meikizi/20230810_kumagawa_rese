# Rese（リーズ）

PHP のフレームワーク Laravel を使っての企業のグループ会社の飲食店予約サービスです。
Laravel の Auth 認証機能を利用し、メールによる二段階認証を行います。
1 段階目：仮登録画面でメールアドレスを入力することで、そのアドレス宛に本登録の URL が記載されたメールが送信されます。
この状態では仮登録扱いとなり、ウェブアプリの機能を利用することはできません。
2 段階目：1 段階目で送信されたメールに記載されている URL から本登録画面へアクセスします。
そこで本登録に必要な情報を入力することで本登録が完了します。

テスト用メールサーバー mailtrap を使用して実装しています。

メールによる二段階認証後のユーザーのみがログイン出来る機能は、動作確認が行いづらい可能性を考慮して、
コメントアウトしております。

検索機能：飲食店一覧ページで、地域・ジャンル・店名で検索することが出来ます。

お気に入り機能：飲食店一覧ページで、飲食店お気に入りの追加、
　　　利用者毎のマイページで、飲食店お気に入りの削除することが出来ます。

予約機能：飲食店詳細ページで、その店舗の予約を行うことが出来ます。
　　　　　利用者毎のマイページで、予約日時または予約人数を変更することが出来ます。

評価機能：飲食店詳細ページで、利用者が店舗を 5 段階評価とコメントが出来ます。
　飲食店詳細ページからレビューの一覧を確認出来ます。

レスポンシブデザイン：スマートフォン用のレスポンシブデザイン、ブレイクポイントは 768p。

管理画面：管理者と店舗代表者と利用者の 3 つの権限。
　店舗代表者が店舗情報の作成、更新と予約情報の確認・削除が出来ます。
　管理者画面で、管理者側は店舗代表者に権限を付与することが出来ます。
　店舗情報登録済みの店舗の店舗代表者は、店舗を選択後付与することで、店舗情報登録済みの店舗の更新するが出来ます。

最初に登録を行ったアカウントに管理者権限を付与するように実装しています。

お知らせメール機能：管理者画面から利用者にお知らせメールを送信することが出来ます。

ストレージ：管理者画面で、お店の画面をストレージに保存することが出来ます。

リマインダー：タイムスケジューラーを利用して、予約当日の朝 8 時に予約確認のメールを自動で送ることが出来ます。

QR コード：利用者が来店した際に MY QR Code ページで店舗側に見せる QR コードを発行し、QR コードを読み込みことで来店したお客様の予約情報ページ表示

決済機能：Stripe を利用して決済をすることが出来ます。

Pro 入会時コーティングテスト追加機能

口コミ機能：
新規口コミ追加：飲食店詳細ページで、一般ユーザーは店舗に対して 5 段階評価とコメントと画像の口コミを追加することが出来ます。店舗ユーザーと管理者ユーザーを出来ません。
口コミ編集：一般ユーザーは自身が追加した口コミの内容を編集することが出来ます。店舗ユーザーと管理者ユーザーを出来ません。
口コミ削除：一般ユーザーは自身が追加した口コミを削除することが出来ます。管理ユーザーは全ての口コミを削除することが出来ます。

店舗一覧ソート機能：一般ユーザーは店舗一覧を「ランダム、評価が高い順、評価が低い順」で並び替えることが出来ます。

CSV インポート機能：管理ユーザーは csv をインポートすることで、店舗情報を追加することが出来ます。

< --- トップ画面の画像 --- >

![スクリーンショット 2023-10-21 164208](https://github.com/meikizi/20230810_kumagawa_rese/assets/126636201/255f4c67-356f-4fa1-ae6e-017f00f1d186)

## 作成した目的

PHP のフレームワーク Laravel を学ぶために作成しました。

## アプリケーション URL

URL: http://43.207.229.54/

## 機能一覧

・　会員登録とメールによる二段階認証
・　ログイン ・ ログアウト機能
・　飲食店検索機能
・　お気に入り機能
・　飲食店予約機能
・　評価機能
・　レスポンシブデザイン
・　お知らせメール機能
・　リマインダー
・　 QR コード
・　決済機能
---Pro 入会時コーティングテスト追加機能---
・　口コミ機能
・　店舗一覧ソート機能
・　 CSV インポート機能

## 使用技術(実行環境)

言語 : PHP 7.4
フレームワーク : Laravel 8.83.27
ウェブサーバー : Apache/2.4.57
MySQL 8.0 (RDS)

## テーブル設計

![スクリーンショット 2023-10-21 163813](https://github.com/meikizi/20230810_kumagawa_rese/assets/126636201/8e515523-31a4-4fd6-baee-894b1da0511a)

## ER 図

![スクリーンショット 2023-10-21 163106](https://github.com/meikizi/20230810_kumagawa_rese/assets/126636201/69cd83c3-7e4f-484a-88a6-60b133b6844e)

# 環境構築

.env ファイルを編集

デフォルトでは価格の単価が usd なので、 jpy を設定

CASHIER_CURRENCY=jpy

ライブラリーが上手く読み込めないことがあるので
class を再読込
composer dump-autoload
キャッシュを消して再インストール
rm -Rf vendor/
rm composer.lock
composer clear-cache
composer install

メールが送れるように
MAIL\_ の箇所を、　https://harublog.org/programming/laravel-email_authentication/　
の.env ファイルの変更 を参考に、修正

## CSV ファイルの記述方法

Excel 等で作成し、一列目にはセル毎に保存したいテーブルのカラム名を入力してください。二列目にはセル毎にカラムの値を入力してください。
店舗名は 50 文字以内
地域は「東京都」「大阪府」「福岡県」のいずれか
ジャンルは「寿司」「焼肉」「イタリアン」「居酒屋」「ラーメン」のいずれか
店舗概要は 400 文字以内
画像 URL は管理者ページで店舗画像保存が可能なので、保存したファイル名を拡張子「jpeg」「png」のいずれかを含めて入力してください。
保存したファイル名一覧は削除する画像を選択から確認も可能です。

< --- CSV ファイル記述例 --- >

![スクリーンショット 2023-10-21 145728](https://github.com/meikizi/20230810_kumagawa_rese/assets/126636201/cde68f0c-080c-4c1c-a554-8602699f1706)
