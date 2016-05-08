<?php
$設定['DBドライバ'] = "sqlite:./video.db";

//存在確認
if(!isset($_POST['url']))      { エラー("動画URLが存在しません"); }
if(!isset($_POST['width']))    { エラー("動画横サイズが存在しません"); }
if(!isset($_POST['height']))   { エラー("動画縦サイズが存在しません"); }
if(!isset($_POST['duration'])) { エラー("動画時間が存在しません"); }
if(!isset($_POST['userid']))   { エラー("ユーザIDが存在しません"); }
if(!isset($_POST['title']))    { エラー("タイトルが存在しません"); }
if(!isset($_POST['thumbnail'])){ エラー("サムネイルが存在しません"); }


//詳細確認


//ディレクトリ作成
$dir = 年月日ディレクトリ作成($設定['contentディレクトリ'], $_SERVER['REQUEST_TIME']);
if(!$dir){ エラー("ディレクトリが作成できません"); }


//画像確認
$tmp = tempnam($dir, "tmp"); //sys_get_temp_dir()は書き込めない可能性があるので使わない
file_put_contents($tmp, base64_decode($_POST['thumbnail']));
$imginfo = getimagesize($tmp);
if($imginfo[2] != 3){ //getimagesize()[0]:横サイズ [1]:縦サイズ [2]:PNGは3、JPEGは2、GIFは1
    unlink($tmp);
    エラー("画像フォーマットが取得できません");
}


//データベース追加
$設定['動画ID'] = データベース追加("
    insert into 動画 (動画URL, 横サイズ, 縦サイズ, 動画時間, ユーザID, タイトル, 本文, 投稿時間) 
    values (?, ?, ?, ?, ?, ?, ?, {$_SERVER['REQUEST_TIME']})",
    array($_POST['url'], $_POST['width'], $_POST['height'], $_POST['duration'], $_POST['userid'], $_POST['title'], $_POST['text'])
);
if(!自然数なら($設定['動画ID'])){ エラー("動画データベースに追加できません"); }


//サムネイルファイル作成
rename($tmp, "$dir/{$設定['動画ID']}.png");


//コメントデータベース作成
include_once("{$設定['actionディレクトリ']}/php/setting.table.php");
データベース接続("sqlite:$dir/{$設定['動画ID']}.db");
データベーステーブル作成("コメント", $設定['テーブル定義:コメント'], $設定['DBドライバ']);






print $設定['動画ID'];





