<?php
//======================================================
// ■共通して利用する関数群
// 
// 呼び出し元: ../../index.php
//======================================================


function テキスト表示($str = ""){
    header("Content-Type: text/plain; charset=UTF-8");
    print $str;
    exit;
}


function JSON表示($json = []){
    header("Content-Type: application/json; charset=utf-8");
    print json_encode($json);
    exit;
}


function リダイレクト($url){
    $url = preg_replace("/[\r\n]/", "", $url);
    header("Location: $url");
    exit;
}


function エラー($str = ""){
    header("HTTP/1.0 400 Bad Request");
    header("Content-Type: text/html; charset=UTF-8");
    print $str;
    exit;
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


function h($str = ""){
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}


function クラスローダ($dir){
    $windows = preg_match("/win/i", PHP_OS);
    spl_autoload_register(function($class) use($dir,$windows){
        if($windows){ $class = addslashes(mb_convert_encoding($class, 'SJIS', 'UTF-8')); }
        include "{$dir}/{$class}.php";
    });
}


function ディレクトリ作成($path, $name, $permission = 707){
    if(!$path or !$name){ return false; }
    mkdir("$path/$name");
    chmod("$path/$name", octdec($permission));
}


function 年月日ディレクトリ作成($dir, $time = 0){
    if(!$time){ $time = ($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time(); }

    $年 = date('Y', $time);
    $月 = date('m', $time);
    $日 = date('d', $time);
    $permission = decoct(fileperms($dir) & 0777);

    ディレクトリ作成($dir, $年, $permission);
    ディレクトリ作成("$dir/$年", "$月$日", $permission);

    if(!is_dir("$dir/$年/$月$日")){ return false; }
    return "$dir/$年/$月$日";
}
