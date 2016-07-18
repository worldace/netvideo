<?php
//======================================================
// ■汎用関数
// 
// 呼び出し元: "../../index.php"
//======================================================


function クラスローダ($dir = __DIR__){
    spl_autoload_register(function($class) use($dir){
        if(preg_match("/^win/i", PHP_OS)){ $class = addslashes(mb_convert_encoding($class, 'SJIS', 'UTF-8')); }
        $class = str_replace("_", "/", $class);
        include "{$dir}/{$class}.php";
    });
}


function データベース($table, $driver = null, $user = null, $pass = null){
    return new データベース($table, $driver, $user, $pass);
}


function 部品(){
    $引数   = func_get_args();
    $部品名 = array_shift($引数);
    return 部品::作成($部品名, $引数);
}


function 確認($value, $method = ""){
    return new 検証("確認", $value, $method);
}


function GET検証($key){
    return new 検証("検証", $key, "GET");
}


function POST検証($key){
    return new 検証("検証", $key, "POST");
}


function 検証($value, $method = ""){
    return new 検証("検証", $value, $method);
}


function エラー($str = ""){
    header('HTTP', true, 400);
    print $str;
    exit;
}


function テキスト表示($str = ""){
    header("Content-Type: text/plain; charset=utf-8");
    print $str;
    exit;
}


function JSON表示($json = [], $callback = null){
    header("Access-Control-Allow-Origin: *");
    if($callback){ //JSONP
        header("Content-Type: text/javascript; charset=utf-8");
        print $callback . "(" . json_encode($json) . ");";
    }
    else{ //JSON
        header("Content-Type: application/json; charset=utf-8");
        print json_encode($json);
    }
    exit;
}


function リダイレクト($url){
    $url = preg_replace("/[\r\n]/", "", $url);
    header("Location: $url");
    exit;
}


function ダウンロード($filepath, $filename = "", $data = "", $timeout = 0){
    if($timeout){ ini_set("max_execution_time", $timeout); }
    if($data){
        $filesize = strlen($data);
    }
    else{
        $filesize = filesize($filepath);
        if(!$filename){ $filename = basename($filepath); }
    }
    $filenameE = rawurlencode($filename);
    header("Content-Type: application/force-download");
    header("Content-Length: $filesize");
    header("Content-Disposition: attachment; filename=\"$filename\"; filename*=UTF-8''$filenameE");

    while(ob_get_level()){ ob_end_clean(); }
    ($data) ? print($data) : readfile($filepath);
}


function GET送信($url, array $querymap = [], array $header = []){
    if($querymap and preg_match("/\?/", $url)){ $url .= "&" . http_build_query($querymap, "", "&"); }
    else if($querymap){ $url .= "?" . http_build_query($querymap, "", "&"); }
    $request = stream_context_create(['http'=>['method'=>'GET', 'header'=>implode("\r\n", $header)]]);
    return @file_get_contents($url, false, $request);
}


function POST送信($url, array $querymap = [], array $header = []){
    $request = stream_context_create(['http'=>['method'=>'POST', 'header'=>implode("\r\n", $header), 'content'=>http_build_query($querymap, "", "&")]]);
    return @file_get_contents($url, false, $request);
}


function 現在のURL($no_query = false){
    if(filter_input(INPUT_SERVER, 'HTTPS', FILTER_VALIDATE_BOOLEAN)){
        $scheme = "https://";
        if($_SERVER['SERVER_PORT'] != 443){ $port = ":" . $_SERVER['SERVER_PORT']; }
    }
    else {
        $scheme = "http://";
        if($_SERVER['SERVER_PORT'] != 80) { $port = ":" . $_SERVER['SERVER_PORT']; }
    }
    $url = $scheme . $_SERVER["HTTP_HOST"] . $port . $_SERVER['REQUEST_URI'];
    if($no_query){ $url = preg_replace("/\?.*$/", "", $url); }
    return $url;
}


function ベースURL($url){
    return (substr_count($url, "/") === 2) ? $url."/" : dirname($url."a")."/";
}


