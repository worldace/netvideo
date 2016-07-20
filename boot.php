<?php
//======================================================
// ■起動処理とアプリ固有関数
// 
// 呼び出し元: "./index.php"
//======================================================

//■起動処理

クラスローダ();

部品::初期化("{$設定['ディレクトリ.action']}/parts");



//■アプリ固有関数

//□第1引数
//・URLを構成するクエリ。"?action=video&id=10" という先頭が?の文字列か、 ["action"=>"video", "id"=>"10"] という連想配列
//・URLを構成する文字列。"test/index.html"という先頭が?以外の文字列
//・引数を省略したときはホームURLが返る

//□以下の2点の設定が必要
//$設定['URL.ホーム'] → ホームページのURL
//$設定['URL.短縮']   → 短縮URLを有効にするかどうかの真偽値
function URL取得($query = ""){
    global $設定;
    $短縮対象アクション名 = "video";
    $短縮対象id名 = "id";

    $base = (preg_replace("|/$|", $設定['URL.ホーム'])) ? $設定['URL.ホーム'] : dirname($設定['URL.ホーム'])."/";

    if(!is_array($query)){
        if(preg_match("/^\?/", $query)){ parse_str(substr($query,1), $query); }
        else{ return ($query) ? $base.preg_replace("|^/|", "", $query) : $設定['URL.ホーム']; }
    }
    if($query["action"] === $短縮対象アクション名 and $query[$短縮対象id名] and $設定['URL.短縮']){
        $短縮対象id値 = rawurlencode($query[$短縮対象id名]);
        unset($query["action"]);
        unset($query[$短縮対象id名]);
    }
    if(count($query)){ $output_query = "?" . http_build_query($query, "", "&"); }
    return ($短縮対象id値) ? $base.$短縮対象id値.$output_query : $設定['URL.ホーム'].$output_query;
}


function 開発用の設定(){
    if($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' or $_SERVER['HTTP_HOST'] !== '127.0.0.1'){ return; }

    ini_set('error_reporting', E_ALL ^ E_NOTICE);
    ini_set('display_errors', 1);
}