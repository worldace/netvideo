<?php

class □動画{
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
}
