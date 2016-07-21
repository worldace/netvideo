<?php
//======================================================
// ■起動処理とアプリ固有関数
// 
// 呼び出し元: "./index.php"
//======================================================


クラスローダ();

部品::初期化("{$設定['ディレクトリ.action']}/parts");



//■以下アプリ固有関数

//□第1引数
//ホームページを基準としたパス文字列。引数省略時はホームURLが返る

//□以下の4点の設定が必要
//$設定['URL.ホーム'] → ホームページのURL
//$設定['URL.短縮']   → 短縮URLを有効にするかどうかの真偽値
//$設定['URL.短縮名'] → 短縮URLの対象となるアクション名
//$設定['URL.短縮値'] → 短縮URLの対象となる値のキー名
function URL作成($query = ""){
    global $設定;

    $base = (preg_match("|/$|", $設定['URL.ホーム'])) ? $設定['URL.ホーム'] : dirname($設定['URL.ホーム'])."/";

    if(preg_match("#^(https?:|ftp:|mailto:|data:|//)#i", $query)){ return $query; }
    if(preg_match("/^\?/", $query)){ parse_str(substr($query,1), $query); }
    else{ return ($query) ? $base.preg_replace("|^/|","",$query) : $設定['URL.ホーム']; }

    if($設定['URL.短縮'] and $query["action"] === $設定['URL.短縮名'] and $query[$設定['URL.短縮値']]){
        $短縮値 = rawurlencode($query[$設定['URL.短縮値']]);
        unset($query["action"]);
        unset($query[$設定['URL.短縮値']]);
    }
    if(count($query)){ $output_query = "?" . http_build_query($query, "", "&"); }
    return ($短縮値) ? $base.$短縮値.$output_query : $設定['URL.ホーム'].$output_query;
}


function 開発用の設定(){
    if($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' or $_SERVER['HTTP_HOST'] !== '127.0.0.1'){ return; }

    ini_set('error_reporting', E_ALL ^ E_NOTICE);
    ini_set('display_errors', 1);
}
