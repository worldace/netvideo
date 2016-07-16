<?php

クラスローダ();
部品::初期化("{$設定['ディレクトリ.action']}/parts");






//□第1引数
//・URLを構成するクエリ。"?action=video&id=10" という先頭が?の文字列か、 ["action"=>"video", "id"=>"10"] という連想配列
//・URLを構成する文字列。"test/index.html"という先頭が?以外の文字列
//・引数を省略したときはホームURLが返る

//□以下の3点の設定が必要
//$設定['URL.ホーム'] → ホームページのURL。最後のスラッシュは必要
//$設定['URL.短縮']   → 短縮URLを有効にするかどうかの真偽値
//$設定['URL.dindex'] → URLで index.php を省略する場合は""。省略しない場合は"index.php"
function geturl($query = ""){
    global $設定;
    $短縮対象アクション名 = "video";
    $短縮対象id名 = "id";
    
    if(!is_array($query)){
        if(preg_match("/^\?/", $query)){ parse_str($query, $query); }
        else{ return $設定['URL.ホーム'] . $query; }
    }
    if($query["action"] === $短縮対象アクション名 and $query[$短縮対象id名] and $設定['URL.短縮']){
        $短縮対象id値 = rawurlencode($query[$短縮対象id名]);
        unset($query["action"]);
        unset($query[$短縮対象id名]);
    }
    if(count($query)){ $output_query = "?" . http_build_query($query, "", "&"); }
    return ($短縮対象id値) ? $設定['URL.ホーム'].$短縮対象id値.$output_query : $設定['URL.ホーム'].$設定['URL.dindex'].$output_query;
}
