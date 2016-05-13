<?php

//確認
if(!自然数なら($_GET['id']))  { exit; }
if(!自然数なら($_GET['path'])){ exit; }


//データベース取得
$設定['DBドライバ'] = "sqlite:" . DBパス作成();
if(自然数なら($_GET['num'])){ $limit = "limit {$_GET['num']}"; }

$検索結果 = データベース取得("select コメント,動画時間,投稿時間 from コメント order by コメントID desc $limit", null, PDO::FETCH_NUM);
if(!$検索結果){ $検索結果 = null; }

//出力
header("Content-Type: application/json; charset=utf-8");
print json_encode($検索結果);


function DBパス作成(){
    $ymd  = date('Y/md', $_POST['path']);
    $path = "{$設定['uploadディレクトリ']}/{$ymd}/{$_GET['id']}.db";
    if(!is_file($path)){ exit; }
    return $path;
}