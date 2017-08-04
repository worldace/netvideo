<?php

$_POST += ['url'=>'', 'ユーザid'=>'', 'タイトル'=>'', '本文'=>'', '横幅'=>'', '縦幅'=>'', '長さ'=>'', 'サムネイル'=>''];

整形($_POST['サムネイル'], function($v){ return base64_decode($v); });

検査($_POST['url'],        "URL");
検査($_POST['ユーザid'],   "必須");
検査($_POST['ユーザid'],   "250文字以下");
検査($_POST['タイトル'],   "必須");
検査($_POST['タイトル'],   "250文字以下");
検査($_POST['本文'],       "5000文字以下");
検査($_POST['横幅'],       "0より大きい");
検査($_POST['縦幅'],       "0より大きい");
検査($_POST['長さ'],       "0より大きい");
検査($_POST['サムネイル'], "画像データ");



//ToDo: 二重投稿防止(URLユニーク)


$id = データベース("動画")->追加([
    "url"      => $_POST['url'],
    "ユーザid" => $_POST['ユーザid'],
    "タイトル" => $_POST['タイトル'],
    "本文"     => $_POST['本文'],
    "横幅"     => $_POST['横幅'],
    "縦幅"     => $_POST['縦幅'],
    "長さ"     => $_POST['長さ'],
    "登録時間" => $_SERVER['REQUEST_TIME'],
]) or エラー500("データベースに登録できません");


$dir = ディレクトリ作成(sprintf('%s/upload/%s', $_ENV['公開ディレクトリ'], date('Y/md'))) or エラー500("ディレクトリが作成できません");

file_put_contents("$dir/$id.png", $_POST['サムネイル'], LOCK_EX);

データベース("コメント", "sqlite:$dir/$id.db")->テーブル作成();



?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>投稿成功</title>
</head>
<body><?= 部品("jump", URL($id)) ?></body>
</html>
