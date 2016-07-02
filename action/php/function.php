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


function データベース接続($driver = "", $user = "", $pass = ""){
    static $pdo;

    if(!$driver){
        global $設定;
        $driver = $設定['データベース.ドライバ'];
        $user   = $設定['データベース.ユーザ'];
        $pass   = $設定['データベース.パスワード'];
    }
    else{
        $pdo = null;
    }
    if(!isset($pdo)) {
        $pdo = new PDO($driver, $user, $pass, array(
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
        ));
    }
    return $pdo;
}

function データベース実行($SQL文, $割当 = array(), $返却タイプ = 0){
    $pdo  = データベース接続();
    $stmt = $pdo -> prepare($SQL文);
    for($i = 1; $i <= count($割当); $i++){
        if(gettype($割当[$i-1]) === "integer"){ //int型はあらかじめ型変換しておくこと。(int)$value
            $stmt -> bindValue($i, $割当[$i-1], PDO::PARAM_INT);
        }
        else {
            $stmt -> bindValue($i, $割当[$i-1], PDO::PARAM_STR);
        }
    }
    $stmt -> execute();
    return ($返却タイプ) ? $pdo : $stmt;
}

function データベース取得($SQL文, $割当 = array(), $取得タイプ = PDO::FETCH_ASSOC){
    return データベース実行($SQL文, $割当) -> fetchAll($取得タイプ);
}

function データベース行取得($SQL文, $割当 = array()){
    return データベース実行($SQL文, $割当) -> fetch();
}

function データベース列取得($SQL文, $割当 = array()){
    return データベース実行($SQL文, $割当) -> fetchAll(PDO::FETCH_COLUMN);
}

function データベースセル取得($SQL文, $割当 = array()){
    return データベース実行($SQL文, $割当) -> fetchColumn();
}

function データベース件数($SQL文, $割当 = array()){ //select count(列名) from テーブル名
    return データベース実行($SQL文, $割当) -> fetchColumn();
}

function データベース追加($SQL文, $割当 = array()){
    return データベース実行($SQL文, $割当, 1) -> lastInsertId();
}

function データベース更新($SQL文, $割当 = array()){
    return データベース実行($SQL文, $割当) -> rowCount();
}

function データベース削除($SQL文, $割当 = array()){
    return データベース実行($SQL文, $割当) -> rowCount();
}


function データベース作成($テーブル名, $テーブル定義, $DB名){
    //※$テーブル定義は「キー:列名」「値:型情報」の連想配列。MySQL互換
    foreach($テーブル定義 as $name => $value){
        $列情報 .= "$name $value,";
    }
    $列情報 = rtrim($列情報, ',');
    $SQL文 = "create table IF NOT EXISTS $テーブル名 ($列情報)";

    if(!$DB名){
        global $設定;
        $DB名 = ($設定['データベース.ドライバ']) ? $設定['データベース.ドライバ'] : "sqlite";
    }

    if(preg_match('/^sqlite/i', $DB名)){ //SQLite用
        $SQL文 = str_replace('auto_increment', 'autoincrement', $SQL文);
        データベース実行($SQL文);
    }
    else { //MySQL用
        $SQL文 = str_replace('autoincrement', 'auto_increment', $SQL文);
        データベース実行("$SQL文 ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci");
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
