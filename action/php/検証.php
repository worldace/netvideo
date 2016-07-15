<?php
// http://musou.s38.xrea.com/php/validate.html

class 検証{
    private $エラー関数;
    private $key;
    private $value;
    private $mode;
    private $method;

    public function __construct($mode, $value, $method = ""){
        $this->エラー関数 = (検証エラー関数) ? 検証エラー関数 : "エラー";
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
        return (is_numeric($this->value) and !preg_match("/^-?0+\d/", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 自然数($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}は1以上にしてください"; }
        return (preg_match("/^[1-9][0-9]*$/", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 自然数と0($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}は0以上にしてください"; }
        return (preg_match("/^(0|[1-9]\d*)$/", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 数字($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}に数字以外の文字が含まれています"; }
        return (preg_match("/^[0-9]+$/", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 英語($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}に英語以外の文字が含まれています"; }
        return (preg_match("/^[A-Za-z]+$/", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 英数字($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}に英数字以外の文字が含まれています"; }
        return (preg_match("/^[A-Za-z0-9]+$/", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function URL($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}にはURLを入力してください"; }
        return (preg_match("|^https?://.{4,}|i", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 画像データ($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}を取得できませんでした"; }
        return (getimagesizefromstring($this->value)[0] > 0) ? $this->成功() : $this->失敗($comment); //getimagesize()[0]:横サイズ [1]:縦サイズ [2]:GIFは1、JPEGは2、PNGは3
    }

    public function と同じ($value, $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}が{$value}でありません"; }
        return ($this->value == $value) ? $this->成功() : $this->失敗($comment);
    }

    public function 以上($num, $unit = "", $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}以上にしてください"; }
        return (is_numeric($this->value) and ($this->value >= $num)) ? $this->成功() : $this->失敗($comment);
    }

    public function 以下($num, $unit = "", $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}以下にしてください"; }
        return (is_numeric($this->value) and ($this->value <= $num)) ? $this->成功() : $this->失敗($comment);
    }

    public function より大きい($num, $unit = "", $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}より大きくしてください"; }
        return (is_numeric($this->value) and ($this->value > $num)) ? $this->成功() : $this->失敗($comment);
    }

    public function より小さい($num, $unit = "", $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}より小さくしてください"; }
        return (is_numeric($this->value) and ($this->value < $num)) ? $this->成功() : $this->失敗($comment);
    }

    public function 文字以上($num, $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}文字以上にしてください"; }
        return (mb_strlen($this->value,"UTF-8") < $num) ? $this->成功() : $this->失敗($comment);
    }

    public function 文字以下($num, $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}文字以内にしてください"; }
        return (mb_strlen($this->value,"UTF-8") > $num) ? $this->成功() : $this->失敗($comment);
    }

    private function 全角数字変換($num){
        $num = preg_replace("/^ー/u", "-", $num);
        $num = preg_replace("/．/u", ".", $num);
        $num = mb_convert_kana($num, "n", "utf-8");
        return $num;
    }

    private function 成功(){
        return ($this->mode === "確認") ? true : $this;
    }

    private function 失敗($comment = "エラーが発生しました"){
        if($this->mode === "確認"){ return false; }
        if(is_callable($this->エラー関数)){ call_user_func($this->エラー関数, $comment, $this); }
        return $this;
    }

    function __call($name, $args){
        if     (preg_match("/^([０-９]+)文?字以上$/u", $name, $m)) { return $this->文字以上($this->全角数字変換($m[1]), $args[0]); }
        else if(preg_match("/^([０-９]+)文?字以/u", $name, $m))    { return $this->文字以下($this->全角数字変換($m[1]), $args[0]); }
        else if(preg_match("/^(ー?[０-９．]+)(\w*)以上$/u", $name, $m)) { return $this->以上($this->全角数字変換($m[1]), $m[2], $args[0]); }
        else if(preg_match("/^(ー?[０-９．]+)(\w*)以/u", $name, $m))    { return $this->以下($this->全角数字変換($m[1]), $m[2], $args[0]); }
        else if(preg_match("/^(ー?[０-９．]+)(\w*)より大/u", $name, $m)){ return $this->より大きい($this->全角数字変換($m[1]), $m[2], $args[0]); }
        else if(preg_match("/^(ー?[０-９．]+)(\w*)より小/u", $name, $m)){ return $this->より小きい($this->全角数字変換($m[1]), $m[2], $args[0]); }
        else {
            if(preg_match("/^(ー?[０-９．]+)$/u", $name)){ $name = $this->全角数字変換($name); }
            return $this->と同じ($name, $args[0]);
        }
    }
}
