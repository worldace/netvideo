<?php

//存在確認
if(!isset($_POST['id']))   { exit; }
if(!isset($_POST['path'])) { exit; }
if(!isset($_POST['num']))  { exit; }

//詳細確認
if(!自然数なら($_POST['id']))  { exit; }
if(!自然数なら($_POST['path'])){ exit; }
if(!自然数なら($_POST['num'])) { exit; }

//データベース取得
$設定['DBドライバ'] = "sqlite:" . DBパス作成();
$検索結果 = データベース取得("select コメント,動画時間,投稿時間 from コメント order by コメントID desc limit {$_POST['num']}", null, PDO::FETCH_NUM);

//出力
header("Content-Type: application/json; charset=utf-8");
print json_encode($検索結果);


/*
$検索結果 = データベース取得("select コメント,動画時間,投稿時間 from コメント order by コメントID desc limit {$_POST['num']}");

$result = array();
foreach($検索結果 as $comment){
    $result[] = array($comment['コメント'], $comment['動画時間'], $comment['投稿時間']);
}

header("Content-Type: application/json; charset=utf-8");
print json_encode($result);
*/


function DBパス作成(){
    $ymd  = date('Y/md', $_POST['path']);
    $path = "{$設定['contentディレクトリ']}/{$ymd}/{$_POST['id']}.db";
    if(!is_file($path)){ exit; }
    return $path;
}