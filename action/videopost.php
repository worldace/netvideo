<?php
$設定['DBドライバ'] = "sqlite:./video.db";

//存在確認
if(!isset($_POST['url']))      { エラー("動画URLが存在しません"); }
if(!isset($_POST['width']))    { エラー("横サイズが存在しません"); }
if(!isset($_POST['height']))   { エラー("縦サイズが存在しません"); }
if(!isset($_POST['duration'])) { エラー("動画時間が存在しません"); }
if(!isset($_POST['userid']))   { エラー("ユーザIDが存在しません"); }
if(!isset($_POST['title']))    { エラー("タイトルが存在しません"); }
if(!isset($_POST['thumbnail'])){ エラー("サムネイルが存在しません"); }


//加工
$_POST['width']    = floor($_POST['width']);
$_POST['height']   = floor($_POST['height']);
$_POST['duration'] = floor($_POST['duration']);


//詳細確認
if(!自然数なら($_POST['width']))   { エラー("横サイズが不適切です"); }
if(!自然数なら($_POST['height']))  { エラー("縦サイズが不適切です"); }
if(!自然数なら($_POST['duration'])){ エラー("動画時間が不適切です"); }

if(mb_strlen($_POST['url'],"UTF-8")    > 2000)   { エラー("URLが長すぎます"); }
if(mb_strlen($_POST['userid'],"UTF-8") > 250)    { エラー("ユーザIDが長すぎます"); }
if(mb_strlen($_POST['title'],"UTF-8")  > 250)    { エラー("タイトルが長すぎます"); }
if(mb_strlen($_POST['text'],"UTF-8")   > 1024*10){ エラー("本文が長すぎます"); }

if(!preg_match("/https*:\/\/.+/i", $_POST['url'])) { エラー("URLが不適切です"); }


//二重投稿防止(URLユニーク)


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


//移動先のURL
$設定['URL'] = "?action=video&id={$設定['動画ID']}";





?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>投稿成功</title>
</head>
<body>
<script>window.onload = function(){ setTimeout(function(){ location.replace = "<?= $設定['URL'] ?>"; }, 0); };</script>
</body>
</html>
