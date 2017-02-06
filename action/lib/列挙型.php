<?php

abstract class 列挙型{
    private $値;

    public function __construct($引数){
        $ref = new ReflectionObject($this);
        if(!in_array($引数, $ref->getConstants(), true)){
            throw new Exception("[$引数]は" . get_class($this) . "クラスに定義されていません");
        }
        $this->値 = $引数;
    }

    final public function __toString(){
        return (string)$this->値;
    }

    final public function get(){
        return $this->値;
    }
}
