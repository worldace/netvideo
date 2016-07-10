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
        if(!$comment and $this->key){ $comment = "{$this->key}を入力してください"; }
        return ($this->value !== null or $this->value !== "") ? $this->成功() : $this->失敗($comment);
    }

    public function 数($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}には数値を入力してください"; }
        return (is_numeric($this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 自然数($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}は1以上にしてください"; }
        return (preg_match("/^[1-9][0-9]*$/", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function URL($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}にはURLを入力してください"; }
        return (preg_match("|^https?://.{4,}|i", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 画像データ($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}を取得できませんでした"; }
        return (getimagesize("data:;base64,".$this->value)[0] > 0) ? $this->成功() : $this->失敗($comment); //getimagesize()[0]:横サイズ [1]:縦サイズ [2]:GIFは1、JPEGは2、PNGは3
    }

    private function 字以上($num, $comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}文字以上にしてください"; }
        return (mb_strlen($this->value,"UTF-8") < $num) ? $this->成功() : $this->失敗($comment);
    }

    private function 字以下($num, $comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}文字以内にしてください"; }
        return (mb_strlen($this->value,"UTF-8") > $num) ? $this->成功() : $this->失敗($comment);
    }

    private function 以上($num, $unit = "", $comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}以上にしてください"; }
        return (is_numeric($this->value) and ($this->value >= $num)) ? $this->成功() : $this->失敗($comment);
    }

    private function 以下($num, $unit = "", $comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}以下にしてください"; }
        return (is_numeric($this->value) and ($this->value <= $num)) ? $this->成功() : $this->失敗($comment);
    }

    private function より大きい($num, $unit = "", $comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}より大きくしてください"; }
        return (is_numeric($this->value) and ($this->value > $num)) ? $this->成功() : $this->失敗($comment);
    }

    private function より小さい($num, $unit = "", $comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}より小さくしてください"; }
        return (is_numeric($this->value) and ($this->value < $num)) ? $this->成功() : $this->失敗($comment);
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
        if     (preg_match("/^([０-９]+)文?字以上$/u", $name, $m)) { $this->字以上(mb_convert_kana($m[1], "n", "utf-8"), $args[0]); }
        else if(preg_match("/^([０-９]+)文?字以/u", $name, $m))    { $this->字以下(mb_convert_kana($m[1], "n", "utf-8"), $args[0]); }
        else if(preg_match("/^([０-９]+)(\w*)以上$/u", $name, $m)) { $this->以上(mb_convert_kana($m[1], "n", "utf-8"), $m[2], $args[0]); }
        else if(preg_match("/^([０-９]+)(\w*)以/u", $name, $m))    { $this->以下(mb_convert_kana($m[1], "n", "utf-8"), $m[2], $args[0]); }
        else if(preg_match("/^([０-９]+)(\w*)より大/u", $name, $m)){ $this->より大きい(mb_convert_kana($m[1], "n", "utf-8"), $m[2], $args[0]); }
        else if(preg_match("/^([０-９]+)(\w*)より小/u", $name, $m)){ $this->より小きい(mb_convert_kana($m[1], "n", "utf-8"), $m[2], $args[0]); }
        else { throw new Exception("検証関数に{$name}はありません"); }
    }
}
