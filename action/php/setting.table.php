<?php
//======================================================
// ■データベースのテーブル定義 SQLite/MySQL共用
// 
// 呼び出し元: ../videopost.php
//======================================================


/*
□SQLite
・primary keyは自動的にautoincremanetになる。現在の最大IDが割り当てられる (最大IDを削除した場合に重複する可能性がある)
・primary keyの後にautoincremanetをつけることもできる … 歴代の最大IDが割り当てられる (重複することはない)
・型は自動判別される。TEXT型のカラムにINTEGERのデータ型の値が格納された場合、TEXT型に変換
・create時、データ型が文字列「INT」を含む場合、INTEGER型となる
・create時、データ型が文字列「CHAR」「BLOB」「TEXT」のいずれかを含む場合、TEXT型となる

□MySQL
「autoincrement」ではなく「auto_increment」なので注意
*/

$設定['データベース.動画テーブル定義'] = array(
"id"           => "integer primary key auto_increment",
"動画URL"      => "varchar(500) not null",
"横サイズ"     => "smallint unsigned not null",
"縦サイズ"     => "smallint unsigned not null",
"動画時間"     => "float unsigned not null",
"投稿時間"     => "integer unsigned not null",
"アクセス数"   => "integer unsigned default 0",
"コメント数"   => "integer unsigned default 0",
"ユーザid"     => "varchar(250) not null",
"状態"         => "varchar(30) default '公開'",
"タイトル"     => "varchar(250) not null",
"本文"         => "text",
"備考"         => "text",
);


$設定['データベース.コメントテーブル定義'] = array(
"id"           => "integer primary key auto_increment",
"コメント"     => "varchar(64) not null",
"動画時間"     => "integer unsigned not null",
"投稿時間"     => "integer unsigned not null",
);

