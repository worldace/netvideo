<?php


クラスローダ("{$設定['ディレクトリ.action']}/php");
データベース::設定($設定['データベース.ドライバ'], $設定['データベース.ユーザ'], $設定['データベース.パスワード']);
部品初期化();