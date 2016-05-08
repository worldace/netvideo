<?php

//動画投稿を許可するユーザのOpenID「array("OpenID1","OpenID2");」。デフォルトは「array();」で全ユーザ許可。OpenIDの確認は→http://open.login.yahoo.co.jp/
$設定['許可ユーザ'] = array();

//基準ディレクトリの絶対パス (最後のスラッシュは不要)
$設定['ディレクトリ'] = __DIR__;

//actionディレクトリの絶対パス (最後のスラッシュは不要)
$設定['actionディレクトリ'] = $設定['ディレクトリ'] . '/action';

//contentディレクトリの絶対パス (最後のスラッシュは不要)
$設定['contentディレクトリ'] = $設定['ディレクトリ'] . '/content';
