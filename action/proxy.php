<?php
//ブラウザからの要求をほぼそのまま相手に送り付けて、相手からの応答はそのままブラウザに返す
//allow_url_fopenが有効であること。参考:http://doremi.s206.xrea.com/php/tips/http.html

//PHPの実行時間制限(単位は秒、0で無制限)
ini_set("max_execution_time", 3600);


if(!preg_match("|^https?://|i", $_GET['url'])){ error(); }
if(is_sameorigin($_GET['url'])){ error(); }


foreach(getallheaders() as $name => $value){
    if(preg_match("/^Host$/i", $name)){ continue; }
    if(preg_match("/^Cookie$/i", $name)){ continue; }
    $reqest_header .= "$name: $value\r\n";
}

$reqest = array(
    'http' => array(
        'method'  => $_SERVER['REQUEST_METHOD'],
        'header'  => $reqest_header,
        'content' => file_get_contents("php://input")
    )
);

$fp = @fopen($_GET['url'], 'rb', false, stream_context_create($reqest)) or error();

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
    $q = parse_url($url); //$q['scheme'] $q['host'] $q['port']
    if($q['port'] == ""){ $q['port'] = ($q['scheme'] == "http") ? 80 : 443; }

    $s['host'] = $_SERVER['HTTP_HOST'];
    $s['port'] = $_SERVER['SERVER_PORT'];
    $s['scheme'] = isset($_SERVER["HTTPS"]) ? "https" : "http";
    
    return (($s['scheme'] == $q['scheme']) and ($s['host'] == $q['host']) and ($s['port'] == $q['port'])) ? true : false;
}
