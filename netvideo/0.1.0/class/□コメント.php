<?php

class □コメント{
    const 定義 = [
        "id"       => "integer primary key auto_increment",
        "本文"     => "varchar(64) not null",
        "位置"     => "integer unsigned not null",
        "登録時間" => "integer unsigned not null",
    ];
}
