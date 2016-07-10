<?php

GET検証('id') -> 自然数();

$video = データベース("動画") -> 行取得($_GET['id']) or エラー('その動画は存在しません');

$video['動画URL']  = h($video['動画URL']);
$video['タイトル'] = h($video['タイトル']);
$video['本文']     = h($video['本文']);



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

<?= 部品("player", $video) ?>


</body>
</html>