<?php

//確認
if(!自然数なら($_POST['id']))  { exit; }
if(!自然数なら($_POST['path'])){ exit; }
if(!整数なら($_POST['time'])){ exit; }
if($_POST['comment'] == ""){ exit; }
if(mb_strlen($_POST['comment'], "UTF-8") > 64) { exit; }


データベース("動画") -> 更新($_POST['id'], [
    "コメント数=コメント数+1" => null,
]);

データベース("コメント", SQLiteドライバ())  -> 追加([
    "コメント" => $_POST['comment'],
    "動画時間" => (int)$_POST['time'],
    "投稿時間" => (int)$_SERVER['REQUEST_TIME'],
]);


function SQLiteドライバ(){
    global $設定;
    
    $ymd  = date('Y/md', $_GET['path']);
    $path = "{$設定['uploadディレクトリ']}/{$ymd}/{$_GET['id']}.db";
    if(!is_file($path)){ exit(); }
    return "sqlite:$path";
}
