<?php

$_POST['サムネイル'] = base64_decode($_POST['サムネイル']);

POST検証("動画URL")    -> URL();
POST検証("ユーザid")   -> 必須() -> ２５０字以内();
POST検証("タイトル")   -> 必須() -> ２５０字以内();
POST検証("本文")       -> ５０００字以内();
POST検証("横サイズ")   -> ０より大きい();
POST検証("縦サイズ")   -> ０より大きい();
POST検証("動画時間")   -> ０より大きい();
POST検証("サムネイル") -> 画像データ();

//ToDo: 二重投稿防止(URLユニーク)

$dir = ディレクトリ作成($設定['ディレクトリ.upload'].date('/Y/md')) or エラー("ディレクトリが作成できません");

$設定['動画id'] = データベース("動画") -> 追加([
    "動画URL"  => $_POST['動画URL'],
    "ユーザid" => $_POST['ユーザid'],
    "タイトル" => $_POST['タイトル'],
    "本文"     => $_POST['本文'],
    "横サイズ" => (int)$_POST['横サイズ'],
    "縦サイズ" => (int)$_POST['縦サイズ'],
    "動画時間" => (int)$_POST['動画時間'],
    "投稿時間" => (int)$_SERVER['REQUEST_TIME'],
]) or エラー("データベースに登録できません");


file_put_contents("$dir/{$設定['動画id']}.png", $_POST['サムネイル'], LOCK_EX);

データベース("コメント", "sqlite:$dir/{$設定['動画id']}.db") -> 作成(コメントテーブル::定義);

$設定['移動先のURL'] = "?action=video&id={$設定['動画id']}";


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
