<?php
$設定['DBドライバ'] = "sqlite:./video.db";

include_once('../action/php/setting.table.php');
include_once('../action/php/function.php');

データベーステーブル作成("動画",   $設定['テーブル定義:動画'],   $設定['DBドライバ']);
