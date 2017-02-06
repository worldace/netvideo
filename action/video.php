<?php

検証("GET", "id", "自然数");

$動画 = データベース("動画") -> 行取得($_GET['id']) or エラー404('その動画は存在しません');


?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title><?= h($動画['タイトル']) ?></title>
  <style>
*{ box-sizing: border-box; }
</style>
</head>
<body>

<?= 部品("jsplayer", $動画) ?>


</body>
</html>