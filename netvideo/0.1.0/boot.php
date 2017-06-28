<?php
//======================================================
// ■起動処理
//======================================================
new class{
    function __construct(){
        ini_set('session.cookie_httponly', 1);

        $_GET += ['action'=>'', 'id'=>''];
        if(設定['URL短縮']){
            $_SERVER['PATH_INFO'] = $_SERVER['PATH_INFO'] ?? '/';
            $this->PATH_INFOをGETに代入($_SERVER['PATH_INFO'], 'video');
        }

        自動読み込み(設定['アプリディレクトリ'].'/class/');
        部品::開始(設定['アプリディレクトリ'].'/../部品/');
        検査::$例外 = true;
    }

    function PATH_INFOをGETに代入(string $pathinfo, string $default_action) :void{
        $pathinfo = explode("/", $pathinfo);
        $_GET['action'] = $pathinfo[1] ?? '';
        $_GET['id']     = $pathinfo[2] ?? '';
        
        if($_GET['id'] === '' and preg_match("/^\d+$/", $_GET['action'])){
            $_GET['id']     = $_GET['action'];
            $_GET['action'] = $default_action;
        }
    }
};
