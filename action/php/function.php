<?php
//======================================================
// ■共通して利用する関数群
// 
// 呼び出し元: ../../index.php
//======================================================

function 部品(){
    global $設定;
    $部品ディレクトリ = "{$設定['actionディレクトリ']}/parts"; //部品ファイルが置いてあるディレクトリ。絶対パス推奨。最後のスラッシュは不要

    $html = $css = $js = $parts_html = null;
    static $追加js;
    static $追加css;

    $引数   = func_get_args();
    $部品名 = array_shift($引数);

    if($部品名 === null){ throw new Exception('部品名がありません'); }
    if($部品名 === "__js__css"){ return [$追加js, $追加css]; }

    static $部品キャッシュ;
    if($部品キャッシュ[$部品名]["読み込み済み"] === true){
        $html = $部品キャッシュ[$部品名]["html"];
        $css  = $部品キャッシュ[$部品名]["css"];
        $js   = $部品キャッシュ[$部品名]["js"];
    }
    else{
        require("{$部品ディレクトリ}/{$部品名}.php");
        $部品キャッシュ[$部品名]["読み込み済み"] = true;
        $部品キャッシュ[$部品名]["html"] = $html;
        $部品キャッシュ[$部品名]["css"]  = $css;
        $部品キャッシュ[$部品名]["js"]   = $js;
    }

    if($html){ $parts_html       = is_callable($html) ? call_user_func_array($html,$引数) : $html; }
    if($js)  { $追加js[$部品名]  = is_callable($js)   ? call_user_func_array($js,  $引数) : $js; }
    if($css) { $追加css[$部品名] = is_callable($css)  ? call_user_func_array($css, $引数) : $css; }
    return $parts_html;
}


function 部品初期化(){
    ob_start();
    register_shutdown_function(function(){
        list($追加js, $追加css) = 部品("__js__css");
        if(!$追加js and !$追加css){
            ob_end_flush();
            return;
        }

        $buf = ob_get_contents();
        ob_end_clean();
        if($追加js){
            $js_pos = strripos($buf, "</body>");
            $js  = implode($追加js, "\n");
            if($js_pos !== false){
                $buf = substr_replace($buf, "\n<script>\n$js\n</script>\n", $js_pos, 0); //最後に出現する</body>の前にJSを挿入する
            }
            else{
                $buf .= "\n<script>\n$js\n</script>\n";
            }
        }
        if($追加css){
            $css_pos = stripos($buf, "</head>");
            $css = implode($追加css, "\n");
            if($css_pos !== false){
                $buf = substr_replace($buf, "\n<style>\n$css\n</style>\n", $css_pos, 0); //最初に出現する</head>の前にCSSを挿入する
            }
            else{
                $buf = "\n<style>\n$css\n</style>\n" . $buf;
            }
        }
        print $buf;
    });
}



function テキスト表示($str){
    header("Content-Type: text/plain; charset=UTF-8");
    print $str;
    exit;
}


function リダイレクト($url){
    $url = str_replace(array("\r\n","\r","\n"), '', $url);
    header("Location: $url");
    exit;
}


function エラー($str){
    global $設定;

    header("HTTP/1.0 400 Bad Request");
    header("Content-Type: text/html; charset=UTF-8");
    print $str;
    exit;
}


// http://musou.s38.xrea.com/php/pdoclass.html
function データベース($table, $driver = null, $user = null, $pass = null){
    return new データベース($table, $driver, $user, $pass);
}
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
        if($割当){
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
        }
        else{
            $stmt = self::$pdo -> query($SQL文);
        }
        return $stmt;
    }

    public function 取得(array $条件 = null, $取得タイプ = PDO::FETCH_ASSOC){
        list($追加文, $割当) = $this->追加SQL文($条件, "where");
        $SQL文 = "select * from {$this->テーブル} $追加文";
        return $this -> 実行($SQL文, $割当) -> fetchAll($取得タイプ);
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

        foreach((array)$検索ワード as $単語){ $割当1[] = "%$単語%"; }

        $列 = (array)$列;
        foreach($列 as $単列){ $this->文字列検証($単列); }
        if(preg_match("/sqlite/i", self::$標準ドライバ)){
            $concat文字列 = "(" . implode('||',$列) . ")";
        }
        else{
            $concat文字列 = "concat(" . implode(',',$列) . ")";
        }
        $検索SQL = implode(' and ', array_fill(0,count($割当1),"$concat文字列 like ?"));

        list($追加文, $割当2) = $this->追加SQL文($条件, "and");
        $割当 = array_merge($割当1, $割当2);

        $SQL文 = "select * from {$this->テーブル} where {$検索SQL} {$追加文} ";
        
        return $this -> 実行($SQL文, $割当) -> fetchAll();
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

        $DB名 = (self::$標準ドライバ) ? self::$標準ドライバ : "sqlite";
        if(preg_match('/^sqlite/i', $DB名)){ //SQLite用
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

    public function id列名($arg = null){
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
        
        if($条件["順序"] === "小さい順"){ $SQL文 .= " order by {$this->id列名} asc "; }
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
        return [$SQL文, $割当];
    }

    public static function 設定($driver, $user = null, $pass = null){
        self::$標準ドライバ   = $driver;
        self::$標準ユーザ     = $user;
        self::$標準パスワード = $pass;
    }
}


function URL作成($querystring = false){
    if($_SERVER["HTTPS"] != 'on') {
        $scheme = "http://";
        if($_SERVER['SERVER_PORT'] != 80) { $port = ":" . $_SERVER['SERVER_PORT']; }
    }
    else {
        $scheme = "https://";
        if($_SERVER['SERVER_PORT'] != 443){ $port = ":" . $_SERVER['SERVER_PORT']; }
    }

    if($querystring === false){
        $request_uri = preg_replace("/\?.*$/", "", $_SERVER['REQUEST_URI']);
        $url = $scheme . $_SERVER["HTTP_HOST"] . $port . $request_uri;
    }
    else{
        $url = $scheme . $_SERVER["HTTP_HOST"] . $port . $_SERVER['REQUEST_URI'];
    }

    return $url;
}


function GETなら(){
    if(strtolower($_SERVER['REQUEST_METHOD']) == 'get'){ return true; }
    else { return false; }
}


function POSTなら(){
    if(strtolower($_SERVER['REQUEST_METHOD']) == 'post'){ return true; }
    else { return false; }
}


function 自然数なら($num){
    if (preg_match("/^[1-9][0-9]*$/", $num)){ return true; }
    else{ return false; }
}

function 整数なら($num){
    if (preg_match("/^0$/", $num)){ return true; }
    if (preg_match("/^[1-9][0-9]*$/", $num)){ return true; }
    else{ return false; }
}


function h($str){
    if(!$str){ return; }
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}


function ディレクトリ作成($path, $name, $permission = 707){
    if(is_dir("$path/$name")){ return false; }

    mkdir("$path/$name");
    chmod("$path/$name", octdec($permission));
}


function 年月日ディレクトリ作成($dir, $time = 0){
    if(!$time){
        if($_SERVER['REQUEST_TIME']){ $time = $_SERVER['REQUEST_TIME']; }
        else{ $time = time();}
    }
    
    $年 = date('Y', $time);
    $月 = date('m', $time);
    $日 = date('d', $time);
    $permission = decoct(fileperms($dir) & 0777);

    ディレクトリ作成($dir, $年, $permission);
    ディレクトリ作成("$dir/$年", "$月$日", $permission);

    if(!is_dir("$dir/$年/$月$日")){ return false; }
    return "$dir/$年/$月$日";
}
