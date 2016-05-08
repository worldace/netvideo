<?php
//PHPの実行時間制限(単位は秒、0で無制限)
ini_set("max_execution_time", 3600);

if(!isset($_GET['url'])){ error(); }
if(!preg_match("/^https*:\/\//i", $_GET['url'])){ error(); }

foreach(getallheaders() as $name => $value){
    if(preg_match("/^Host$/i", $name)){ continue; }
    if(preg_match("/^Cookie$/i", $name)){ continue; }

    $reqest .= "$name: $value\r\n";
}

$fp = fopen($_GET['url'], 'rb', false, stream_context_create(array('http' => array('header'=> $reqest))));
if(!$fp){ error(); }

$meta = stream_get_meta_data($fp);
foreach($meta['wrapper_data'] as $value){
    if(preg_match("/^HTTP\/\d\.\d\s+20\d\s/i", $value)){
        $flag = true;
        header($value);
    }
    if(!$flag){ continue; }
    header($value);
}
if(!$flag){ error(); }

while(!feof($fp)) {
    print fread($fp, 8192);
}
fclose($fp);


function error(){
    header('HTTP', true, 400);
    exit;
}