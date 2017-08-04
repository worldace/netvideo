<?php

$_GET += ['id'=>'', '件数'=>10000, '登録時間'=>''];

検査($_GET['id'],       "自然数");
検査($_GET['件数'],     "自然数");
検査($_GET['登録時間'], "自然数");

if(検査::失敗なら()){
    exit;
}

$dbfile = sprintf("%s/upload/%s/%s.db", $_ENV['公開ディレクトリ'], date('Y/md', $_GET['登録時間']), $_GET['id']);
if(!is_file($dbfile)){
    exit;
}


$結果 = データベース("コメント", "sqlite:$dbfile")->取得(0, $_GET['件数']);

JSON表示($結果);
