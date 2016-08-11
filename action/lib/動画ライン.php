<?php

class 動画ライン{
    const 定義 = [
        "id"           => "integer primary key auto_increment",
        "url"          => "varchar(500) not null",
        "横幅"         => "smallint unsigned not null",
        "縦幅"         => "smallint unsigned not null",
        "長さ"         => "float unsigned not null",
        "登録時間"     => "integer unsigned not null",
        "アクセス数"   => "integer unsigned default 0",
        "コメント数"   => "integer unsigned default 0",
        "ユーザid"     => "varchar(250) not null",
        "状態"         => "varchar(30) default '公開'",
        "タイトル"     => "varchar(250) not null",
        "本文"         => "text",
        "備考"         => "text",
    ];
    
    public $id;
    public $url;
    public $横幅;
    public $縦幅;
    public $長さ;
    public $登録時間;
    public $アクセス数;
    public $コメント数;
    public $ユーザid;
    public $状態;
    public $タイトル;
    public $本文;
    public $備考;
}
