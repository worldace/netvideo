<?php

class 検証{
    public static $コールバック = "エラー";
    private $key;
    private $value;
    private $mode;
    private $method;

    public function __construct($mode, $value, $method = ""){
        $this->mode = $mode;
        if($method){
            $this->key   = $value;
            $this->value = ($method == "POST") ? @$_POST[$value] : @$_GET[$value];
        }
        else{
            $this->value = $value;
        }
    }
    
    public function 必須($comment = ""){
        if($this->value === null or $this->value === ""){
            if(!$comment and $this->key){ $comment = "{$this->key}を入力してください"; }
            $this->失敗($comment);
        }
        $this->成功();
    }

    public function 数($comment = ""){
        if(!is_numeric($this->value)){
            if(!$comment and $this->key){ $comment = "{$this->key}には数値を入力してください"; }
            $this->失敗($comment);
        }
        $this->成功();
    }

    public function 自然数($comment = ""){
        if(!preg_match("/^[1-9][0-9]*$/", $this->value)){
            if(!$comment and $this->key){ $comment = "{$this->key}は1以上にしてください"; }
            $this->失敗($comment);
        }
        $this->成功();
    }

    public function URL($comment = ""){
        if(!preg_match("|^https?://.{4,}|i", $this->value)){
            if(!$comment and $this->key){ $comment = "{$this->key}にはURLを入力してください"; }
            $this->失敗($comment);
        }
        $this->成功();
    }

    private function 字以上($num, $comment = ""){
        if(mb_strlen($this->value,"UTF-8") < $num){
            if(!$comment and $this->key){ $comment = "{$this->key}は{$num}文字以上にしてください"; }
            $this->失敗($comment);
        }
        $this->成功();
    }

    private function 字以下($num, $comment = ""){
        if(mb_strlen($this->value,"UTF-8") > $num){
            if(!$comment and $this->key){ $comment = "{$this->key}は{$num}文字以内にしてください"; }
            $this->失敗($comment);
        }
        $this->成功();
    }

    private function 以上($num, $unit = "", $comment = ""){
        $this->数($comment);
        if(!($this->value >= $num)){
            if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}以上にしてください"; }
            $this->失敗($comment);
        }
        $this->成功();
    }

    private function 以下($num, $unit = "", $comment = ""){
        $this->数($comment);
        if(!($this->value <= $num)){
            if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}以下にしてください"; }
            $this->失敗($comment);
        }
        $this->成功();
    }

    private function より大きい($num, $unit = "", $comment = ""){
        $this->数($comment);
        if(!($this->value > $num)){
            if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}より大きくしてください"; }
            $this->失敗($comment);
        }
        $this->成功();
    }

    private function より小さい($num, $unit = "", $comment = ""){
        $this->数($comment);
        if(!($this->value < $num)){
            if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}より小さくしてください"; }
            $this->失敗($comment);
        }
        $this->成功();
    }

    private function 成功(){
        return ($this->mode === "確認") ? true : $this;
    }

    private function 失敗($comment = "エラーが発生しました"){
        if($this->mode === "確認"){ return false; }
        if(is_callable(self::$コールバック)){ call_user_func(self::$コールバック, $comment, $this); }
        return $this;
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
}
