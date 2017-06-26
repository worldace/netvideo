<?php

検査("GET", "id", "自然数");
検査("GET", "登録時間", "自然数");


$db = sprintf("%s/upload/%s/%s.db", 設定['公開ディレクトリ'], date('Y/md', $_GET['登録時間']), $_GET['id']);
if(!is_file($db)){ exit; }

$結果 = データベース("コメント", "sqlite:$db") -> 取得([
    "件数" => 検査::自然数($_GET['件数']) ? $_GET['件数'] : "∞"
]);

JSON表示($結果);
