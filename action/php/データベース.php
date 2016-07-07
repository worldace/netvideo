<?php
// http://musou.s38.xrea.com/php/pdoclass.html
class データベース{
    private static $標準ドライバ;
    private static $標準ユーザ;
    private static $標準パスワード;
    private static $現在のドライバ;
    private static $pdo;
    private $テーブル;
    private $id列名 = "id";
    public static $件数 = 31;

    public function __construct($table, $driver = null, $user = null, $pass = null){
        $this->テーブル($table);
        if($driver){ $this->接続($driver, $user, $pass); }
        else{
            if(!self::$pdo){ $this->接続(self::$標準ドライバ, self::$標準ユーザ, self::$標準パスワード); }
            else if(self::$現在のドライバ != self::$標準ドライバ){ $this->接続(self::$標準ドライバ, self::$標準ユーザ, self::$標準パスワード); }
        }
        return $this;
    }

    private function 接続($driver, $user = null, $pass = null){
        try{
            self::$pdo = new PDO($driver, $user, $pass, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            ]);
        } catch(Exception $e){ print "接続エラー。データベース::設定(ドライバ,ユーザID,パスワード)を再確認してください"; }
        self::$現在のドライバ = $driver;
        return $this;
    }

    public function 実行($SQL文, array $割当 = null){
        $stmt = self::$pdo -> prepare($SQL文);
        for($i = 1; $i <= count($割当); $i++){
            $type = gettype($割当[$i-1]);
            if($type === "integer" or $type === "boolean"){
                $stmt -> bindValue($i, $割当[$i-1], PDO::PARAM_INT);
            }
            else {
                $stmt -> bindValue($i, $割当[$i-1], PDO::PARAM_STR);
            }
        }
        $stmt -> execute();
        return $stmt;
    }

    public function 取得(array $条件 = null){
        list($追加文, $割当, $行タイプ) = $this->追加SQL文($条件, "where");
        $SQL文 = "select * from {$this->テーブル} $追加文";
        return $this -> 実行($SQL文, $割当) -> fetchAll($行タイプ);
    }

    public function 行取得($id){
        $SQL文 = "select * from {$this->テーブル} where {$this->id列名} = ?";
        return $this -> 実行($SQL文, [(int)$id]) -> fetch();
    }

    public function 列取得($列, array $条件 = null){
        $this->文字列検証($列);
        list($追加文, $割当) = $this->追加SQL文($条件, "where");
        $SQL文 = "select {$列} from {$this->テーブル} $追加文 ";
        return $this -> 実行($SQL文, $割当) -> fetchAll(PDO::FETCH_COLUMN);
    }

    public function セル取得($id, $列){
        $this->文字列検証($列);
        $SQL文 = "select {$列} from {$this->テーブル} where {$this->id列名} = ?";
        return $this -> 実行($SQL文, [(int)$id]) -> fetchColumn();
    }

    public function 件数(array $条件 = null){
        if($条件['式']){ $追加文 = "where {$条件['式']}"; }
        $SQL文 = "select count(*) from {$this->テーブル} $追加文";
        return $this -> 実行($SQL文, $条件['割当']) -> fetchColumn();
    }

    public function 検索($検索ワード, $列, array $条件 = null){

        foreach((array)$検索ワード as $単語){
            $単語 = addcslashes($単語, '_%');
            $割当1[] = "%$単語%";
        }

        $列 = (array)$列;
        foreach($列 as $単列){ $this->文字列検証($単列); }
        if(preg_match("/sqlite/i", self::$現在のドライバ)){
            $concat文字列 = "(" . implode('||',$列) . ")";
        }
        else{
            $concat文字列 = "concat(" . implode(',',$列) . ")";
        }
        $検索SQL = implode(' and ', array_fill(0,count($割当1),"$concat文字列 like ?"));

        list($追加文, $割当2, $行タイプ) = $this->追加SQL文($条件, "and");
        $割当 = array_merge($割当1, $割当2);

        $SQL文 = "select * from {$this->テーブル} where {$検索SQL} {$追加文} ";
        
        return $this -> 実行($SQL文, $割当) -> fetchAll($行タイプ);
    }

    public function 追加(array $data){
        foreach($data as $name => $value){
            $this->文字列検証($name);
            $into文1 .= "{$name},";
            $into文2 .= "?,";
            $割当[] = $value;
        }
        $into文1 = rtrim($into文1, ',');
        $into文2 = rtrim($into文2, ',');

        $SQL文 = "insert into {$this->テーブル} ($into文1) values ($into文2)";
        $this -> 実行($SQL文, $割当);
        return self::$pdo -> lastInsertId();
    }

    public function 更新($id, array $data){
        foreach($data as $name => $value){
            if(preg_match("/=/", $name)){
                $set文 .= "{$name},";
            }
            else{
                $this->文字列検証($name);
                $set文 .= "{$name}=?,";
                $割当[] = $value;
            }
        }
        $set文 = rtrim($set文, ',');
        $割当[] = (int)$id;

        $SQL文 = "update {$this->テーブル} set {$set文} where {$this->id列名} = ?";
        return $this -> 実行($SQL文, $割当) -> rowCount();
    }

    public function 削除($id){
        $SQL文 = "delete from {$this->テーブル} where {$this->id列名} = ?";
        return $this -> 実行($SQL文, [(int)$id]) -> rowCount();
    }

    public function 作成(array $テーブル定義){
        foreach($テーブル定義 as $name => $value){
            $this->文字列検証($name);
            $列情報 .= "$name $value,";
        }
        $列情報 = rtrim($列情報, ',');
        $SQL文 = "create table IF NOT EXISTS {$this->テーブル} ($列情報)";

        if(preg_match('/^sqlite/i', self::$現在のドライバ)){ //SQLite用
            $SQL文  = str_replace('auto_increment', 'autoincrement', $SQL文);
        }
        else { //MySQL用
            $SQL文  = str_replace('autoincrement', 'auto_increment', $SQL文);
            $SQL文 .= " ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
        }
        $this -> 実行($SQL文);
        return $this;
    }

    public function インデックス作成($列){
        $this->文字列検証($列);
        $SQL文  = "create index {$列}インデックス on {$this->テーブル} ($列)";
        $this -> 実行($SQL文);
        return $this;
    }

    public function トランザクション開始(){
        self::$pdo -> beginTransaction();
        return $this;
    }

    public function トランザクション終了(){
        self::$pdo -> commit();
        return $this;
    }

    public function トランザクション失敗(){
        self::$pdo -> rollBack();
        return $this;
    }

    public function テーブル($arg = null){
        if($arg){
            $this->文字列検証($arg);
            $this->テーブル = $arg;
            return $this;
        }
        else{
            return $this->テーブル;
        }
    }

    public function id($arg = null){
        if($arg){
            $this->文字列検証($arg);
            $this->id列名 = $arg;
            return $this;
        }
        else{
            return $this->id;
        }
    }

    private function 文字列検証($str){
        if(preg_match("/[[:cntrl:][:punct:][:space:]]/", $str)){ throw new Exception("引数に不正な文字列"); }
    }

    private function 追加SQL文(array $条件 = null, $WHEREorAND = "where"){
        $割当  = (array)$条件['割当'];
        if($条件["式"]){ $SQL文 = " $WHEREorAND {$条件['式']} "; }
        
        if($条件["順番"] === "小さい順"){ $SQL文 .= " order by {$this->id列名} asc "; }
        else{ $SQL文 .= " order by {$this->id列名} desc "; }
        
        if(!$条件["件数"]){ $条件["件数"] = self::$件数; }
        if(!$条件["位置"]){ $条件["位置"] = 0; }
        if($条件["件数"] === "∞"){
            $SQL文 .= " offset ? ";
            $割当[] = (int)$条件["位置"];
        }
        else{
            $SQL文 .= " limit ? offset ?";
            $割当[] = (int)$条件["件数"];
            $割当[] = (int)$条件["位置"];
        }

        if($条件['行タイプ'] === null){ $行タイプ = PDO::FETCH_ASSOC; }
        else{
            if($条件['行タイプ'] === "連想配列"){ $行タイプ = PDO::FETCH_ASSOC; }
            else if($条件['行タイプ'] === "配列"){ $行タイプ = PDO::FETCH_NUM; }
            else { $行タイプ = PDO::FETCH_CLASS; }
        }

        return [$SQL文, $割当, $行タイプ];
    }

    public static function 設定($driver, $user = null, $pass = null){
        self::$標準ドライバ   = $driver;
        self::$標準ユーザ     = $user;
        self::$標準パスワード = $pass;
    }
}


function データベース($table, $driver = null, $user = null, $pass = null){
    return new データベース($table, $driver, $user, $pass);
}
