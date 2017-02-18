<?php

class イベント{
    private static $情報 = [];
    
    static function 登録($イベント名, callable $関数, array $オプション = null){
        self::$情報[$イベント名][] = ["関数"=>$関数, "オプション"=>$オプション];
    }
    
    static function 解除($イベント名, callable $関数){
        foreach((array)self::$情報[$イベント名] as $key => $value){
            if($value["関数"] === $関数){
                unset(self::$情報[$イベント名][$key]);
                break;
            }
        }
    }
    
    static function 発生($イベント名, $引数 = null){
        foreach((array)self::$情報[$イベント名] as $value){
            $value["関数"]($引数);
        }
    }
}