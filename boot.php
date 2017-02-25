<?php
//======================================================
// ■起動処理とアプリ固有関数
// 
// 呼び出し元: "./index.php"
//======================================================

ini_set('session.cookie_httponly', 1);

set_exception_handler(function($e){
    print $e;
});


自動読み込み("./action/class/");
部品::開始("./action/parts");
検査::$例外 = true;
開発用の設定();


//■以下アプリ固有関数

function 開発用の設定(){
    if($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' or $_SERVER['HTTP_HOST'] !== '127.0.0.1'){ return; }

    ini_set('error_reporting', E_ALL ^ E_NOTICE);
    ini_set('display_errors', 1);
}


//□第1引数
//クエリの連想配列・ホームページを基準としたパス文字列のどちらか。省略時はホームURLが返る

//□以下の4点の設定が必要
//$_ENV['URL']        → ホームページのURL
//$_ENV['URL.短縮']   → 短縮URLを有効にするかどうかの真偽値
//$_ENV['URL.短縮名'] → 短縮URLの対象となるアクション名
//$_ENV['URL.短縮値'] → 短縮URLの対象となる値のキー名
function URL作成($query = ""){
    $base = (preg_match("|/$|", $_ENV['URL'])) ? $_ENV['URL'] : dirname($_ENV['URL'])."/";

    if(is_string($query)){
        $query = str_replace(["<",">","'",'"',"\r","\n"], "", $query);
        $query = preg_replace("|^\.?/|", "", $query);
        return ($query) ? $base.$query : $_ENV['URL'];
    }

    if($_ENV['URL.短縮'] and $query["action"] === $_ENV['URL.短縮名'] and $query[$_ENV['URL.短縮値']]){
        $短縮値 = rawurlencode($query[$_ENV['URL.短縮値']]);
        unset($query["action"]);
        unset($query[$_ENV['URL.短縮値']]);
    }
    if(count($query)){
        $output_query = "?" . http_build_query($query, "", "&", PHP_QUERY_RFC3986);
    }
    return ($短縮値) ? $base.$短縮値.$output_query : $_ENV['URL'].$output_query;
}
