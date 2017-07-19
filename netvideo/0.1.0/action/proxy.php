<?php
//ブラウザからの要求をほぼそのまま相手に送り付けて、相手からの応答はそのままブラウザに返す
//allow_url_fopenが有効であること。参考: http://doremi.s206.xrea.com/php/tips/http.html

//PHPの実行時間制限(単位は秒、0で無制限)
ini_set("max_execution_time", 60*60*24);

//バッファ無効化
while(ob_get_level()){ ob_end_clean(); }


if(!isset($_GET['url']) or !preg_match("|^https?://|i", $_GET['url']) or オリジンが同じなら($_GET['url'])){
    proxyエラー();
}

foreach(getallheaders() as $k => $v){
    if(preg_match("/^(Host|Cookie)$/i", $k)){
        continue;
    }
    $request_header .= "$k: $v\r\n";
}

$context = stream_context_create([
    'http' => [
        'method'  => $_SERVER['REQUEST_METHOD'],
        'header'  => $request_header,
        'content' => file_get_contents("php://input"),
    ]
]);

$fp = @fopen($_GET['url'], 'rb', false, $context);
if($fp === false){
    proxyエラー();
}

foreach(array_reverse(stream_get_meta_data($fp)['wrapper_data']) as $v){
    if(preg_match("/^Set-Cookie/i", $v)){
        continue;
    }
    if(preg_match("|^HTTP/|i", $v)){
        break;
    }
    header($v);
}

while(!feof($fp)){
    print fread($fp, 8192);
}

exit;




function proxyエラー(){
    header('HTTP', true, 400);
    exit;
}


function オリジンが同じなら($url){
    $url = parse_url($url); //['scheme', 'host', 'port']
    if($url['port'] == ""){ $url['port'] = ($url['scheme'] == "http") ? 80 : 443; }

    $host   = $_SERVER['HTTP_HOST'];
    $port   = $_SERVER['SERVER_PORT'];
    $scheme = (filter_input(INPUT_SERVER, 'HTTPS', FILTER_VALIDATE_BOOLEAN)) ? "https" : "http";

    return (($url['scheme'] == $scheme) and ($url['host'] == $host) and ($url['port'] == $port)) ? true : false;
}
