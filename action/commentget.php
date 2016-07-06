<?php

//確認
if(!自然数なら($_GET['id']))  { json_print(); }
if(!自然数なら($_GET['path'])){ json_print(); }
$件数 = (自然数なら($_GET['num']) ? $_GET['num'] : "∞";


$結果 = データベース("コメント", SQLiteドライバ()) -> 取得(["件数"=>$件数, "行タイプ"=>"配列"]);
json_print($結果);


function SQLiteドライバ(){
    global $設定;
    
    $ymd  = date('Y/md', $_GET['path']);
    $path = "{$設定['uploadディレクトリ']}/{$ymd}/{$_GET['id']}.db";
    if(!is_file($path)){ json_print(); }
    return "sqlite:$path";
}

function json_print($json = array()){
    header("Content-Type: application/json; charset=utf-8");
    print json_encode($json);
    exit;
}
