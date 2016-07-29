<?php

GET検証('id') -> 自然数();

$video = データベース("動画") -> 行取得($_GET['id']) or エラー('その動画は存在しません');


?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title><?= h($video['タイトル']) ?></title>
  <style>
*{ box-sizing: border-box; }
</style>
</head>
<body>

<?= 部品("player", $video) ?>


</body>
</html>