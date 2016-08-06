<?php

$_POST['サムネイル'] = base64_decode($_POST['サムネイル']);

POST検証("url")        -> URL();
POST検証("ユーザid")   -> 必須() -> ２５０字以内();
POST検証("タイトル")   -> 必須() -> ２５０字以内();
POST検証("本文")       -> ５０００字以内();
POST検証("横幅")       -> ０より大きい();
POST検証("縦幅")       -> ０より大きい();
POST検証("長さ")       -> ０より大きい();
POST検証("サムネイル") -> 画像データ();

//ToDo: 二重投稿防止(URLユニーク)

$dir = ディレクトリ作成($_ENV['ディレクトリ.upload'].date('/Y/md')) or エラー("ディレクトリが作成できません");

$id = データベース("動画") -> 追加([
    "url"      => $_POST['url'],
    "ユーザid" => $_POST['ユーザid'],
    "タイトル" => $_POST['タイトル'],
    "本文"     => $_POST['本文'],
    "横幅"     => (int)$_POST['横幅'],
    "縦幅"     => (int)$_POST['縦幅'],
    "長さ"     => (int)$_POST['長さ'],
    "登録時間" => (int)$_SERVER['REQUEST_TIME'],
]) or エラー("データベースに登録できません");


file_put_contents("$dir/$id.png", $_POST['サムネイル'], LOCK_EX);

データベース("コメント", "sqlite:$dir/$id.db") -> 作成(コメントテーブル::定義);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>投稿成功</title>
</head>
<body><?= 部品("jump", URL作成(["action"=>"video","id"=>$id])) ?></body>
</html>
