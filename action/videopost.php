<?php

//確認
if(!$_POST['url'])      { エラー("動画URLが存在しません"); }
if(!$_POST['userid'])   { エラー("ユーザIDが存在しません"); }
if(!$_POST['title'])    { エラー("タイトルが存在しません"); }
if(!$_POST['thumbnail']){ エラー("サムネイルが存在しません"); }

$_POST['width']  = floor($_POST['width']);
$_POST['height'] = floor($_POST['height']);
if(!自然数なら($_POST['width']))   { エラー("横サイズが不適切です"); }
if(!自然数なら($_POST['height']))  { エラー("縦サイズが不適切です"); }
if(!is_numeric($_POST['duration']) or $_POST['duration'] < 0) { エラー("動画時間が不適切です"); }


//詳細確認
if(mb_strlen($_POST['text'],"UTF-8")   > 10000){ エラー("本文が長すぎます"); }
if(mb_strlen($_POST['userid'],"UTF-8") > 250)  { エラー("ユーザIDが長すぎます"); }
if(mb_strlen($_POST['title'],"UTF-8")  > 250)  { エラー("タイトルが長すぎます"); }
if(mb_strlen($_POST['url'],"UTF-8")    > 500)  { エラー("URLが長すぎます"); }

if(!preg_match("/https*:\/\/.+/i", $_POST['url'])) { エラー("URLが不適切です"); }


//画像確認 getimagesize()[0]:横サイズ [1]:縦サイズ [2]:GIFは1、JPEGは2、PNGは3
if(!getimagesize("data:;base64,".$_POST['thumbnail'])[0]){ エラー("画像フォーマットが取得できません"); }


//二重投稿防止(未作成、URLユニーク)






$設定['動画ID'] = データベース("動画") -> 追加([
    "動画URL"  => $_POST['url'],
    "ユーザID" => $_POST['userid'],
    "タイトル" => $_POST['title'],
    "本文"     => $_POST['text'],
    "横サイズ" => (int)$_POST['width'],
    "縦サイズ" => (int)$_POST['height'],
    "動画時間" => (int)$_POST['duration'],
    "投稿時間" => (int)$_SERVER['REQUEST_TIME'],
]);
if(!$設定['動画ID']){ エラー("データベースに登録できません"); }


//ディレクトリ作成
$dir = ディレクトリ作成($設定['ディレクトリ.upload']."/".date('Y/md')) or エラー("ディレクトリが作成できません");

//サムネイルファイル作成
file_put_contents("$dir/{$設定['動画ID']}.png", base64_decode($_POST['thumbnail']), LOCK_EX);

//コメントデータベース作成
include("{$設定['ディレクトリ.action']}/php/setting.table.php");
データベース("コメント", "sqlite:$dir/{$設定['動画ID']}.db") -> 作成($設定['データベース.コメントテーブル定義']);


//移動先のURL
$設定['URL'] = "?action=video&id={$設定['動画ID']}";





?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>投稿成功</title>
<script>window.onload = function(){ setTimeout(function(){ location.href = "<?= $設定['URL'] ?>"; }, 0); };</script>
</head>
<body></body>
</html>
