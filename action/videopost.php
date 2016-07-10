<?php

POST検証("url")      -> URL();
POST検証("userid")   -> 必須() -> ２５０字以内();
POST検証("title")    -> 必須() -> ２５０字以内();
POST検証("text")     -> ５０００字以内();
POST検証("width")    -> ０より大きい();
POST検証("height")   -> ０より大きい();
POST検証("duration") -> ０より大きい();


//画像確認 getimagesize()[0]:横サイズ [1]:縦サイズ [2]:GIFは1、JPEGは2、PNGは3
if(!getimagesize("data:;base64,".$_POST['thumbnail'])[0]){ エラー("サムネイルが不正です"); }


//二重投稿防止(未作成、URLユニーク)




$dir = ディレクトリ作成($設定['ディレクトリ.upload'].date('/Y/md')) or エラー("ディレクトリが作成できません");



$設定['動画ID'] = データベース("動画") -> 追加([
    "動画URL"  => $_POST['url'],
    "ユーザID" => $_POST['userid'],
    "タイトル" => $_POST['title'],
    "本文"     => $_POST['text'],
    "横サイズ" => (int)$_POST['width'],
    "縦サイズ" => (int)$_POST['height'],
    "動画時間" => $_POST['duration'],
    "投稿時間" => (int)$_SERVER['REQUEST_TIME'],
]);
if(!$設定['動画ID']){ エラー("データベースに登録できません"); }


//サムネイルファイル作成
file_put_contents("$dir/{$設定['動画ID']}.png", base64_decode($_POST['thumbnail']), LOCK_EX);

//コメントデータベース作成
include("{$設定['ディレクトリ.action']}/php/setting.table.php");
データベース("コメント", "sqlite:$dir/{$設定['動画ID']}.db") -> 作成($設定['データベース.コメントテーブル定義']);


$設定['移動先のURL'] = "?action=video&id={$設定['動画ID']}";



?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>投稿成功</title>
<script>window.onload = function(){ setTimeout(function(){ location.href = "<?= $設定['移動先のURL'] ?>"; }, 0); };</script>
</head>
<body></body>
</html>
