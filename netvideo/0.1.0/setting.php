<?php


$_ENV = new 不変配列;

//ホームページのURL
$_ENV['url'] = "http://127.0.0.1/netvideo/";

//PATH_INFOの記法
$_ENV['pathinfo.区切り文字'] = '.';

//アプリディレクトリのフルパス (最後のスラッシュは必要)
$_ENV['アプリディレクトリ'] = __DIR__ . '/';

//公開ディレクトリのフルパス (最後のスラッシュは必要)
$_ENV['公開ディレクトリ'] = __DIR__ . '/../../';

//動画データベースのドライバー
$_ENV['データベース.接続.0'] = 'sqlite:' . __DIR__ . '/../video.db';

//動画データベースのドライバー (標準データベースはSQLiteなので無効になっています)
//$_ENV['データベース.接続.0'] = 'mysql:host=ホストアドレス;dbname=データベース名;charset=utf8mb4';

//データベースのユーザー名 (MySQL使用時に必要)
$_ENV['データベース.接続.1'] = 'ユーザー名';

//データベースのパスワード (MySQL使用時に必要)
$_ENV['データベース.接続.2'] = 'パスワード';

//部品ディレクトリ
$_ENV['部品.ディレクトリ'] = __DIR__ . '/../部品/';

//php.ini
$_ENV['ini'] = [
    'session.cookie_httponly' => 1,
];
