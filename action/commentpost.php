<?php

POST検証("id")           -> 自然数();
POST検証("動画投稿時間") -> 自然数();
POST検証("動画時間")     -> ０以上();
POST検証("コメント")     -> 必須() -> ６４文字以内();


データベース("動画") -> 更新($_POST['id'], [
    "コメント数=コメント数+1" => null,
]);

データベース("コメント", SQLiteドライバ()) -> 追加([
    "コメント" => $_POST['コメント'],
    "動画時間" => (int)$_POST['動画時間'],
    "投稿時間" => (int)$_SERVER['REQUEST_TIME'],
]);


function SQLiteドライバ(){
    global $設定;
    
    $ymd  = date('Y/md', $_POST['動画投稿時間']);
    $path = "{$設定['ディレクトリ.upload']}/{$ymd}/{$_POST['id']}.db";
    if(!is_file($path)){ exit(); }
    return "sqlite:$path";
}
