# Flea-Market

## 環境構築

### Dockerビルド
1.「git clone git@github.com:kentarotengchuan/flea-market.git」

2.アプリディレクトリに移動し「sudo cp .env.example .env」

### Laravel環境構築
3.「docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs」を実行してcomposerをインストール。

4.一つ前のディレクトリに戻って「sudo chown -R {Linuxのユーザー名} flea-market」で所有者の変更。

5.再び、flea-marketのディレクトリに戻って「./vendor/bin/sail up -d」を実行し、アプリを立ち上げる。

6.「./vendor/bin/sail artisan key:generate」を実行し、キーを発行する。

7.「./vendor/bin/sail artisan migrate:fresh」を実行し、マイグレーションする。

8.「./vendor/bin/sail artisan db:seed」を実行し、テストユーザーを作成する。

    テストユーザー
        ユーザー名：test

        メールアドレス：test@test.com

        パスワード：hogehoge

    ※ダミー商品全ての出品者

9.「./vendor/bin/sail artisan storage:link」でシンボリックリンクを作成する。

##  URL

・アプリ画面：http://localhost

・Mailpit：http://localhost:8025

・phpMyAdmin：http://localhost:8080

## 使用技術（実行環境）

・　PHP 8.2.26

・　Laravel sail　8.2

・　Laravel Fortify

・　Laravel Dusk 

・　Laravel Framework 11.35.1

・　Mailpit v1.21.7

・　phpmyadmin 

・　Stripe v16.4.0

## ER図

<p align="center">
<img src="https://github.com/user-attachments/assets/2d051c04-e1f5-4387-bdb0-b9fe4623437c">
</p>

## テスト

・　マイグレーション後にコマンドライン上で「./vendor/bin/sail dusk」