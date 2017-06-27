<?php

abstract class 列挙型{
    private $値;

    final function __construct($引数){
        if(!in_array($引数, static::列挙, true)){
            throw new Exception("[$引数]は" . get_class($this) . "クラスに定義されていません");
        }
        $this->値 = $引数;
    }

    final function __toString(){
        return (string)$this->値;
    }

    final function __invoke(){
        return $this->値;
    }
}