function GETなら(){
    return (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') ? true : false;
}


function POSTなら(){
    return (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') ? true : false;
}


function 連想配列なら(array $array){
    return (array_values($array) !== $array) ? true : false;
}


function 日付($time = 0, $str = '[年]/[0月]/[0日] [0時]:[0分]:[0秒]'){
	if(!$time){ $time = time(); }
	$week = ['日','月','火','水','木','金','土'][date('w', $time)];
    $from = ['[年]','[月]','[0月]','[日]','[0日]','[時]','[0時]','[0分]','[0秒]','[曜日]'];
    $to   = ['Y'   ,'n'   ,'m'    ,'j'   ,'d'    ,'G'   ,'H'    ,'i'    ,'s'    ,$week];
	$str  = str_replace($from, $to, $str);
	$str  = str_replace('[分]', ltrim(date('i',$time),"0"), $str);
	$str  = str_replace('[秒]', ltrim(date('s',$time),"0"), $str);
	return date($str, $time);
}


function 経過時間($time){
    $時間差 = time() - $time;
    switch($時間差){
        case $時間差 < 1        : return "今";
        case $時間差 < 60       : return "{$時間差}秒前";
        case $時間差 < 3600     : return floor($時間差/60)."分前";
        case $時間差 < 86400    : return floor($時間差/3600)."時間前";
        case $時間差 < 2592000  : return floor($時間差/86400)."日前";
        case $時間差 < 31536000 : return floor($時間差/2592000)."ヶ月前";
        default: return floor($時間差/31536000)."年前";
    }
}


function h($str = ""){
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}


function 自動リンク($str = "", array $attrmap = []){
    foreach($attrmap as $name => $value){ $attr .= " $name=\"$value\""; }
    return preg_replace("|(https?://[^[:space:]\r\n]+)|i", "<a href=\"$1\"$attr>$1</a>", $str);
}


function ファイル一覧($path = ".", $pattern = "/./", $fullpath = false){
    foreach(glob("$path/*") as $file){
        if(is_file("$path/$file") and preg_match($pattern, $file)){ $list[] = ($fullpath) ? realpath("$path/$file") : $file; }
    }
    return (array)$list;
}


function ディレクトリ一覧($path = ".", $pattern = "/./", $fullpath = false){
    foreach(glob("$path/*", GLOB_ONLYDIR) as $dir){
        if(preg_match($pattern, $dir)){ $list[] = ($fullpath) ? realpath("$path/$dir") : $dir; }
    }
    return (array)$list;
}


function ディレクトリ作成($path, $permission = 707){
    if(!$path){ return false; }
    if(is_dir($path)){ return $path; }
    $mask = umask();
    umask(0);
    $result = mkdir($path, octdec($permission), true);
    umask($mask);
    return ($result) ? $path : false;
}


function ディレクトリ削除($path){
    if(!$path or !is_dir($path)){ return false; }
    foreach(array_diff(scandir($path), ['.','..']) as $file){
        (is_dir("$path/$file")) ? ディレクトリ削除("$path/$file") : unlink("$path/$file");
    }
    return rmdir($path);
}


function zip圧縮($zipfile, array $filemap){
    $zip = new ZipArchive();
    if(!$zip->open($zipfile, ZipArchive::CREATE)){ return false; }
    foreach($filemap as $name => $value){ $zip->addFromString($name, $value); } //$nameに/を含めるとディレクトリになる
    $zip->close();
    return $zipfile;
}


function キャッシュ保存($name, $data){
    $tempfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . get_current_user() . "-" . $name;
    file_put_contents($tempfile, serialize($data), LOCK_EX);
}


function キャッシュ取得($name){
    $tempfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . get_current_user() . "-" . $name;
    return (file_exists($tempfile)) ? unserialize(file_get_contents($tempfile)) : false;
}


function uuid($hyphen = false) { //uuid v4
    $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0x0fff)|0x4000,mt_rand(0,0x3fff)|0x8000,mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff));
    if(!$hyphen){ $uuid = str_replace("-", "", $uuid); }
    return $uuid;
}


function ランダム文字列($length = 8, $userfriendly = true){
    $str = "ABCDEFGHJKLMNPQRSTWXYZabcdefghkmnpqrstwxyz23456789";
    if(!$userfriendly){ $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; }
    $str = str_repeat($str, floor($length/2));
    return substr(str_shuffle($str), 0, $length);
}
