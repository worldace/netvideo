<?php

class 動画テーブル{
    const 定義 = [
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
    ];
    
    public $id;
    public $動画URL;
    public $横サイズ;
    public $縦サイズ;
    public $動画時間;
    public $投稿時間;
    public $アクセス数;
    public $コメント数;
    public $ユーザid;
    public $状態;
    public $タイトル;
    public $本文;
    public $備考;
}
