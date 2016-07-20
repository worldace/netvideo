<?php
//ブラウザからの要求をほぼそのまま相手に送り付けて、相手からの応答はそのままブラウザに返す
//allow_url_fopenが有効であること。参考:http://doremi.s206.xrea.com/php/tips/http.html

//PHPの実行時間制限(単位は秒、0で無制限)
ini_set("max_execution_time", 60*60*24);


if(!preg_match("|^https?://|i", $_GET['url'])){ error(); }
if(is_sameorigin($_GET['url'])){ error(); }


foreach(getallheaders() as $name => $value){
    if(preg_match("/^Host$/i", $name)){ continue; }
    if(preg_match("/^Cookie$/i", $name)){ continue; }
    $reqest_header .= "$name: $value\r\n";
}

$reqest = stream_context_create(['http' => ['method'=>$_SERVER['REQUEST_METHOD'], 'header'=>$reqest_header, 'content'=>file_get_contents("php://input")]]);

$fp = @fopen($_GET['url'], 'rb', false, $reqest) or error();

$response_header = array();
$meta = stream_get_meta_data($fp);
foreach(array_reverse($meta['wrapper_data']) as $value){
    if(preg_match("/^Set-Cookie/i", $value)){ continue; }
    array_unshift($response_header, $value);
    if(preg_match("|^HTTP/|i", $value)){ break; }
}
foreach($response_header as $header){ header($header); }

while(ob_get_level()){ ob_end_clean(); }
while(!feof($fp)){ print fread($fp, 8192); }
fclose($fp);


function error(){
    header('HTTP', true, 400);
    exit;
}


function is_sameorigin($url){
    $input = parse_url($url); //$input['scheme'] $input['host'] $input['port']
    if($input['port'] == ""){ $input['port'] = ($input['scheme'] == "http") ? 80 : 443; }

    $host   = $_SERVER['HTTP_HOST'];
    $port   = $_SERVER['SERVER_PORT'];
    $scheme = (filter_input(INPUT_SERVER, 'HTTPS', FILTER_VALIDATE_BOOLEAN)) ? "https" : "http";

    return (($input['scheme'] == $scheme) and ($input['host'] == $host) and ($input['port'] == $port)) ? true : false;
}
