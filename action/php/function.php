<?php
//======================================================
// ■共通して利用する関数群
// 
// 呼び出し元: ../../index.php
//======================================================


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


function エラー($str = ""){
    header('HTTP', true, 500);
    print $str;
    exit;
}


function 現在のURL($no_query = true){
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


function GETなら(){
    return (strtolower($_SERVER['REQUEST_METHOD']) == 'get') ? true : false;
}


function POSTなら(){
    return (strtolower($_SERVER['REQUEST_METHOD']) == 'post') ? true : false;
}


function 自然数なら($num){
    return (preg_match("/^[1-9][0-9]*$/", $num)) ? true : false;
}

function 整数なら($num){
    if (preg_match("/^0$/", $num)){ return true; }
    return (preg_match("/^[1-9][0-9]*$/", $num)) ? true : false;
}


function h($str = ""){
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}


function クラスローダ($dir){
    spl_autoload_register(function($class) use($dir){
        if(preg_match("/win/i", PHP_OS)){ $class = addslashes(mb_convert_encoding($class, 'SJIS', 'UTF-8')); }
        include "{$dir}/{$class}.php";
    });
}


function ファイル一覧($path = ".", $pattern = "*"){
    foreach(glob("$path/$pattern") as $file){
        if(is_file("$path/$file")){ $list[] = realpath("$path/$file"); }
    }
    return $list;
}


function ディレクトリ一覧($path = ".", $pattern = "*"){
    foreach(glob("$path/$pattern", GLOB_ONLYDIR) as $dir){
        $list[] = realpath("$path/$dir");
    }
    return $list;
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
