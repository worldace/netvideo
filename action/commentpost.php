<?php

検証("POST", "id", "自然数");
検証("POST", "登録時間", "自然数");
検証("POST", "位置", "0以上");
検証("POST", "本文", "必須");
検証("POST", "本文", "64文字以下");


データベース("動画") -> 更新($_POST['id'], [
    "コメント数" => ["式"=>"コメント数+1"],
]);

$db = "{$_ENV['ディレクトリ.upload']}/". date('Y/md', $_GET['登録時間']) . "/{$_GET['id']}.db";
if(!is_file($db)){ exit; }

データベース("コメント", "sqlite:$db") -> 追加([
    "本文"     => $_POST['本文'],
    "位置"     => (int)$_POST['位置'],
    "登録時間" => (int)$_SERVER['REQUEST_TIME'],
]);
