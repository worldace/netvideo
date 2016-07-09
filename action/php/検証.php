<?php


function GET検証($key){
    return new 検証($key, "GET");
}
function POST検証($key){
    return new 検証($key, "POST");
}

class 検証{
    private static $func = "エラー";
    private $value;
    private $key;
    private $method;

    public function __construct($value, $method = ""){
        if($method){
            $this->key   = $value;
            $this->value = ($method == "POST") ? $_POST[$value] : $_GET[$value];
        }
        else{
            $this->value = $value;
        }
    }
    
    public function 必須($comment = ""){
        if($this->value === null or $this->value === ""){
            if(!$comment and $this->key){ $comment = "{$this->key}を入力してください"; }
            $this->error($comment);
        }
        return $this;
    }

    public function 数($comment = ""){
        if(!is_numeric($this->value)){
            if(!$comment and $this->key){ $comment = "{$this->key}には数値を入力してください"; }
            $this->error($comment);
        }
    }

    public function 自然数($comment = ""){
        if(!preg_match("/^[1-9][0-9]*$/", $this->value)){
            if(!$comment and $this->key){ $comment = "{$this->key}は1以上にしてください"; }
            $this->error($comment);
        }
        return $this;
    }
    
    public function 字以上($num, $comment = ""){
        if(mb_strlen($this->value,"UTF-8") < $num){
            if(!$comment and $this->key){ $comment = "{$this->key}は{$num}文字以上にしてください"; }
            $this->error($comment);
        }
        return $this;
    }

    public function 字以下($num, $comment = ""){
        if(mb_strlen($this->value,"UTF-8") > $num){
            if(!$comment and $this->key){ $comment = "{$this->key}は{$num}文字以内にしてください"; }
            $this->error($comment);
        }
        return $this;
    }

    public function 以上($num, $unit = "", $comment = ""){
        $this->数();
        if(!($this->value >= $num)){
            if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}以上にしてください"; }
            $this->error($comment);
        }
        return $this;
    }

    public function 以下($num, $unit = "", $comment = ""){
        $this->数();
        if(!($this->value <= $num)){
            if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}以下にしてください"; }
            $this->error($comment);
        }
        return $this;
    }

    public function より大きい($num, $unit = "", $comment = ""){
        $this->数();
        if(!($this->value > $num)){
            if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}より大きくしてください"; }
            $this->error($comment);
        }
        return $this;
    }

    public function より小さい($num, $unit = "", $comment = ""){
        $this->数();
        if(!($this->value < $num)){
            if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}より小さくしてください"; }
            $this->error($comment);
        }
        return $this;
    }

    private function error($comment = "エラーが発生しました"){
        call_user_func(self::$func, $comment);
    }

    function __call($name, $args){
        if(preg_match("/^([０-９]+)文?字以上$/u", $name, $m)) { $this->字以上(mb_convert_kana($m[1], "n", "utf-8"), $args[0]); }
        if(preg_match("/^([０-９]+)文?字以/u", $name, $m))    { $this->字以下(mb_convert_kana($m[1], "n", "utf-8"), $args[0]); }
        else if(preg_match("/^([０-９]+)(\w*)以上$/u", $name, $m)) { $this->以上(mb_convert_kana($m[1], "n", "utf-8"), $m[2], $args[0]); }
        else if(preg_match("/^([０-９]+)(\w*)以/u", $name, $m))    { $this->以下(mb_convert_kana($m[1], "n", "utf-8"), $m[2], $args[0]); }
        else if(preg_match("/^([０-９]+)(\w*)より大/u", $name, $m)){ $this->より大きい(mb_convert_kana($m[1], "n", "utf-8"), $m[2], $args[0]); }
        else if(preg_match("/^([０-９]+)(\w*)より小/u", $name, $m)){ $this->より小きい(mb_convert_kana($m[1], "n", "utf-8"), $m[2], $args[0]); }
        else { throw new Exception("検証関数に{$name}はありません"); }
    }

    public static function 設定(callable $func){
        if($func){ self::$func = $func; }
    }
}
