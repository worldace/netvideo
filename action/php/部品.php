<?php
// http://musou.s38.xrea.com/php/parts.html

function 部品(){
    $引数   = func_get_args();
    $部品名 = array_shift($引数);
    return 部品::作成($部品名, $引数);
}

class 部品{
    private static $ディレクトリ = ".";
    private static $初期化済み = false;
    private static $キャッシュ;
    private static $windows;
    public  static $追加js;
    public  static $追加css;

    public static function 作成($部品名, $引数){
        if($部品名 === null){ throw new Exception('部品名がありません'); }
        if(!self::$初期化済み){ self::初期化(); }

        if(self::$キャッシュ[$部品名]["読み込み済み"] === true){
            $html = self::$キャッシュ[$部品名]["html"];
            $css  = self::$キャッシュ[$部品名]["css"];
            $js   = self::$キャッシュ[$部品名]["js"];
        }
        else{
            $path = self::$ディレクトリ . "/{$部品名}.php";
            if(self::$windows){ $path = addslashes(mb_convert_encoding($path, 'SJIS', 'UTF-8')); }
            require $path;
            self::$キャッシュ[$部品名]["読み込み済み"] = true;
            self::$キャッシュ[$部品名]["html"] = $html;
            self::$キャッシュ[$部品名]["css"]  = $css;
            self::$キャッシュ[$部品名]["js"]   = $js;
        }

        if($css) { self::$追加css[$部品名] = is_callable($css) ? call_user_func_array($css, $引数) : $css; }
        if($js)  { self::$追加js[$部品名]  = is_callable($js)  ? call_user_func_array($js,  $引数) : $js; }

        return is_callable($html) ? call_user_func_array($html, $引数) : $html;
    }

    public static function 終了処理(){
        if(!self::$追加js and !self::$追加css){
            ob_end_flush();
            return;
        }

        $buf = ob_get_contents();
        ob_end_clean();
        if(self::$追加js){
            $js     = "\n<script>\n" . implode(self::$追加js,"\n") . "\n</script>\n";
            $js_pos = strripos($buf, "</body>");
            if($js_pos >= 0){
                $buf = substr_replace($buf, $js, $js_pos, 0); //最後に出現する</body>の前にJSを挿入する
            }
            else{
                $buf .= $js;
            }
        }
        if(self::$追加css){
            $css     = "\n<style>\n " . implode(self::$追加css,"\n") . "\n</style>\n";
            $css_pos = stripos($buf, "</head>");
            if($css_pos >= 0){
                $buf = substr_replace($buf, $css, $css_pos, 0); //最初に出現する</head>の前にCSSを挿入する
            }
            else{
                $buf = $css . $buf;
            }
        }
        print $buf;
    }

    private static function 初期化(){
        self::$初期化済み = true;
        self::$windows = preg_match("/win/i", PHP_OS);
        ob_start();
        register_shutdown_function("部品::終了処理");
    }

    public static function 設定($dir = null){
        if($dir){ self::$ディレクトリ = $dir; }
        if(!self::$初期化済み){ self::初期化(); }
    }
}
