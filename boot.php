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
//"test/index.html"という先頭が?以外の文字列
//"?action=video&id=10" という先頭が?の文字列
// ["action"=>"video", "id"=>"10"] という連想配列
//引数を省略したときはホームURLが返る

//□以下の4点の設定が必要
//$設定['URL.ホーム'] → ホームページのURL
//$設定['URL.短縮']   → 短縮URLを有効にするかどうかの真偽値
//$設定['URL.短縮名'] → 短縮URLの対象となるアクション名
//$設定['URL.短縮値'] → 短縮URLの対象となる値のキー名
function URL作成($query = ""){
    global $設定;

    $base = (preg_replace("|/$|", $設定['URL.ホーム'])) ? $設定['URL.ホーム'] : dirname($設定['URL.ホーム'])."/";

    if(!is_array($query)){
        if(preg_match("/^\?/", $query)){ parse_str(substr($query,1), $query); }
        else{ return ($query) ? $base.preg_replace("|^/|","",$query) : $設定['URL.ホーム']; }
    }
    if($query["action"] === $設定['URL.短縮名'] and $query[$設定['URL.短縮値']] and $設定['URL.短縮']){
        $短縮対象id値 = rawurlencode($query[$設定['URL.短縮値']]);
        unset($query["action"]);
        unset($query[$設定['URL.短縮値']]);
    }
    if(count($query)){ $output_query = "?" . http_build_query($query, "", "&"); }
    return ($短縮対象id値) ? $base.$短縮対象id値.$output_query : $設定['URL.ホーム'].$output_query;
}


function 開発用の設定(){
    if($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' or $_SERVER['HTTP_HOST'] !== '127.0.0.1'){ return; }

    ini_set('error_reporting', E_ALL ^ E_NOTICE);
    ini_set('display_errors', 1);
}
