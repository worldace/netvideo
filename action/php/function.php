<?php
//======================================================
// ■共通して利用する関数群
// 
// 呼び出し元: ../../index.php
//======================================================


function 部品(){
    global $設定;

    $引数   = func_get_args();
    $部品名 = array_shift($引数);

    include_once("{$設定['actionディレクトリ']}/parts/{$部品名}.php");

    if($html){
        $parts_html = is_callable($html) ? call_user_func_array($html, $引数) : $html;
    }
    if($js){
        $設定['追加js'][$部品名]  = is_callable($js) ? call_user_func_array($js, $引数) : $js;
    }
    if($css){
        $設定['追加css'][$部品名] = is_callable($css) ? call_user_func_array($css, $引数) : $css;
    }

    return $parts_html;
}


function 部品初期化(){ //部品()を使用する時は出力前に必ず部品初期化()を行うこと
    $設定['追加js']  = [];
    $設定['追加css'] = [];
    ob_start();
    register_shutdown_function(function(){
        global $設定;
        if($設定['追加js'] or $設定['追加css']){
            $buf = ob_get_contents();
            ob_end_clean();
            if($設定['追加js']){
                $js  = implode($設定['追加js'], "\n");
                $buf = substr_replace($buf, "\n<script>\n$js</script>\n", strrpos($buf,"</body>"), 0); //最後に出現する</body>の前にJSを挿入する
            }
            if($設定['追加css']){
                $css = implode($設定['追加css'], "\n");
                $buf = substr_replace($buf, "\n<style>\n$css</style>\n", strpos($buf,"</head>"), 0);   //最初に出現する</head>の前にCSSを挿入する
            }
            print $buf;
        }
        else{
            ob_end_flush();
        }
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


function データベース接続($dsn = "", $user = "", $pass = "") {
    global $設定;
    static $pdo;

    if($dsn){
        $pdo = null;
        $設定['DBドライバ']   = $dsn;
        $設定['DBユーザ']     = $user;
        $設定['DBパスワード'] = $pass;
    }
    if(!isset($pdo)) {
        $pdo = new PDO($設定['DBドライバ'], $設定['DBユーザ'], $設定['DBパスワード'], array(
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => true,
            //PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
        ));
    }
    return $pdo;
}


/*
$bindvalue(配列:プレースホルダに割り当てる変数)は全て文字列型に変換されるので、$bindvalueには文字列型の変数のみ入れる。
数値型の場合はPHP側で値を検証して、$sql(SQL文)の中で展開させておくこと。(ToDo:独自の数値型プレースホルダーを作った方がよさそう。記号は@)
*/
function データベース実行($sql, $bindvalue = array()){
    $db = データベース接続();
    if(!$bindvalue){
        return $db->query($sql);
    }
    else{
        $stmt = $db->prepare($sql);
        $stmt -> execute($bindvalue);
        return $stmt;
    }
}


function データベース取得($sql, $bindvalue = array(), $mode = PDO::FETCH_ASSOC){
    $db = データベース接続();
    if(!$bindvalue){
        return $db->query($sql)->fetchAll($mode);
    }
    else{
        $stmt = $db->prepare($sql);
        $stmt -> execute($bindvalue);
        return $stmt->fetchAll($mode);
    }
}


function データベース行取得($sql, $bindvalue = array()){
    $db = データベース接続();
    if(!$bindvalue){
        return $db->query($sql)->fetch();
    }
    else{
        $stmt = $db->prepare($sql);
        $stmt -> execute($bindvalue);
        return $stmt->fetch();
    }
}


function データベース列取得($sql, $bindvalue = array()){
    $db = データベース接続();
    if(!$bindvalue){
        return $db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }
    else{
        $stmt = $db->prepare($sql);
        $stmt -> execute($bindvalue);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}


function データベースセル取得($sql, $bindvalue = array()){
    $db = データベース接続();
    if(!$bindvalue){
        return $db->query($sql)->fetchColumn();
    }
    else{
        $stmt = $db->prepare($sql);
        $stmt -> execute($bindvalue);
        return $stmt->fetchColumn();
    }
}


function データベース件数($sql, $bindvalue = array()){ //select count(列名) from テーブル名 where ...
    return データベースセル取得($sql, $bindvalue);
}


function データベース追加($sql, $bindvalue = array()){
    $db = データベース接続();
    if(!$bindvalue){
        $db->query($sql);
        return $db->lastInsertId();
    }
    else{
        $stmt = $db->prepare($sql);
        $stmt -> execute($bindvalue);
        return $db->lastInsertId();
    }
}


function データベース更新($sql, $bindvalue = array()){
    $db = データベース接続();
    if(!$bindvalue){
        return $db->exec($sql);
    }
    else{
        $stmt = $db->prepare($sql);
        $stmt -> execute($bindvalue);
        return $stmt->rowCount();
    }
}


function データベース削除($sql, $bindvalue = array()){
    return データベース更新($sql, $bindvalue);
}


function データベーステーブル作成($テーブル名, $テーブル定義, $PDOドライバ){
    //MySQLとSQLite両対応のテーブル作成
    //※$テーブル定義は「キー:列名」「値:型情報」の連想配列。MySQL互換であること

    foreach($テーブル定義 as $key => $value){
        $sql .= "$key $value,";
    }
    $sql = rtrim($sql, ',');

    //SQLiteの場合
    if(preg_match('/^sqlite/i', $PDOドライバ)){
       $sql = str_replace('auto_increment', 'autoincrement', $sql);
        データベース実行("create table IF NOT EXISTS $テーブル名 ($sql)");
    }
    //MySQLの場合
    else {
       $MySQL追加文 = " ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
       データベース実行("create table IF NOT EXISTS $テーブル名 ($sql) $MySQL追加文");
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


function 投稿文字列処理($str, $br = false){
    $str = trim($str); 
    $str = str_replace('>', '＞', $str); 
    $str = str_replace('<', '＜', $str);
    $str = str_replace('"', '”', $str);
    //$str = str_replace('&', '＆', $str);
    $str = str_replace("\0", "", $str);

    //改行処理
    if($br){ $str = str_replace(array("\r\n","\r","\n"), '<br>', $str); }
    else   { $str = str_replace(array("\r\n","\r","\n"), '', $str); }

    return $str;
}


function 日付変換($time = 0, $style = 0){

    if(!$time){ $time = $_SERVER['REQUEST_TIME']; }

    $曜日一覧 = array('日','月','火','水','木','金','土');
    $曜日 = $曜日一覧[date('w', $time)];

    switch($style){
        case 1  : return date("Y年n月j日({$曜日}) H:i", $time);
        case 2  : return date("c", $time);
        case 3  : return date("Y/m/d", $time);
        case 4  : return $曜日;
        case 5  : return date("Y年n月j日({$曜日})", $time);
        case 6  : return date("Y年n月j日", $time);
        default : return date("Y/m/d H:i", $time);
    }
}


function 経過時間($time = 0, $format = "Y/m/d H:i"){

    if(!$time){ $time = $_SERVER['REQUEST_TIME']; }

    $曜日一覧 = array('日','月','火','水','木','金','土');
    $曜日 = $曜日一覧[date('w', $time)];
    $format = str_replace('__', $曜日, $format);

    $時間差 = $_SERVER['REQUEST_TIME'] - $time;
    if($時間差 < 1){ $時間差 = 1; }
    switch($時間差){
        case $時間差 < 60     : return "{$時間差}秒前";
        case $時間差 < 3600   : return floor($時間差/60)   . "分前";
        case $時間差 < 86400  : return floor($時間差/3600) . "時間前";
        case $時間差 < 604800 : return floor($時間差/86400) . "日前";
        default: return date($format, $time);
    }
}


function GETなら(){
    if(strtolower($_SERVER['REQUEST_METHOD']) == 'get'){ return true; }
    else { return false; }
}


function POSTなら(){
    if(strtolower($_SERVER['REQUEST_METHOD']) == 'post'){ return true; }
    else { return false; }
}


function Ajaxなら(){
    if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){ return true; }
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


function ファイル一覧取得($path = "./"){
    $ファイル一覧 = array();
    if(!is_dir($path)){ return array(); }

    $handle = opendir($path);
    while ($file = readdir($handle)){
        if(is_file("$path/$file")){
            $ファイル一覧[] = $file;
        }
    }
    closedir($handle);

    sort($ファイル一覧);
    return $ファイル一覧;
}


function ディレクトリ一覧取得($path = "./"){
    $ディレクトリ一覧 = array();
    if(!is_dir($path)){ return array(); }

    $handle = opendir($path);
    while ($dir = readdir($handle)){
        if($dir == "." or $dir == ".."){ continue; }
        if(is_dir("$path/$dir")){
            $ディレクトリ一覧[] = $dir;
        }
    }
    closedir($handle);

    sort($ディレクトリ一覧);
    return $ディレクトリ一覧;
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
