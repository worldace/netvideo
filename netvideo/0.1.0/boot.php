<?php
//======================================================
// ■起動処理
//======================================================
(function (){
    foreach($_ENV['ini'] as $k => $v){
        ini_set($k, $v);
    }

    //PATH_INFOの仕様 /action.id
    $_SERVER['PATH_INFO'] = $_SERVER['PATH_INFO'] ?? '/';
    preg_match("|^/?([^\.]*)\.?(.*)$|", $_SERVER['PATH_INFO'], $match);
    $_GET['action'] = $match[1];
    $_GET['id']     = $match[2];
    if(自然数なら($_GET['action'])){
        $_GET['id']     = $_GET['action'];
        $_GET['action'] = 'video';
    }

    自動読み込み($_ENV['アプリディレクトリ'].'class/');
    部品::開始($_ENV['アプリディレクトリ'].'../部品/');
})();
