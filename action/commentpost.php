<?php

//確認
POST検証("id")      -> 自然数();
POST検証("path")    -> 自然数();
POST検証("time")    -> ０以上();
POST検証("comment") -> 必須() -> ６４文字以内();


データベース("動画") -> 更新($_POST['id'], [
    "コメント数=コメント数+1" => null,
]);

データベース("コメント", SQLiteドライバ()) -> 追加([
    "コメント" => $_POST['comment'],
    "動画時間" => (int)$_POST['time'],
    "投稿時間" => (int)$_SERVER['REQUEST_TIME'],
]);


function SQLiteドライバ(){
    global $設定;
    
    $ymd  = date('Y/md', $_GET['path']);
    $path = "{$設定['ディレクトリ.upload']}/{$ymd}/{$_GET['id']}.db";
    if(!is_file($path)){ exit(); }
    return "sqlite:$path";
}
