<?php

if(!自然数なら($_GET['id'])){ エラー('不正なIDです'); }

$video = データベース行取得("select * from 動画 where 動画ID = {$_GET['id']}");
if(!$video['動画ID']){ エラー('その動画は存在しません'); }

//エスケープ
$video['動画URL']  = h($video['動画URL']);
$video['タイトル'] = h($video['タイトル']);
$video['本文']     = h($video['本文']);


$設定['プレーヤー'] = 部品("player", $video);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title><?= $video['タイトル'] ?></title>
  <style>
*{ box-sizing: border-box; }
</style>
</head>
<body>

<?= $設定['プレーヤー'] ?>






</body>
</html>