<?php

GET検証("id")           -> 自然数();
GET検証("動画投稿時間") -> 自然数();

$件数 = (確認($_GET['件数'])->自然数()) ? $_GET['件数'] : "∞";


$結果 = データベース("コメント", SQLiteドライバ()) -> 取得(["件数"=>$件数, "行タイプ"=>"配列"]);
JSON表示($結果);


function SQLiteドライバ(){
    global $設定;
    
    $ymd  = date('Y/md', $_GET['動画投稿時間']);
    $path = "{$設定['ディレクトリ.upload']}/{$ymd}/{$_GET['id']}.db";
    if(!is_file($path)){ エラー(); }
    return "sqlite:$path";
}
