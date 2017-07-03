<?php
//======================================================
// ■起動処理
//======================================================
new class{
    function __construct(){
        ini_set('session.cookie_httponly', 1);

        //PATH_INFOの仕様＝action.id
        $_SERVER['PATH_INFO'] = $_SERVER['PATH_INFO'] ?? '/';
        preg_match("|^/?([^\.]*)\.?(.*)$|", $_SERVER['PATH_INFO'], $match);
        $_GET['action'] = $match[1];
        $_GET['id']     = $match[2];
        if(preg_match("/^\d+$/", $_GET['action'])){
            $_GET['id']     = $_GET['action'];
            $_GET['action'] = 'video';
        }

        自動読み込み(設定['アプリディレクトリ'].'/class/');
        部品::開始(設定['アプリディレクトリ'].'/../部品/');
        検査::$例外 = true;
    }
};
