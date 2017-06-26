<?php

整形("POST", "サムネイル", function($v){ return base64_decode($v); });

検査("POST", "url", "URL");
検査("POST", "ユーザid", "必須");
検査("POST", "ユーザid", "250文字以下");
検査("POST", "タイトル", "必須");
検査("POST", "タイトル", "250文字以下");
検査("POST", "本文", "5000文字以下");
検査("POST", "横幅", "0より大きい");
検査("POST", "縦幅", "0より大きい");
検査("POST", "長さ", "0より大きい");
検査("POST", "サムネイル", "画像データ");



//ToDo: 二重投稿防止(URLユニーク)

$dir = ディレクトリ作成(sprintf('%s/upload/%s', 設定['公開ディレクトリ'], date('/Y/md'))) or エラー500("ディレクトリが作成できません");

$id = データベース("動画") -> 追加([
    "url"      => $_POST['url'],
    "ユーザid" => $_POST['ユーザid'],
    "タイトル" => $_POST['タイトル'],
    "本文"     => $_POST['本文'],
    "横幅"     => (int)$_POST['横幅'],
    "縦幅"     => (int)$_POST['縦幅'],
    "長さ"     => (int)$_POST['長さ'],
    "登録時間" => (int)$_SERVER['REQUEST_TIME'],
]) or エラー500("データベースに登録できません");


file_put_contents("$dir/$id.png", $_POST['サムネイル'], LOCK_EX);

データベース("コメント", "sqlite:$dir/$id.db") -> 作成(コメント定義::定義);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>投稿成功</title>
</head>
<body><?= 部品("jump", URL作成(["action"=>"video","id"=>$id])) ?></body>
</html>
