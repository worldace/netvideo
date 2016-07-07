<?php

//確認
if(!自然数なら($_GET['id']))  { エラー(); }
if(!自然数なら($_GET['path'])){ エラー(); }

$件数 = (自然数なら($_GET['num']) ? $_GET['num'] : "∞";


$結果 = データベース("コメント", SQLiteドライバ()) -> 取得(["件数"=>$件数, "行タイプ"=>"配列"]);
JSON表示($結果);


function SQLiteドライバ(){
    global $設定;
    
    $ymd  = date('Y/md', $_GET['path']);
    $path = "{$設定['ディレクトリ.upload']}/{$ymd}/{$_GET['id']}.db";
    if(!is_file($path)){ エラー(); }
    return "sqlite:$path";
}
