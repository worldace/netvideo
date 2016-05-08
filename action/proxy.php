<?php
//PHPの実行時間制限(単位は秒、0で無制限)
ini_set("max_execution_time", 60*60);
//ユーザエージェント名
ini_set("user_agent", 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36');

if(!isset($_GET['url'])){ error(); }
if(!preg_match("/^https*:\/\//i", $_GET['url'])){ error(); }

foreach (getallheaders() as $name => $value) {
    if(preg_match("/^Range$/i", $name){ $range = "$name: $value\r\n"; };
}
if($range){
    $option = array('http' => array('header'=> $range));
    $fp = fopen($_GET['url'], 'rb', false, stream_context_create($option));
    if(!$fp){ exit; }

    $meta = stream_get_meta_data($fp);
    $flag = false;
    foreach($meta['wrapper_data'] as $value){
        if(preg_match("/206 Partial Content/i", $value)){
            header($value);
            $flag = true;
        }
        if(!$flag){ continue; }
        
        if(preg_match("/^Content-Type/i", $value)){ header($value); }
        if(preg_match("/^Content-Length/i", $value)){ header($value); }
        if(preg_match("/^Content-Range/i", $value)){ header($value); }
    }
    while(!feof($fp)) {
        print fread($fp, 8192);
    }
    fclose($fp);
}
else{
    $header = my_get_headers($_GET['url']);
    if($header['last-status'] != 200){ error(); }
    $length = $header[$header['count']]['content-length'];
    $type   = $header[$header['count']]['content-type'];
    if($length){ header("Content-Length: $length"); }
    if($type)  { header("Content-Type: $type"); }
    else{ header("Content-Type: application/octet-stream"); }

    while(ob_get_level()){ ob_end_clean(); }
    readfile($_GET['url']);
}

function my_get_headers($url){
    $headers = get_headers($url);
    if(!$headers){ return $headers; }
    $res = Array();
    $c = -1;
    foreach($headers as $h){
        if(strpos($h, 'HTTP/') === 0){
            $res[++$c]['status-line'] = $h;
            $res[ $c ]['status-code'] = (int)strstr($h, ' ');
        }
        else{
            $sep = strpos($h, ': ');
            $res[$c][strtolower(substr($h, 0, $sep))] = substr($h, $sep+2);
        }   
    }
    $res['count'] = $c;
    $res['last-status'] = $res[$c]['status-code'];
    return $res;
    // http://exe.tyo.ro/2010/04/phpget_headers.html
}

function error(){
    header('HTTP', true, 400);
    exit;
}