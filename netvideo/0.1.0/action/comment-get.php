<?php

$_GET += ['id'=>'', '登録時間'=>'', '件数'=>''];

検査($_GET['id'], "自然数");
検査($_GET['登録時間'], "自然数");

if(検査::失敗なら()){
    exit;
}

$db = sprintf("%s/upload/%s/%s.db", 設定['公開ディレクトリ'], date('Y/md', $_GET['登録時間']), $_GET['id']);
if(!is_file($db)){
    exit;
}

$結果 = データベース("コメント", "sqlite:$db") -> 取得([
    "件数" => 自然数なら($_GET['件数']) ? $_GET['件数'] : "∞"
]);

JSON表示($結果);
