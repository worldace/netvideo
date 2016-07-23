<?php

class コメントテーブル{
    const 定義 = [
        "id"           => "integer primary key auto_increment",
        "コメント"     => "varchar(64) not null",
        "動画時間"     => "integer unsigned not null",
        "投稿時間"     => "integer unsigned not null",
    ];

    public $id;
    public $コメント;
    public $動画時間;
    public $投稿時間;
}
