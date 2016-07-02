<?php

//確認
if(!自然数なら($_POST['id']))  { exit; }
if(!自然数なら($_POST['path'])){ exit; }
if(!整数なら($_POST['time'])){ exit; }
if($_POST['comment'] == ""){ exit; }
if(mb_strlen($_POST['comment'], "UTF-8") > 64) { exit; }

//データベース追加
$設定['データベース.ドライバ'] = "sqlite:" . DBパス作成();

データベース追加(
    "insert into コメント (コメント, 動画時間, 投稿時間) values (?, {$_POST['time']}, {$_SERVER['REQUEST_TIME']})",
    array($_POST['comment'])
);


//カウントアップ
データベース接続("sqlite:{$設定['DBファイル']}");
データベース更新("update 動画 set コメント数 = コメント数 + 1 where 動画ID = {$_POST['id']}");



function DBパス作成(){
    global $設定;

    $ymd  = date('Y/md', $_POST['path']);
    $path = "{$設定['uploadディレクトリ']}/{$ymd}/{$_POST['id']}.db";
    if(!is_file($path)){ exit; }
    return $path;
}