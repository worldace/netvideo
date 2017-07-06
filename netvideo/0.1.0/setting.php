<?php
const 設定 = [

//ホームページのURL
'URL' => "http://127.0.0.1/netvideo/",

//PATH_INFOの記法
'PATH_INFO区切り文字' => '.',

//公開ディレクトリのフルパス (最後のスラッシュは不要)
'公開ディレクトリ' => __DIR__ . '/../..',

//アプリディレクトリのフルパス (最後のスラッシュは不要)
'アプリディレクトリ' => __DIR__,

//動画データベースのドライバー
'データベースドライバー' => 'sqlite:' . __DIR__ . '/../video.db',

//動画データベースのドライバー (標準データベースはSQLiteなので無効になっています)
//'データベースドライバー' => 'mysql:host=ホストアドレス;dbname=データベース名;charset=utf8',

//データベースのユーザー名 (MySQL使用時に必要)
'データベースユーザー名' => 'ユーザー名',

//データベースのパスワード (MySQL使用時に必要)
'データベースパスワード' => 'パスワード',

];