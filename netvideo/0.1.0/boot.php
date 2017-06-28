<?php
//======================================================
// ■起動処理
//======================================================
new class{
    function __construct(){
        ini_set('session.cookie_httponly', 1);

        $_GET += ['action'=>'', 'id'=>''];
        if(設定['URL短縮']){
            $this->PATH_INFOをGETに代入('video');
        }

        自動読み込み(設定['アプリディレクトリ'].'/class/');
        部品::開始(設定['アプリディレクトリ'].'/../部品/');
        検査::$例外 = true;
    }


    function PATH_INFOをGETに代入(string $default_action) :void{
        assert(count(設定['URLキー名']) >= 2);

        $_SERVER['PATH_INFO'] = $_SERVER['PATH_INFO'] ?? '/';
        $pathinfo = explode("/", $_SERVER['PATH_INFO']);

        foreach(設定['URLキー名'] as $k => $v){
            $_GET[$v] = $pathinfo[$k+1] ?? '';
        }

        $k0 = 設定['URLキー名'][0];
        $k1 = 設定['URLキー名'][1];
        if(preg_match("/^\d+$/", $_GET[$k0])){
            $_GET[$k1] = $_GET[$k0];
            $_GET[$k0] = $default_action;
        }
    }
};
