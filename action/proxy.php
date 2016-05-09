<?php
//allow_url_fopenがonである必要がある。参考:http://doremi.s206.xrea.com/php/tips/http.html

//PHPの実行時間制限(単位は秒、0で無制限)
ini_set("max_execution_time", 3600);


if(!isset($_GET['url'])){ error(); }
if(!preg_match("|^https*://|i", $_GET['url'])){ error(); }
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

$fp = @fopen($_GET['url'], 'rb', false, stream_context_create($reqest));
if(!$fp){ error(); }

$meta = stream_get_meta_data($fp);
foreach($meta['wrapper_data'] as $value){
    if(preg_match("|^HTTP/[\d\.]+\s+20\d\s|i", $value)){
        $flag = true;
    }
    if($flag){ header($value); }
}
if(!$flag){ error(); }


while(ob_get_level()){ ob_end_clean(); }

while(!feof($fp)){
    print fread($fp, 8192);
}
fclose($fp);


function error(){
    header('HTTP', true, 400);
    exit;
}


function is_sameorigin($url){
    $query = parse_url($url); //$query['scheme'] $query['host'] $query['port']
 
    if(!isset($query['port'])){
        $query['port'] = ($query['scheme'] == "http") ? 80 : 443;
    }

    $server = array();
    $server['host'] = $_SERVER['HTTP_HOST'];
    $server['port'] = $_SERVER['SERVER_PORT'];
    $server['scheme'] = isset($_SERVER["HTTPS"]) ? "https" : "http";
    
    if(($server['scheme'] == $query['scheme']) and ($server['host'] == $query['host']) and ($server['port'] == $query['port'])){ return true; }
    else{ return false; }
}
