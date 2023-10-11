# Rese（リーズ）

PHPのフレームワークLaravelを使っての企業のグループ会社の飲食店予約サービスです。
LaravelのAuth認証機能を利用し、メールによる二段階認証を行います。
1段階目：仮登録画面でメールアドレスを入力することで、そのアドレス宛に本登録のURLが記載されたメールが送信されます。
この状態では仮登録扱いとなり、ウェブアプリの機能を利用することはできません。
2段階目：1段階目で送信されたメールに記載されているURLから本登録画面へアクセスします。
そこで本登録に必要な情報を入力することで本登録が完了します。

テスト用メールサーバー mailtrapを使用して実装しています。

メールによる二段階認証後のユーザーのみがログイン出来る機能は、動作確認が行いづらい可能性を考慮して、
コメントアウトしております。

検索機能：飲食店一覧ページで、地域・ジャンル・店名で検索することが出来ます。

お気に入り機能：飲食店一覧ページで、飲食店お気に入りの追加、
	　　　利用者毎のマイページで、飲食店お気に入りの削除することが出来ます。

予約機能：飲食店詳細ページで、その店舗の予約を行うことが出来ます。
　　　　　利用者毎のマイページで、予約日時または予約人数を変更することが出来ます。

評価機能：飲食店詳細ページで、利用者が店舗を5段階評価とコメントが出来ます。
	　飲食店詳細ページからレビューの一覧を確認出来ます。

レスポンシブデザイン：スマートフォン用のレスポンシブデザイン、ブレイクポイントは768p。

管理画面：管理者と店舗代表者と利用者の3つの権限。
	　店舗代表者が店舗情報の作成、更新と予約情報の確認・削除が出来ます。
	　管理者画面で、管理者側は店舗代表者に権限を付与することが出来ます。
	　店舗情報登録済みの店舗の店舗代表者は、店舗を選択後付与することで、店舗情報登録済みの店舗の更新するが出来ます。

最初に登録を行ったアカウントに管理者権限を付与するように実装しています。

お知らせメール機能：管理者画面から利用者にお知らせメールを送信することが出来ます。

ストレージ：管理者画面で、お店の画面をストレージに保存することが出来ます。

リマインダー：タイムスケジューラーを利用して、予約当日の朝8時に予約確認のメールを自動で送ることが出来ます。

決済機能：Stripeを利用して決済をすることが出来ます。

< --- トップ画面の画像 --- >

![スクリーンショット 2023-08-10 093622](https://github.com/meikizi/20230810_kumagawa_rese/assets/126636201/d7cabc20-d5fc-4627-b4e2-a7ead729b027)

## 作成した目的
PHPのフレームワークLaravelを学ぶために作成しました。

## アプリケーションURL
URL: http://18.181.78.44/

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
・　決済機能

## 使用技術(実行環境)
言語 : PHP 7.4
フレームワーク : Laravel 8.83.27
ウェブサーバー : Apache/2.4.57
MySQL 8.0 (RDS)

## テーブル設計

![スクリーンショット 2023-08-09 174905](https://github.com/meikizi/20230810_kumagawa_rese/assets/126636201/88769f37-09f8-4801-9b65-2ebd3e2a0f3d)

## ER図

![スクリーンショット 2023-08-09 175153](https://github.com/meikizi/20230810_kumagawa_rese/assets/126636201/883ac233-d2f2-4563-8a76-fb3e684d0892)

# 環境構築
.env ファイルを編集

デフォルトでは価格の単価が usd なので、 jpy を設定

CASHIER_CURRENCY=jpy

メールが送れるように
MAIL_ の箇所を、　https://harublog.org/programming/laravel-email_authentication/　
の.envファイルの変更 を参考に、修正

## 未実装機能
QRコード：https://www.simplesoftware.io/#/docs/simple-qrcode/ja　を参考に実装を試みました。
パッケージインストール　composer require simplesoftwareio/simple-qrcode　後、エラー：Class 'QrCode' not found　が発生。

yakiniku.jpg のファイルサイズが、3.51MB　でアップロードするファイルの上限サイズ初期値の2MBから変更が出来ませんでしたので、画像表示が出来ませんでした。
ファイルアップロードの上限値の変更、設定ファイル docker/php/php/php.iniを編集、upload_max_filesize = 50M　post_max_size = 50M　を追加
参照してる、php.iniファイルのパス：
Loaded Configuration File => /usr/local/etc/php/php.ini