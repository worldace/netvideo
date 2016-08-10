<?php

POST検証("id")       -> 自然数();
POST検証("登録時間") -> 自然数();
POST検証("位置")     -> ０以上();
POST検証("本文")     -> 必須() -> ６４文字以内();


データベース("動画") -> 更新($_POST['id'], [
    "コメント数" => ["式"=>"コメント数=コメント数+1"],
]);

データベース("コメント", SQLiteドライバ()) -> 追加([
    "本文"     => $_POST['本文'],
    "位置"     => (int)$_POST['位置'],
    "登録時間" => (int)$_SERVER['REQUEST_TIME'],
]);


function SQLiteドライバ(){
    $ymd  = date('Y/md', $_POST['登録時間']);
    $path = "{$_ENV['ディレクトリ.upload']}/{$ymd}/{$_POST['id']}.db";
    if(!is_file($path)){ exit(); }
    return "sqlite:$path";
}
