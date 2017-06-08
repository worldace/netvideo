<?php

//配列操作(range(0,10000))->if('$v % 2 === 0')->v('$v **= 2')->if('$v > 20')->sum();

function 配列操作(iterable $array) :配列操作{
    return new 配列操作($array);
}

class 配列操作{
    private $配列 = [];
    private $操作 = [];

    function __construct(iterable $array){
        $this->配列 = $array;
    }

    function v(string $arg = null) :self{
        if($arg){
            $this->操作[] = "\$v = $arg;";
        }
        else{
            $this->操作[] = "\$k = \$_i_;";
        }
        return $this;
    }

    function k(string $arg = null) :self{
        if($arg){
            $this->操作[] = "\$k = $arg;";
        }
        else{
            $this->操作[] = "\$v = \$k;";
            $this->操作[] = "\$k = \$_i_;";
        }
        return $this;
    }

    function if(string $arg) :self{
        $this->操作[] = "if(!($arg)){ continue; }";
        return $this;
    }
    
    function sum(){
        $_sum_ = 0;
        $this->操作[] = "\$_sum_ += \$v;";
        $this->eval();
        return $_sum_;
    }
    

    function out(&$array = null) :iterable{
        $this->eval();
        $array = $this->配列;
        return $this->配列;
    }

    private function eval() :void{
        if(!$this->操作){ return; }
        $_a_ = [];
        $_i_ = 0;
        $code = "foreach(\$this->配列 as \$k=>\$v){\n    " . implode($this->操作,"\n    ") . "\n    \$_a_[\$k] = \$v;\n    \$_i_++;\n}\n";
        eval($code);
        print $code;
        $this->配列 = $_a_;
        $this->操作 = [];
    }

}
