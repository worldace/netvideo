<?php
//======================================================
// ■起動処理とアプリ固有関数
// 
// 呼び出し元: "./index.php"
//======================================================


クラスローダ();

部品::設定("{$_ENV['ディレクトリ.action']}/parts", true);



//■以下アプリ固有関数

//□第1引数
//ホームページを基準としたパス文字列。引数省略時はホームURLが返る

//□以下の4点の設定が必要
//$_ENV['URL.ホーム'] → ホームページのURL
//$_ENV['URL.短縮']   → 短縮URLを有効にするかどうかの真偽値
//$_ENV['URL.短縮名'] → 短縮URLの対象となるアクション名
//$_ENV['URL.短縮値'] → 短縮URLの対象となる値のキー名
function URL作成($query = ""){
    $query = htmlspecialchars($query, ENT_QUOTES, "UTF-8");

    if(preg_match("#^https?://#i", $query)){
        return $query;
    }
    $base  = (preg_match("|/$|", $_ENV['URL.ホーム'])) ? $_ENV['URL.ホーム'] : dirname($_ENV['URL.ホーム'])."/";
    $query = preg_replace("|^\.?/|", "", $query);
    if(preg_match("/^\?/", $query)){
        $query = str_replace("&amp;", "&", $query);
        parse_str(substr($query,1), $query);
    }
    elseif(!$query){
        return $_ENV['URL.ホーム'];
    }
    else{
        return $base.$query;
    }

    if($_ENV['URL.短縮'] and $query["action"] === $_ENV['URL.短縮名'] and $query[$_ENV['URL.短縮値']]){
        $短縮値 = rawurlencode($query[$_ENV['URL.短縮値']]);
        unset($query["action"]);
        unset($query[$_ENV['URL.短縮値']]);
    }
    if(count($query)){
        $output_query = "?" . http_build_query($query, "", "&");
    }
    return ($短縮値) ? $base.$短縮値.$output_query : $_ENV['URL.ホーム'].$output_query;
}


function 開発用の設定(){
    if($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' or $_SERVER['HTTP_HOST'] !== '127.0.0.1'){ return; }

    ini_set('error_reporting', E_ALL ^ E_NOTICE);
    ini_set('display_errors', 1);
}
