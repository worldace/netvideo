<?php
//======================================================
// ■起動処理
//======================================================

ini_set('session.cookie_httponly', 1);


$_GET += ['action'=>'', 'id'=>''];
if(!$_GET['id'] and preg_match("/^\d+$/", $_GET['action'])){
    $_GET['id']     = $_GET['action'];
    $_GET['action'] = 'video';
}


自動読み込み(設定['アプリディレクトリ'].'./class/');
部品::開始(設定['アプリディレクトリ'].'./../部品/');
検査::$例外 = true;

