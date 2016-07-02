<?php

//確認
if(!自然数なら($_GET['id']))  { json_print(); }
if(!自然数なら($_GET['path'])){ json_print(); }


//データベース取得
$設定['データベース.ドライバ'] = "sqlite:" . DBパス作成();
if(自然数なら($_GET['num'])){ $limit = "limit {$_GET['num']}"; }

$検索結果 = データベース取得("select コメント,動画時間,投稿時間 from コメント order by コメントID desc $limit", null, PDO::FETCH_NUM);
if(!$検索結果){ json_print(); }

//出力
json_print($検索結果);


function DBパス作成(){
    global $設定;
    
    $ymd  = date('Y/md', $_GET['path']);
    $path = "{$設定['uploadディレクトリ']}/{$ymd}/{$_GET['id']}.db";
    if(!is_file($path)){ json_print(); }
    return $path;
}

function json_print($json = array()){
    header("Content-Type: application/json; charset=utf-8");
    print json_encode($json);
    exit;
}
