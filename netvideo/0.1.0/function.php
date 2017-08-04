<?php
// ■function.php http://musou.s38.xrea.com/php/



function テキスト表示(string $str) :void{
    header("Content-Type: text/plain; charset=utf-8");
    print $str;
    exit;
}


function JSON表示($data, $allow=null) :void{
    $allow = ($allow)  ?  implode(" ", (array)$allow)  :  "*";
    header("Access-Control-Allow-Origin: $allow");
    header("Access-Control-Allow-Credentials: true");
    if(isset($_GET['callback']) and is_string($_GET['callback'])){ //JSONP
        header("Content-Type: application/javascript; charset=utf-8");
        print $_GET['callback'] . "(" . json_encode($data, JSON_PARTIAL_OUTPUT_ON_ERROR) . ");";
    }
    else{ //JSON
        header("Content-Type: application/json; charset=utf-8");
        print json_encode($data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    }
    exit;
}


function RSS表示(array $items, array $channel) :void{ // http://www.futomi.com/lecture/japanese/rss20.html
    $tag = function ($name, $value){
        return "<$name>$value</$name>\n";
    };
    
    if(isset($channel["title"])){
        $rss .= $tag("title", h($channel['title']));
    }
    if(isset($channel["link"])){
        $rss .= $tag("link", h($channel['link']));
    }
    if(isset($channel["description"])){
        $rss .= $tag("description", h($channel['description']));
    }
    
    foreach($items as $item){
        $rss .= "<item>\n";
        if(isset($item["title"])){
            $rss .= $tag("title", h($item['title']));
        }
        if(isset($item["link"])){
            $rss .= $tag("link", h($item['link']));
        }
        if(isset($item["pubDate"])){
            $rss .= $tag("pubDate", date("r", $item["pubDate"]));
        }
        $rss .= "</item>\n";
    }
    
    header("Content-Type: application/xml; charset=UTF-8");
    print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<rss version=\"2.0\">\n<channel>\n{$rss}</channel>\n</rss>";
    exit;
}



function リダイレクト(string $url) :void{
    header("Location: $url");
    exit;
}


function route(string $method, array $files, $before=1) :bool{
    $method = strtoupper($method);
    if(!in_array($method, ['GET', 'POST', 'ANY'])){
        return 内部エラー("第1引数の指定メソッドは'GET'か'POST'か'ANY'のどれかにしてください");
    }
    if($method !== $_SERVER['REQUEST_METHOD']){
        return false;
    }
    while(count($files)){
        $before = require_once array_shift($files);
    }
    exit;
}


function 自動読み込み(string $dir=__DIR__) :void{
    if(!絶対パスなら($dir)){
        $dir = realpath($dir);
    }
    spl_autoload_register(function($class) use($dir){
        $class = str_replace(["_","\\"], "/", $class);
        if(file_exists("$dir/$class.php")){
            require_once "$dir/$class.php";
        }
    });
}


function require_cache(string $file){
    static $記憶 = [];

    if(!絶対パスなら($file)){
        $file = realpath($file);
    }
    if(!isset($記憶[$file])){
        $記憶[$file] = require($file);
    }
    
    if(is_object($記憶[$file])){
        return clone $記憶[$file];
    }
    else{
        return $記憶[$file];
    }
}


function 非同期処理(string $file, $data=null) :void{
    if(!is_file($file)){
        return;
    }

    $file = escapeshellarg($file);
    $data = escapeshellarg(json_encode($data, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_PARTIAL_OUTPUT_ON_ERROR));

    if(preg_match('/WIN/i', PHP_OS)){
        pclose(popen(sprintf('start php -f %s -- %s', $file, $data), 'r'));
    }
    else{
        exec(sprintf('php -f %s -- %s > /dev/null &', $file, $data));
    }
}


function 検査($var, $func, $message=null) :bool{
    if(is_callable($func)){
        $return = $func($var);
        if(!is_bool($return)){
            内部エラー("第2引数の関数はtrueかfalseを返してください");
        }
    }
    else{
        if(is_callable("検査::$func")){
            $return = 検査::$func($var);
        }
        else if(preg_match("/^(\d+)(文字|字|バイト)(以上|以下|以内|未満|より大きい|より小さい|と同じ)*$/u", $func, $match)){
            if(!isset($match[3])){
                $match[3] = "と同じ";
            }
            if($match[2] === "文字" or $match[2] === "字"){
                $return = 検査::文字数($var, (int)$match[1], $match[3]);
            }
            else{
                $return = 検査::バイト数($var, (int)$match[1], $match[3]);
            }
        }
        else{
            内部エラー("第2引数の特別関数名が間違っています");
        }
    }

    検査::$結果[] = $return ?: $message;
    return $return;
}


class 検査{
    public static $結果 = [];


    static function 失敗なら(&$result) :bool{
        $result = self::$結果;
        self::$結果 = [];

        foreach($result as $v){
            if($v !== true){
                return true;
            }
        }
        return false;
    }

    static function 開始() :void{
        self::$結果 = [];
    }


    static function 必須($v) :bool{
        return strlen($v) > 0;
    }
    static function 数($v) :bool{
        return (is_numeric($v) and !preg_match("/^-?0+\d/", $v));
    }
    static function 自然数($v) :bool{
        return preg_match("/^[1-9][0-9]*\z/", $v) > 0;
    }
    static function 整数($v) :bool{
        return preg_match("/^(0|[1-9]\d*)\z/", $v) > 0;
    }
    static function 数字($v) :bool{
        return preg_match("/^[0-9]+\z/", $v) > 0;
    }
    static function 英字($v) :bool{
        return preg_match("/^[A-Za-z]+\z/", $v) > 0;
    }
    static function 英数字($v) :bool{
        return preg_match("/^[A-Za-z0-9]+\z/", $v) > 0;
    }
    static function URL($v) :bool{
        return preg_match("|^https?://.{4,}|i", $v) > 0;
    }
    static function 画像データ($v) :bool{
        return getimagesizefromstring($v)[0] > 0; //getimagesize()[0]:横サイズ [1]:縦サイズ [2]:GIFは1、JPEGは2、PNGは3
    }
    static function UTF8($v) :bool{
        return preg_match('//u', $v); //mb_check_encoding($v, 'UTF-8')
    }
    static function 文字数(string $v, int $num, string $compare) :bool{
        return self::{$compare}(mb_strlen($v,"UTF-8"), $num);
    }
    static function バイト数(string $v, int $num, string $compare) :bool{
        return self::{$compare}(strlen($v), $num);
    }
    static function 以上(int $a, int $b) :bool{
        return $a >= $b;
    }
    static function 以下(int $a, int $b) :bool{
        return $a <= $b;
    }
    static function 以内(int $a, int $b) :bool{
        return $a <= $b;
    }
    static function より大きい(int $a, int $b) :bool{
        return $a > $b;
    }
    static function より小さい(int $a, int $b) :bool{
        return $a < $b;
    }
    static function 未満(int $a, int $b) :bool{
        return $a < $b;
    }
    static function と同じ(int $a, int $b) :bool{
        return $a === $b;
    }
}


function 整形(&$x, callable $func){
    $x = $func($x);
    return $x;
}


function 入力(string $name, $default=""){
    if($name === ""){
        return $default;
    }
    preg_match("/^(get|post|cookie)?\.?(.+)$/i", $name, $m);
    $name = $m[2];
    if($m[1]){
        $typeid = constant('INPUT_' . strtoupper($m[1]));
    }
    else{
        foreach([INPUT_GET, INPUT_POST, INPUT_COOKIE] as $typeid){
            if(filter_has_var($typeid, $name)){
                break;
            }
        }
    }

    $v = filter_input($typeid, $name);
    if($v === false){ //配列の場合
        return filter_input($typeid, $name, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    }
    else{
        return ($v === "" or is_null($v)) ? $default : $v;
    }
}


function テンプレート(string $file_, array $data_, bool $エスケープする_=true) :string{
    $h_ = function($arg) use (&$h_){
        if(is_array($arg)){
            return array_map($h_, $arg);
        }
        return htmlspecialchars($arg, ENT_QUOTES, "UTF-8", false);
    };
    
    if($エスケープする_){
        foreach((array)$data_ as $key1_ => $val_){
            $$key1_ = $h_($val_);
            $key2_  = "__" . $key1_;
            $$key2_ = $val_;
        }
    }
    else{
        foreach((array)$data_ as $key1_ => $val_){
            $$key1_ = $val_;
        }
    }
    ob_start();
    require $file_;
    return ob_get_clean();
}


function ダウンロード(string $file, string $filename=null, int $timeout=60*60*6) :void{
    if(preg_match('/^data:.*,/i', $file, $m)){
        $filesize = strlen($file) - strlen($m[0]);
        if(!$filename){
            $filename = "data.txt";
        }
    }
    else{
        if(!is_file($file)){
            内部エラー("ダウンロードさせるファイル $file は存在しません", "警告");
            return;
        }
        $filesize = filesize($file);
        if(!$filename){
            $filename = basename($file);
        }
    }
    ini_set("max_execution_time", $timeout);

    $filename = str_replace(['"',"'","\r","\n"], "", $filename);
    $filenameE = rawurlencode($filename);
    header("Content-Type: application/force-download");
    header("Content-Length: $filesize");
    header("Content-Disposition: attachment; filename=\"$filename\"; filename*=UTF-8''$filenameE");

    while(ob_get_level()){ ob_end_clean(); }
    readfile($file);
}


function 並列GET送信(array $url, int $並列数=5, array $option=[]) :array{
    $option = $option + [ // http://php.net/manual/ja/function.curl-setopt.php
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ];

    $mh = curl_multi_init();
    curl_multi_setopt($mh, CURLMOPT_PIPELINING, 1); // http://php.net/manual/ja/function.curl-multi-setopt.php
    curl_multi_setopt($mh, CURLMOPT_MAX_TOTAL_CONNECTIONS, $並列数);
    curl_multi_setopt($mh, CURLMOPT_MAX_HOST_CONNECTIONS, $並列数);

    foreach($url as $k => $v){
        $ch[$k] = curl_init($v);
        curl_setopt_array($ch[$k], $option);
        curl_multi_add_handle($mh, $ch[$k]);
    }

    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);

    $return = [];
    foreach($ch as $k => $v){
        //$info = curl_getinfo($ch[$key]); // http://php.net/manual/ja/function.curl-getinfo.php
        $return[$url[$k]] = curl_multi_getcontent($v);
        curl_multi_remove_handle($mh, $v);
    }

    curl_multi_close($mh);
    return $return;
}


function GET送信(string $url, array $querymap=null, array $request_header=[]){
    $_ENV['RESPONSE_HEADER'] = [];
    if($querymap){
        $url .= preg_match("/\?/", $url) ? "&" : "?";
        $url .= http_build_query($querymap, "", "&", PHP_QUERY_RFC3986);
    }
    $header = "";
    foreach($request_header as $k => $v){
        $k = str_replace([":", "\r", "\n"], "", $k);
        $v = str_replace(["\r", "\n"], "", $v);
        $header .= "$k: $v\r\n";
    }
    $context = stream_context_create([
        'http'=>[
            'method' => 'GET',
            'header' => $header,
        ]
    ]);
    $return = file_get_contents($url, false, $context);
    $_ENV['RESPONSE_HEADER'] = $http_response_header ?? [];
    return $return;
}


function POST送信(string $url, array $querymap=null, array $request_header=[]){
    $_ENV['RESPONSE_HEADER'] = [];
    $content = http_build_query((array)$querymap, "", "&");

    $request_header = [
        "content-type"   => "application/x-www-form-urlencoded; charset=UTF-8",
        "content-length" => strlen($content),
    ] + array_change_key_case($request_header);

    $header = "";
    foreach($request_header as $k => $v){
        $k = str_replace([":", "\r", "\n"], "", $k);
        $v = str_replace(["\r", "\n"], "", $v);
        $header .= "$k: $v\r\n";
    }
    $context = stream_context_create([
        'http'=>[
            'method' => 'POST',
            'header' => $header,
            'content' => $content,
        ]
    ]);
    $return = file_get_contents($url, false, $context);
    $_ENV['RESPONSE_HEADER'] = $http_response_header ?? [];
    return $return;
}


function ファイル送信(string $url, array $querymap=null, array $request_header=[]){
    $_ENV['RESPONSE_HEADER'] = [];
    $区切り = "__" . sha1(uniqid()) . "__";
    foreach((array)$querymap as $name => $value){
        $name = str_replace(['"', "\r", "\n"], "", $name);
        if(is_array($value)){
            foreach($value as $name2 => $value2){
                $name2  = str_replace(['"', "\r", "\n"], "", $name2);
                $value2 = is_resource($value2) ? stream_get_contents($value2) : file_get_contents($value2);
                if($value2 === false){
                    continue;
                }
                $content .= "--$区切り\r\n";
                $content .= "Content-Disposition: form-data; name=\"$name\"; filename=\"$name2\"\r\n";
                $content .= "Content-Type: " . MIMEタイプ($name2) . "\r\n\r\n";
                $content .= "$value2\r\n";
            }
        }
        else{
            $content .= "--$区切り\r\n";
            $content .= "Content-Disposition: form-data; name=\"$name\"\r\n\r\n";
            $content .= "$value\r\n";
        }
    }
    $content .= "--$区切り--\r\n";

    $request_header = [
        "content-type"   => "multipart/form-data; boundary=$区切り",
        "content-length" => strlen($content),
    ] + array_change_key_case($request_header);

    $header = "";
    foreach($request_header as $k => $v){
        $k = str_replace([":", "\r", "\n"], "", $k);
        $v = str_replace(["\r", "\n"], "", $v);
        $header .= "$k: $v\r\n";
    }

    $context = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => $header,
            'content' => $content
        ]
    ]);
    $return = file_get_contents($url, false, $context);
    $_ENV['RESPONSE_HEADER'] = $http_response_header ?? [];
    return $return;
}


function HEAD送信(string $url, array $querymap=null, array $request_header=[]){
    $_ENV['RESPONSE_HEADER'] = [];
    if($querymap){
        $url .= preg_match("/\?/", $url) ? "&" : "?";
        $url .= http_build_query($querymap, "", "&", PHP_QUERY_RFC3986);
    }
    $header = "";
    foreach($request_header as $k => $v){
        $k = str_replace([":", "\r", "\n"], "", $k);
        $v = str_replace(["\r", "\n"], "", $v);
        $header .= "$k: $v\r\n";
    }
    $context = stream_context_create([
        'http'=>[
            'method' => 'HEAD',
            'header' => $header,
        ]
    ]);
    $return = file_get_contents($url, false, $context);
    $_ENV['RESPONSE_HEADER'] = $http_response_header ?? [];

    foreach(array_reverse($_ENV['RESPONSE_HEADER']) as $v){
        if(preg_match("|^HTTP/\d+\.\d+\s+(\d+)|i", $v, $match)){
            $return = (int)$match[1];
            break;
        }
    }
    return $return;
}


function FILES詰め直し() :array{
    //['form-name'=>[['name'=>,'type'=>,'tmp_name'=>,'error'=>,'size=>']]]
    if(!isset($_FILES)){
        return [];
    }

    $return = [];
    $uploaded = false;
    foreach($_FILES as $index => $file) {
        if(!is_array($file['name'])) {
            if($file['error'] !== UPLOAD_ERR_NO_FILE){
                $uploaded = true;
            }
            $return[$index][] = $file;
            continue;
        }
        foreach($file['name'] as $idx => $name) {
            if($file['error'][$idx] !== UPLOAD_ERR_NO_FILE){
                $uploaded = true;
            }
            $return[$index][$idx] = [
                'name' => $name,
                'type' => $file['type'][$idx],
                'tmp_name' => $file['tmp_name'][$idx],
                'error' => $file['error'][$idx],
                'size' => $file['size'][$idx]
            ];
        }
    }
    return $uploaded ? $return : [];
}


function ファイル受信(string $dir, array $whitelist){
    $files = FILES詰め直し();
    
    if(!$files){
        return;
    }
    if(!is_dir($dir)){
        内部エラー("ディレクトリ $dir は存在しません", "警告");
        return false;
    }
    $dir = realpath($dir);

    foreach($whitelist as $k => $v){
        $v = str_replace(".", "", $v);
        $whitelist[$k] = strtolower($v);
    }

    foreach($files as $k1 => $v1){
        foreach($v1 as $k2 => $v2){
            if($v2['error'] !== UPLOAD_ERR_OK){
                continue;
            }
            $ext = pathinfo($v2["name"], PATHINFO_EXTENSION); //拡張子なしは空文字列
            $ext = strtolower($ext);
            if(!in_array($ext, $whitelist, true)){
                continue;
            }
            if($ext !== ""){
                $ext = ".$ext";
            }

            $savepath = $dir. DIRECTORY_SEPARATOR . uniqid(bin2hex(random_bytes(2))) . $ext;
            if(!move_uploaded_file($v2["tmp_name"], $savepath)){
                continue;
            }
            $return[$k1][$k2] = $savepath;
        }
    }
    return isset($return)  ?  $return  :  false;
}


function メール送信($送信先, string $送信元="", string $送信者="", string $題名="", string $本文="", array $添付=null, $cc="", $bcc="", array $add=null) :bool{
    $送信先 = str_replace(["\r","\n"," ",","], "", $送信先);
    $送信元 = str_replace(["\r","\n"," ",","], "", $送信元);
    $送信者 = str_replace(["\r","\n"], "", $送信者);
    $題名   = str_replace(["\r","\n"], "", $題名);
    $cc     = str_replace(["\r","\n"," ",","], "", $cc);
    $bcc    = str_replace(["\r","\n"," ",","], "", $bcc);
    $add    = str_replace(["\r","\n"], "", $add);

    $送信先 = implode(",", (array)$送信先);
    $題名   = mb_encode_mimeheader($題名, "jis");
    $body   = mb_convert_encoding($本文, "jis", "UTF-8");

    if($送信元 and $送信者){
        $header .= "From: " . mb_encode_mimeheader($送信者,"jis") . " <$送信元>\r\n";
    }
    else if($送信元){
        $header .= "From: $送信元\r\n";
    }

    if($cc) {
        $cc = implode(",", (array)$cc);
        $header .= "Cc: $cc\r\n";
    }

    if($bcc){
        $bcc = implode(",", (array)$bcc);
        $header .= "Bcc: $bcc\r\n";
    }

    if(is_array($add)){
        $header .= implode("\r\n", $add) . "\r\n";
    }

    if(is_array($添付)){
        $区切り = "__" . sha1(uniqid()) . "__";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"{$区切り}\"\r\n";

        $body  = "--{$区切り}\r\n";
        $body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\r\n\r\n";
        $body .= mb_convert_encoding($本文, "jis", "UTF-8") . "\r\n";

        foreach($添付 as $name => $value){
            $value = is_resource($value) ? stream_get_contents($value) : file_get_contents($value);
            if($value === false){
                continue;
            }
            $body .= "--{$区切り}\r\n";
            $body .= "Content-Type: " . MIMEタイプ($name) . "\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename=\"" . mb_encode_mimeheader($name, "jis") . "\"\r\n\r\n";
            $body .= chunk_split(base64_encode($value)) . "\r\n";
        }
        $body .= "--{$区切り}--\r\n";
    }
    return mail($送信先, $題名, $body, $header);
}


function FTPアップロード(array $upload, array $option) :array{ // http://php.net/ftp
    $return = [];
    $option = $option + [
        'ftp.パッシブモード' => true,
        'ftp.バイナリモード' => true,
        'ftp.ポート' => 21,
        'ftp.暗号化' => false,
    ];

    $ftp = ($option['ftp.暗号化'] and function_exists('ftp_ssl_connect')) ? 'ftp_ssl_connect' : 'ftp_connect';
    $ftp = $ftp($option['ftp.ホスト'], $option['ftp.ポート']);
    if(!$ftp){
        内部エラー("FTPサーバに接続できませんでした", "警告");
        return $return;
    }

    if(!ftp_login($ftp, $option['ftp.id'], $option['ftp.パスワード'])){
        内部エラー("FTPサーバにログインできませんでした", "警告");
        return $return;
    }
    ftp_pasv($ftp, $option['ftp.パッシブモード']);
    $転送モード = $option['ftp.バイナリモード'] ? FTP_BINARY : FTP_ASCII;

    $files = [];
    foreach($upload as $k => $v){
        if($k[-1] === '/'){
            @ftp_mkdir($ftp, $k);
        }
        else{
            $files[$k] = $v;
        }
    }
    foreach($files as $k => $v){
        if(ftp_put($ftp, $k, $v, $転送モード)){
            $return[$k] = $v;
        }
    }

    ftp_close($ftp);
    return $return;
}


function ホスト確認(string $host) :bool{
    if(preg_match("|^https?://|i", $host)){ //URLなら
        $host = parse_url($host, PHP_URL_HOST);
    }
    else if(filter_var($host, FILTER_VALIDATE_EMAIL)){ //メールアドレスなら
        $host = array_pop(explode("@", $host));
        return checkdnsrr($host, 'MX') || checkdnsrr($host, 'A') || checkdnsrr($host, 'AAAA');
    }
    return checkdnsrr($host, 'A') || checkdnsrr($host, 'AAAA');
}


function キャッシュ無効(){
    if(headers_sent()){
        return false;
    }
    header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
}


function URL(array $param=null) :string{
    assert(isset(設定['URL']));
    $url = 設定['URL'];

    if(!$param){
        return $url;
    }

    foreach($param as $k => $v){
        if(is_numeric($k)){
            $pathinfo[] = rawurlencode((string)$v);
        }
        else{
            $query[$k] = $v;
        }
    }
    if(isset($pathinfo)){
        if($url[-1] !== '/'){
            $url .= '/';
        }
        $url .= implode($_ENV['pathinfo.区切り文字'] ?? '/', $pathinfo);
    }

    if(isset($query)){
        $url .= "?" . http_build_query($query, "", "&", PHP_QUERY_RFC3986); // 設定['URL']で?を含むURLには非対応
    }
    return $url;
}


function PATH_INFO分解() :array{
    $pathinfo = $_SERVER['PATH_INFO'] ?? '';
    $pathinfo = ltrim($pathinfo, '/');
    $pathinfo = explode($_ENV['pathinfo.区切り文字'] ?? '/', $pathinfo);
    $return   = [];

    foreach($pathinfo as $v){
        if($v !== ""){
            $return[] = $v;
        }
    }
    return $return;
}


function PATH_INFO設定() :string{
    if(isset($_SERVER['PATH_INFO'])){
        return $_SERVER['PATH_INFO'];
    }
    if(!isset($_SERVER['DOCUMENT_ROOT'], $_SERVER['SCRIPT_FILENAME'], $_SERVER['REDIRECT_URL'])){
        $_SERVER['PATH_INFO'] = "";
        return $_SERVER['PATH_INFO'];
    }
    //サンプル: $_SERVER = ["SCRIPT_FILENAME"=>"/virtual/id/public_html/p/index.php", "DOCUMENT_ROOT"=>"/virtual/id/public_html", "REDIRECT_URL"=>"/p/312/8888"]; //※"REDIRECT_URL"の値はURLデコードされている
    $dir = substr_replace($_SERVER["SCRIPT_FILENAME"], "", 0, strlen($_SERVER["DOCUMENT_ROOT"]));
    $dir = dirname($dir);
    $_SERVER['PATH_INFO'] = (strlen($dir) === 1)  ?  $_SERVER['REDIRECT_URL']  :  substr_replace($_SERVER['REDIRECT_URL'], "", 0, strlen($dir));
    return $_SERVER['PATH_INFO'];
}


function 現在のURL(bool $cut_query=false) :string{
    $port   = "";
    if(filter_input(INPUT_SERVER, 'HTTPS', FILTER_VALIDATE_BOOLEAN)){
        $scheme = "https://";
        if($_SERVER['SERVER_PORT'] != 443){
            $port = ":" . $_SERVER['SERVER_PORT'];
        }
    }
    else {
        $scheme = "http://";
        if($_SERVER['SERVER_PORT'] != 80){
            $port = ":" . $_SERVER['SERVER_PORT'];
        }
    }
    $url = $scheme . $_SERVER["HTTP_HOST"] . $port . $_SERVER['REQUEST_URI'];
    return $cut_query ? preg_replace("/\?.*$/","",$url) : $url;
}


function ホームURL(string $url) :string{
    $parsed = explode("/", $url);
    if(!isset($parsed[2])){
        内部エラー("$url はURL文字列ではありません", "警告");
        return $url;
    }
    return $parsed[0] . "//" . $parsed[2] . "/";
}


function トップURL(string $url) :string{
    $url = preg_replace("/\?.*/", "", $url);
    return (substr_count($url, "/") === 2) ? $url."/" : dirname($url."a")."/";
}


function Windowsなら() :bool{
    return preg_match("/^WIN/i", PHP_OS);
}


function 絶対パスなら(string $path) :bool{
    if(DIRECTORY_SEPARATOR === '/'){
        return $path[0] === '/';
    }
    else{
        return ($path[1] === ':' or $path[0] === '\\' or $path[0] === '/');
    }
}


function GETなら() :bool{
    return filter_input(INPUT_SERVER, "REQUEST_METHOD") === 'GET';
}


function POSTなら() :bool{
    return filter_input(INPUT_SERVER, "REQUEST_METHOD") === 'POST';
}


function 開発環境なら() :bool{
    //display_errorsはstring型 "stderr"の扱いが未定。filter_var($bool, FILTER_VALIDATE_BOOLEAN)でも検証できる
    return !in_array(ini_get("display_errors"), ["","0","no","off","false"], true);
}


function PHP≧(string $str) :bool{
    return version_compare(PHP_VERSION, $str) >= 0;
}


function 自然数なら($num, bool $zero=false) :bool{
    if(is_int($num)){
        if(($num > 0) or ($zero and $num === 0)){
            return true;
        }
    }
    elseif(is_string($num)){
        if(preg_match("/\A[1-9][0-9]*\z/", $num) or ($zero and $num === '0')){
            return true;
        }
    }
    return false;
}


function str_match(string $needle, string $haystack, &$match=null) :bool{
    $match = strpos($haystack, $needle);
    return $match !== false;
}


function str_replace_once(string $needle, string $replace, string $haystack) :string{
    $pos = strpos($haystack, $needle); 
    if($pos === false){
        return $haystack;
    }
    return substr_replace($haystack, $replace, $pos, strlen($needle));
}

/*
function preg_match_replace(string $regex, string $replace, string $haystack, &$match = null){
    if(preg_match($regex, $haystack, $match)){
        return preg_replace($regex, $replace, $haystack);
    }
    else{
        return $haystack;
    }
}
*/

function 日付(string $str='[年]/[0月]/[0日] [0時]:[0分]', int $time=0) :string{
    if(!$time){
        $time = time();
    }
    $week = ['日','月','火','水','木','金','土'][date('w', $time)];
    $from = ['[年]','[月]','[0月]','[日]','[0日]','[時]','[0時]','[0分]','[0秒]','[曜日]'];
    $to   = ['Y'   ,'n'   ,'m'    ,'j'   ,'d'    ,'G'   ,'H'    ,'i'    ,'s'    ,$week];
    $str  = str_replace($from, $to, $str);
    $str  = str_replace('[分]', ltrim(date('i',$time),"0"), $str);
    $str  = str_replace('[秒]', ltrim(date('s',$time),"0"), $str);
    return date($str, $time);
}


function 経過(int $time) :string{
    $時間差 = time() - $time;
    switch($時間差){
        case $時間差 < 1        : return "今";
        case $時間差 < 60       : return "{$時間差}秒前";
        case $時間差 < 3600     : return floor($時間差/60)."分前";
        case $時間差 < 86400    : return floor($時間差/3600)."時間前";
        case $時間差 < 2592000  : return floor($時間差/86400)."日前";
        case $時間差 < 31536000 : return floor($時間差/2592000)."ヶ月前";
        default: return floor($時間差/31536000)."年前";
    }
}


function カレンダー配列(int $年=null, int $月=null) :array{
    if(!$年 or !$月){
        $date = new \DateTime('first day of');
    }
    else{
        $date = new \DateTime("{$年}-{$月}");
    }

    $曜日 = $date->format('w');
    $日数 = $date->format('t');
    $週   = 0;

    for($i = $曜日; $i > 0; $i--){
        $return[$週][] = '';
    }
    for($i = 1; $i <= $日数; $i++){
        if($曜日 > 6){
            $曜日 = 0;
            $週++;
        }
        $曜日++;
        $return[$週][] = $i;
    }
    for($i = $曜日; $i <= 6; $i++){
        $return[$週][] = '';
    }
    return $return;
}


function 日本語設定() :void{
    if(!preg_match("/^WIN/i", PHP_OS)){
        setlocale(LC_ALL, 'ja_JP.UTF-8'); // windows -> 'Japanese_Japan.932';
    }
    ini_set("default_charset", "UTF-8");
    ini_set("mbstring.language", "Japanese");
    ini_set("mbstring.detect_order", "UTF-8,SJIS-win,eucJP-win,ASCII");
    ini_set("date.timezone", "Asia/Tokyo");
}


function h($arg){
    if(is_string($arg)){
        $arg = htmlspecialchars($arg, ENT_QUOTES, "UTF-8", false);
    }
    else if(is_array($arg)){
        array_walk_recursive($arg, function(&$v){
            $v = htmlspecialchars($v, ENT_QUOTES, "UTF-8", false);
        });
    }
    return $arg;
}


function 改行置換($arg, string $replace=""){
    if(is_string($arg)){
        $arg = preg_replace("/\r\n|\n|\r/", $replace, $arg);
    }
    else if(is_array($arg)){
        array_walk_recursive($arg, function(&$v) use($replace){
            $v = preg_replace("/\r\n|\n|\r/", $replace, $v);
        });
    }
    return $arg;
}



function 制御文字削除($arg, $LF){ // http://blog.sarabande.jp/post/52701231276
    if(is_string($arg)){
        $arg = preg_replace("/\t/", "    ", $arg);
        $arg = preg_replace("/\xC2[\x80-\x9F]/", "", $arg); //Unicode制御文字
        $arg = ($LF === true)  ?  preg_replace("/(?!\n)[[:cntrl:]]/u", "", $arg)  :  preg_replace("/[[:cntrl:]]/u", "", $arg);
    }
    else if(is_array($arg)){
        array_walk_recursive($arg, function(&$v, $k) use($LF){
            $v = preg_replace("/\t/", "    ", $v);
            $v = preg_replace("/\xC2[\x80-\x9F]/", "", $v); //Unicode制御文字
            if(is_array($LF)){
                $bool = in_array($k, $LF, true);
            }
            else{
                $bool = ($LF === true);
            }
            $v = $bool  ?  preg_replace("/(?!\n)[[:cntrl:]]/u", "", $v)  :  preg_replace("/[[:cntrl:]]/u", "", $v);
        });
    }
    return $arg;
}


function タグ(string $tag, array $attr=[]) :string{
    $閉じる   = true;
    $単独タグ = false;
    if(preg_match("|^\!|", $tag)){
        $閉じる = false;
        $tag = ltrim($tag, "!");
    }
    if(preg_match("/[^a-zA-Z0-9\-]/", $tag)){ 
        内部エラー("タグ名に $tag は使用できません", "警告");
        return "";
    }
    if(in_array($tag, ["br","wbr","hr","img","col","base","link","meta","input","keygen","area","param","embed","source","track","command"], true)){
        $単独タグ = true;
    }

    $return = "<$tag" . 属性文字列($attr) . ">";
    
    if($単独タグ === false and isset($attr['本文'])){
        $return .= htmlspecialchars($attr['本文'], ENT_QUOTES, "UTF-8", false);
    }
    if($単独タグ === false and $閉じる === true){
        $return .= "</$tag>";
    }

    return $return;
}


function 属性文字列(array $attr=[]) :string{
    $str = "";
    foreach($attr as $key => $val){
        if($key === "本文"){
            continue;
        }
        if(preg_match("/[^a-zA-Z\-]/", $key)){
            内部エラー("属性名に $key は使用できません", "警告");
            continue;
        }
        if(in_array($key, ["src", "href", "action", "formaction", "poster"], true)){ //cite, srcset
            if(strlen($val) > 0 and !preg_match("%^(https*://|/|\./|#)%", $val)){
                $val = "./" . $val;
            }
        }
        $val = htmlspecialchars($val, ENT_QUOTES, "UTF-8", false);
        $str .= " $key=\"$val\"";
    }
    return $str;
}


function 自動リンク($arg, array $attr=[]){
    $attr_str = 属性文字列($attr);
    if(is_string($arg)){
        $arg = preg_replace("|(https?://[^[:space:]　\r\n<>]+)|ui", "<a href=\"$1\"$attr_str>$1</a>", $arg);
    }
    else if(is_array($arg)){
        array_walk_recursive($arg, function(&$v) use($attr_str){
            $v = preg_replace("|(https?://[^[:space:]　\r\n<>]+)|ui", "<a href=\"$1\"$attr_str>$1</a>", $v);
        });
    }
    return $arg;
}


function 配列HTML(array $array, array $attr=[]) :string{
    $firstkey = key($array);

    if(is_array($array[$firstkey])){
        $tag = "<tr>\n<th></th>\n<th>" . implode("</th>\n<th>",h(array_keys($array[$firstkey]))) . "</th>\n</tr>\n";
    }

    foreach($array as $key1 => $value1){
        $tag .= "<tr>\n<th>" . h($key1) . "</th>\n";
        foreach((array)$value1 as $key2 => $value2){
            $tag .= "<td>" . h($value2) . "</td>\n";
        }
        $tag .= "</tr>\n";
    }
    $attr_str = 属性文字列($attr);
    return "<table$attr_str>\n$tag\n</table>";
}


function 連想配列なら($array) :bool{
    return (is_array($array) and array_values($array) !== $array);
}


/*
function 配列探索($array, bool $leafonly = false) :Generator{
    foreach($array as $k => $v){
        if(is_iterable($v) or is_object($v)){
            if(!$leafonly){
                yield $k => $v;
            }
            yield from 配列探索($v, $leafonly);
        }
        else{
            yield $k => $v;
        }
    }
}
*/
function 配列探索($array, bool $枝要素を含む=false) :RecursiveIteratorIterator{
    $option = $枝要素を含む  ?  RecursiveIteratorIterator::SELF_FIRST  :  RecursiveIteratorIterator::LEAVES_ONLY;
    return new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array), $option);
}


function パーミッション(string $path, string $permission=null) :string{
    if(!file_exists($path)){
        内部エラー("パス $path は存在しません", "警告");
        return "";
    }
    if(!preg_match("/^0/", $permission) and $permission >= 100 and $permission <= 777){
        chmod($path, octdec($permission));
    }
    return decoct(fileperms($path) & 0777);
}


function ファイル一覧(string $dir, $再帰=true) :array{
    static $length = 0;
    $return = [];
    $再帰 = (int)$再帰;
    if($再帰 < 2){
        if(!is_dir($dir)){
            内部エラー("ディレクトリ $dir は存在しません", "警告");
            return $return;
        }
        $dir    = realpath($dir);
        $length = strlen($dir) + 1;
        if(preg_match("/^WIN/i", PHP_OS)){
            $dir = str_replace("\\", "/", $dir);
        }
    }
    foreach(array_diff(scandir($dir), ['.','..']) as $file){
        $path     = "$dir/$file";
        $relative = substr($path, $length);
        if(is_file($path)){
            $return[$relative] = $path;
        }
        elseif(is_dir($path) and $再帰){
            $再帰++;
            $return = array_merge($return, ファイル一覧($path, $再帰));
        }
    }
    return $return;
}


function ディレクトリ一覧(string $dir, $recursive=true) :array{
    static $strlen = 0;
    $return = [];
    $recursive = (int)$recursive;
    if($recursive < 2){
        if(!is_dir($dir)){
            内部エラー("ディレクトリ $dir は存在しません", "警告");
            return $return;
        }
        $dir = realpath($dir);
        $strlen = strlen($dir) + 1;
        if(preg_match("/^WIN/i", PHP_OS)){
            $dir = str_replace("\\", "/", $dir);
        }
    }
    foreach(array_diff(scandir($dir), ['.','..']) as $file){
        $path = "$dir/$file";
        $relative = substr($path, $strlen);
        if(is_dir($path)){
            $return[$relative.'/'] = $path.'/';
            if($recursive){
                $recursive++;
                $return = array_merge($return, ディレクトリ一覧($path, $recursive));
            }
        }
    }
    return $return;
}


function パス一覧(string $dir, $recursive=true) :array{
    static $strlen = 0;
    $return = [];
    $recursive = (int)$recursive;
    if($recursive < 2){
        if(!is_dir($dir)){
            内部エラー("ディレクトリ $dir は存在しません", "警告");
            return $return;
        }
        $dir = realpath($dir);
        $strlen = strlen($dir) + 1;
        if(preg_match("/^WIN/i", PHP_OS)){
            $dir = str_replace("\\", "/", $dir);
        }
    }
    foreach(array_diff(scandir($dir), ['.','..']) as $file){
        $path = "$dir/$file";
        $relative = substr($path, $strlen);
        if(is_dir($path)){
            $return[$relative.'/'] = $path.'/';
            if($recursive){
                $recursive++;
                $return = array_merge($return, パス一覧($path, $recursive));
            }
        }
        else{
            $return[$relative] = $path;
        }
    }
    return $return;
}


function ディレクトリ作成(string $dir, string $permission="707"){
    if(is_dir($dir)){
        パーミッション($dir, $permission);
        return $dir;
    }
    $mask = umask();
    umask(0);
    $result = mkdir($dir, octdec($permission), true);
    umask($mask);
    return ($result) ? $dir : false;
}


function ディレクトリ削除(string $dir){
    if(!is_dir($dir)){
        内部エラー("ディレクトリ $dir は存在しません", "警告");
        return false;
    }
    foreach(array_diff(scandir($dir), ['.','..']) as $file){
        (is_dir("$dir/$file")) ? ディレクトリ削除("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir) ? $dir : false;
}


function zip圧縮(string $zipfile, $filemap){
    $zip = new \ZipArchive(); // http://php.net/ziparchive
    if($zip->open($zipfile, ZipArchive::CREATE) !== true){
        内部エラー("ZIPファイル $zipfile を作成できません", "警告");
        return false;
    }
    if(is_string($filemap)){
        $path = realpath($filemap);
        if($path === false){
            内部エラー("ディレクトリ $filemap は存在しません", "警告");
            return false;
        }
        foreach(パス一覧($path) as $k => $v){
            is_dir($v) ? $zip->addEmptyDir($k) : $zip->addFile($v, $k);
        }
    }
    else{
        foreach($filemap as $k => $v){
            is_resource($v) ? $zip->addFromString($k, stream_get_contents($v)) : $zip->addFile($v, $k);
        }
    }
    $zip->close();
    return $zipfile;
}


function zip追加(string $zipfile, array $filemap){
    $zip = new \ZipArchive(); // http://php.net/ziparchive
    if($zip->open($zipfile) !== true){
        内部エラー("ZIPファイル $zipfile を開けません", "警告");
        return false;
    }
    foreach($filemap as $k => $v){
        is_resource($v) ? $zip->addFromString($k, stream_get_contents($v)) : $zip->addFile($v, $k);
    }
    $zip->close();
    return $zipfile;
}


function zip解凍(string $zipfile, string $解凍先="") :array{
    $return= [];

    $zip = new \ZipArchive(); // http://php.net/ziparchive
    if($zip->open($zipfile) !== true){
        内部エラー("ZIPファイル $zipfile を開けません", "警告");
        return $return;
    }

    $解凍先  = $解凍先 ? realpath($解凍先) : realpath(dirname($zipfile));
    if($解凍先 === false){
        内部エラー("解凍先ディレクトリが存在しません", "警告");
        return $return;
    }
    $解凍先 .= DIRECTORY_SEPARATOR;

    for($i = 0;  $i < $zip->numFiles;  $i++){
        $name   = $zip->getNameIndex($i, ZipArchive::FL_ENC_RAW);
        $encode = mb_detect_encoding($name, ["utf-8", "sjis-win", "eucjp"]);
        if($encode !== 'UTF-8'){
            $name = mb_convert_encoding($name, "utf-8", $encode);
        }

        $dir = ($name[-1] === '/') ? $解凍先.$name : $解凍先.dirname($name);
        if(!is_dir($dir)){
            mkdir($dir, 0755, true);
        }
        if($name[-1] === '/'){
            continue;
        }
        if(file_put_contents($解凍先.$name, $zip->getStream($zip->getNameIndex($i))) !== false){ // $zip->getFromIndex($i) という方法もあるけど
            $return[] = $解凍先.$name;
        }
    }
    $zip->close();
    return $return;
}


function 一時保存(string $name, $data){
    $tempfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . get_current_user() . "_" . md5($name);
    $result = file_put_contents($tempfile, serialize($data), LOCK_EX);
    return ($result === false) ? false : $name;
}


function 一時取得(string $name, $default = false){
    $tempfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . get_current_user() . "_" . md5($name);
    return (is_file($tempfile)) ? unserialize(file_get_contents($tempfile)) : $default;
}


function JSON保存(string $file, $data){
    $prefix = preg_match("/\.php$/i", $file) ? "<?php\n" : "";
    $result = file_put_contents($file, $prefix.json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR), LOCK_EX);
    return ($result === false) ? false : $file;
}


function JSON取得(string $file){
    $json = file_get_contents($file);
    if($json === false){
        return false;
    }
    $json = preg_replace("/^<\?php\s*/i", "", $json);
    return json_decode($json, true);
}


function 配列保存(string $file, array $array){
    $return = file_put_contents($file, "<?php\nreturn " . var_export($array,true) . ";", LOCK_EX);
    return ($return === false) ? false : $file;
}


function XML取得(string $xml) :array{
    $xml = ltrim($xml);
    if(!preg_match("/^</", $xml)){
        $xml = file_get_contents($xml);
        if($xml === false){
            return [];
        }
    }
    $xml = preg_replace("/&(?!([a-zA-Z0-9]{2,8};)|(#[0-9]{2,5};)|(#x[a-fA-F0-9]{2,4};))/", "&amp;" ,$xml);
    $SimpleXML = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOBLANKS | LIBXML_NOCDATA | LIBXML_NONET | LIBXML_COMPACT | LIBXML_PARSEHUGE);
    return json_decode(json_encode([$SimpleXML->getName()=>$SimpleXML]), true);
}


function CSV取得(string $path, $encode=null, string $区切り=null, string $囲い='"') :Generator{
    $ini = ini_get("auto_detect_line_endings");
    ini_set("auto_detect_line_endings", true);

    $fp = fopen($path, "rb");

    ini_set("auto_detect_line_endings", $ini);

    if($fp === false){
        内部エラー("CSVファイル $path が開けません", "警告");
        return;
    }
    $sample = fread($fp, 4096);
    rewind($fp);


    //文字コード検知
    if($encode === true){
        $encode = mb_detect_encoding($sample, ["utf-8", "sjis-win", "eucjp-win", "ascii", "ISO-2022-JP"]);
        if(!$encode){
            $encode = "utf-8";
        }
    }
    if(preg_match("/^utf-?8/i", $encode)){
        $encode = null;
    }

    //区切り検知
    if(!$区切り){
        $sample  = substr($sample, 0, 256);
        $count_c = preg_match_all("/,/" , $sample);
        $count_t = preg_match_all("/\t/", $sample);
        if(!$count_c and !$count_t){
            内部エラー("CSVファイルの区切り文字を検知できませんでした", "警告");
            return;
        }
        $区切り = ($count_c > $count_t)  ?  ","  :  "\t";
    }

    $i = 0;
    while(($csv = CSV行取得($fp, $区切り, $囲い)) !== false){
        if($csv === ['']){
            $csv = [];
        }
        if($encode){
            mb_convert_variables("utf-8", $encode, $csv);
        }
        yield $i => $csv;
        $i++;
    }
    fclose($fp);
}


function CSV行取得(&$handle, $d=',', $e='"'){
    $d = preg_quote($d);
    $e = preg_quote($e);
    $line = "";

    if(!is_resource($handle)){
        return false;
    }

    while(!feof($handle)){
        $line .= fgets($handle);
        if(preg_match_all("/$e/", $line) % 2 === 0){
            break;
        }
    }

    $count  = preg_match_all(sprintf('/(%s[^%s]*(?:%s%s[^%s]*)*%s|[^%s]*)%s/', $e,$e,$e,$e,$e,$e,$d,$d), preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, rtrim($line)), $match);
    $return = $match[1];

    for($i = 0;  $i < $count;  $i++){
        $return[$i] = preg_replace(sprintf('/^%s(.*)%s$/s', $e,$e), '$1', $return[$i]);
        $return[$i] = str_replace("$e$e", $e, $return[$i]);
    }

    return empty($line) ? false : $return;
}


function CSV作成($array, $改行変換="\n", $常に囲む=null, string $d=',', string $e='"') :string{
    $return = "";
    if(!is_iterable($array) and !is_object($array)){
        return $return;
    }
    
    foreach($array as $line){
        if(!is_iterable($line) and !is_object($line)){
            continue;
        }
        $newline = [];
        foreach($line as $cell){
            $cell = (string)$cell;
            $cell = preg_replace("/\r\n|\n|\r/", $改行変換, $cell);
            if($常に囲む === true or (strlen($cell) and !is_numeric($cell))){
                $cell = str_replace($e, "$e$e", $cell);
                $cell = $e . $cell . $e;
            }
            $newline[] = $cell;
        }
        $return .= implode($d, $newline) . "\r\n";
    }
    return $return;
}


function fromphp($data, string $name='fromphp') :string{
    $json  = json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
    $valid = preg_match("/^[a-zA-Z_\$\x7f-\xff][a-zA-Z0-9_\$\x7f-\xff]*/", $name);
    return ($json !== false and $valid)  ?  "<script>var $name = $json;</script>\n"  :  "";
}


function 当たり($確率) :bool{
    $確率 = (float)$確率;
    if($確率 <= 0){
        return false;
    }
    if($確率 >= 100){
        return true;
    }

    $i = mt_rand(1, round(100/$確率*100000));
    return $i <= 100000;
}


function uuid(bool $hyphen=false) :string{ //uuid v4 http://php.net/manual/en/function.uniqid.php#94959
    $format = $hyphen  ?  '%04x%04x-%04x-%04x-%04x-%04x%04x%04x'  :  '%04x%04x%04x%04x%04x%04x%04x%04x';
    return sprintf($format, mt_rand(0,0xffff),mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000, mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff));
}


function ID発行(bool $more=false) :string{
    [$micro, $sec] = explode(" ", microtime());
    $micro = substr($micro, 2, 6);
    $rand  = mt_rand(1000, 5202); //5202より大きいと12桁になる
    $return = base_encode("$rand$micro$sec");
    if($more === true){
        $return = パスワード発行(5) . $return;
        $return = preg_replace("/^\d/", "a", $return);
    }
    return $return;
}


function パスワード発行(int $length=8, bool $userfriendly=false) :string{
    $chars = $userfriendly  ?  'abcdefghijkmnprstwxyz2345678'  :  '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $max = strlen($chars) - 1;
    for($i = 0;  $i < $length;  $i++){
        $return .= $chars[mt_rand(0, $max)];
    }
    return $return;
}


function パスワード変換(string $password) :string{
    return password_hash($password, PASSWORD_DEFAULT);
}


function パスワード認証(string $password, string $hash) :bool{
    return password_verify($password, $hash);
}


function 暗号化(string $str, string $key) :string{
    $iv = openssl_random_pseudo_bytes(16); // openssl_cipher_iv_length('aes-128-cbc') == 16
    return bin2hex($iv) . openssl_encrypt($str, 'aes-128-cbc', $key, 0, $iv); //先頭32バイトがiv
}


function 復号化(string $str, string $key){
    $iv = substr($str, 0, 32);
    return openssl_decrypt(substr($str, 32), 'aes-128-cbc', $key, 0, hex2bin($iv));
}


function jwt発行(array $data, string $key) :string{
    $header     = ['typ'=>'jwt', 'alg'=>'HS256'];
    $segments[] = base64_encode_urlsafe(json_encode($header), JSON_PARTIAL_OUTPUT_ON_ERROR);
    $segments[] = base64_encode_urlsafe(json_encode($data), JSON_PARTIAL_OUTPUT_ON_ERROR);
    $sign       = hash_hmac('sha256', implode('.', $segments), $key, true);
    $segments[] = base64_encode_urlsafe($sign);

    return implode('.', $segments);
}


function jwt認証(string $jwt, string $key){
    if(substr_count($jwt, ".") !== 2) {
        return false;
    }

    list($headb64, $datab64, $cryptob64) = explode('.', $jwt);
    $header = json_decode(base64_decode_urlsafe($headb64));
    $data   = json_decode(base64_decode_urlsafe($datab64), true);
    $sign   = base64_decode_urlsafe($cryptob64);

    if(!$header or !$data) {
        return false;
    }
    if($header->alg !== "HS256"){
        return false;
    }
    if($sign !== hash_hmac('sha256', "$headb64.$datab64", $key, true)){
        return false;
    }

    return $data;
}


function base64_encode_urlsafe(string $input) :string{
    return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
}


function base64_decode_urlsafe(string $input) :string{
    $remainder = strlen($input) % 4;
    if($remainder){
        $padlen = 4 - $remainder;
        $input .= str_repeat('=', $padlen);
    }
    return base64_decode(strtr($input, '-_', '+/'));
}


function base_encode($val, int $base=62) :string{
    if($base < 2 or $base > 62){
        内部エラー("進数は2～62の間で指定してください");
        return "";
    }
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    do {
        $mem = bcmod($val, $base);
        $str = $chars[$mem] . $str;
        $val = bcdiv(bcsub($val, $mem), $base);
    } while(bccomp($val,0) > 0);

    return $str;
}


function base_decode(string $str, int $base=62) :string{
    if($base < 2 or $base > 62){
        内部エラー("進数は2～62の間で指定してください");
        return "";
    }
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $len = strlen($str);
    $val = "0";
    $arr = array_flip(str_split($chars));
    for($i = 0;  $i < $len;  $i++){
        $val = bcadd($val, bcmul($arr[$str[$i]], bcpow($base, $len-$i-1)));
    }

    return $val;
}


function ベーシック認証(callable $認証関数, string $realm="member only"){
    if(isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW'])){
        if($認証関数($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) === true){
            return $_SERVER['PHP_AUTH_USER'];
        }
    }
    header("WWW-Authenticate: Basic realm=\"$realm\"");
    header("HTTP/1.0 401 Unauthorized");
    return false;
}


function セッション削除() :void{
    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    $_SESSION = [];

    $param = session_get_cookie_params();
    setcookie(session_name(), '', 0, $param["path"], $param["domain"], $param["secure"], $param["httponly"]);

    session_destroy();
}


/*
攻撃パターンが偽フォームの場合、$tokenの値を当てなければいけない(かつ被害者がCookieを有していること)
攻撃パターンが偽Ajaxの場合、Cookieが送信できない(ただし被害者サーバーがAccess-Control-Allow-Originで攻撃サイトを指定していて、Access-Control-Allow-Credentialsがtrueだと送信できる)
*/
function CSRFタグ() :string{ // ajax: http://d.hatena.ne.jp/hasegawayosuke/20130302/p1
    $token = uuid();
    setcookie('csrf-token', $token, 0, '/');
    return sprintf('<input type="hidden" name="csrf-token" value="%s">', $token) . "\n";
}

function CSRF確認() :bool{
    setcookie('csrf-token', '', 0, '/');
    if(!empty($_COOKIE['csrf-token']) and !empty($_POST['csrf-token']) and $_COOKIE['csrf-token'] === $_POST['csrf-token']){
        return true;
    }
    return false;
}


function MIMEタイプ(string $path) :string{ // http://www.iana.org/assignments/media-types/media-types.xhtml
    $list = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'bmp'  => 'image/bmp',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'txt'  => 'text/plain',
        'htm'  => 'text/html',
        'html' => 'text/html',
        'css'  => 'text/css',
        'xml'  => 'text/xml',
        'csv'  => 'text/csv',
        'tsv'  => 'text/tab-separated-values',
        'js'   => 'application/javascript',
        'json' => 'application/json',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt'  => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'pdf'  => 'application/pdf',
        'swf'  => 'application/x-shockwave-flash',
        'zip'  => 'application/zip',
        'lzh'  => 'application/x-lzh',
        'mp3'  => 'audio/mpeg',
        'wav'  => 'audio/x-wav',
        'wmv'  => 'video/x-ms-wmv',
        '3g2'  => 'video/3gpp2',
        'mp4'  => 'video/mp4',
    ];
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    return $list[$ext] ?: "application/octet-stream";
}


function ベンチマーク(callable $func, ...$arg) :int{
    static $i = 0;
    $i++;

    $start = microtime(true);
    $end   = $start + 1;
    if($arg){
        for($count = -1;  microtime(true) <= $end;  $count++){
            $func(...$arg);
        }
    }
    else{
        for($count = -1;  microtime(true) <= $end;  $count++){
            $func();
        }
    }
    $finish = microtime(true);

    $count = ($count > 0) ? number_format($count) : number_format(1/($finish-$start), 3);
    printf("case%s: %s回\n", $i, $count);
    return $count;
}


function 関数文字列化($func){
    if(is_string($func) and preg_match("/::/", $func)){
        if(!method_exists(...explode('::', $func))){
            return false;
        }
        $ref = new \ReflectionMethod($func);
    }
    else{
        if(!is_callable($func) or is_array($func)){
            return false;
        }
        $ref = new \ReflectionFunction($func);
    }
    if(!$ref->isUserDefined()){
        return false;
    }
    $return = implode("",
        array_slice(
            file($ref->getFileName()),
            $ref->getStartLine() - 1,
            $ref->getEndLine() - $ref->getStartLine() + 1
        )
    );
    $return = preg_replace("/^.*(function[\s|\(])/i", '$1', $return); //token_get_all()でやるべきだが https://spelunker2.wordpress.com/2017/07/16/php%e3%81%ae%e3%83%88%e3%83%bc%e3%82%af%e3%83%b3%e3%83%a1%e3%83%a2/
    $return = preg_replace("/}.*$/", "}", $return);
    return $return;
}


function クラス文字列化($class){
    if(is_object($class) or class_exists($class)){
        $regex = "class[\s|\(|\{]";
    }
    elseif(interface_exists($class)){
        $regex = "interface ";
    }
    elseif(trait_exists($class)){
        $regex = "trait ";
    }
    else{
        return false;
    }
    $ref = new \ReflectionClass($class);
    if(!$ref->isUserDefined()){
        return false;
    }
    $return = implode("",
        array_slice(
            file($ref->getFileName()),
            $ref->getStartLine() - 1,
            $ref->getEndLine() - $ref->getStartLine() + 1
        )
    );
    $return = preg_replace("/^.*($regex)/i", '$1', $return);
    $return = preg_replace("/}.*$/", "}", $return);
    return $return;
}


function データベース(string $table, array $setting=[]){
    return new データベース($table, $setting);
}


class データベース{
    private static $pdo = [];
    private $接続名 = "";
    private $テーブル = "";
    private $主キー = "id";
    private $where = [];
    private $設定  = [];
    private $定義  = [];



    function __construct(string $table, array $setting=[]){
        assert(isset($_ENV['データベース.接続.0']));

        $this->設定['接続'][0] = $setting[0] ?? $_ENV['データベース.接続.0'];
        $this->設定['接続'][1] = $setting[1] ?? $_ENV['データベース.接続.1'] ?? '';
        $this->設定['接続'][2] = $setting[2] ?? $_ENV['データベース.接続.2'] ?? '';
        $this->設定['接続'][3] = $setting[3] ?? $_ENV['データベース.接続.3'] ?? [];

        $this->設定['接続'][3] += [
            PDO::ATTR_DEFAULT_FETCH_MODE       => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE                  => PDO::ERRMODE_WARNING,
            PDO::ATTR_EMULATE_PREPARES         => true,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        ];
 
        $this->設定['取得件数']   = $_ENV['データベース.取得件数']   ?? 31;
        $this->設定['クラス修飾'] = $_ENV['データベース.クラス修飾'] ?? '□';
        $this->設定['ログ']       = $_ENV['データベース.ログ']       ?? null;

        $this->接続名  = md5($this->設定['接続'][0] . $this->設定['接続'][1] . $this->設定['接続'][2]);

        if(!isset(self::$pdo[$this->接続名])){
            try{ //パスワードが漏れる可能性があるので例外を握りつぶす＋パスワードは配列に入れておく
                self::$pdo[$this->接続名] = new \PDO(...$this->設定['接続']);
            }
            catch(\PDOException $e){
                if($this->設定['接続'][3][PDO::ATTR_ERRMODE] === PDO::ERRMODE_EXCEPTION){
                    throw new \PDOException("データベースに接続できません。データベースの設定(ドライバー,ユーザー名,パスワード)を再確認してください");
                }
                else{
                    trigger_error("データベースに接続できません。データベースの設定(ドライバー,ユーザー名,パスワード)を再確認してください", E_USER_WARNING);
                }
            }
        }

        $this->テーブル($table);
    }


    function テーブル(string $table=null){
        if(!$table){
            return $this->テーブル;
        }

        $class = $this->設定['クラス修飾'] . $table;
        assert(class_exists($class));

        $this->テーブル = $table;
        $this->定義     = constant("$class::定義");
        $this->主キー   = defined ("$class::主キー")  ?  constant("$class::主キー")  :  "id";

        return $this;
    }


    function 実行(string $SQL文, array $プレースホルダ=[]){
        $this->where = [];

        if(!isset(self::$pdo[$this->接続名])){
            return false;
        }

        if(isset($this->設定['ログ'])){
            $this->設定['ログ']($SQL文, $プレースホルダ);
        }

        $state = self::$pdo[$this->接続名]->prepare($SQL文);
        if($state === false){
            return false;
        }

        if($this->設定['接続'][3][PDO::ATTR_DEFAULT_FETCH_MODE] & PDO::FETCH_CLASS){ //&は「含めば」
            $state->setFetchMode(PDO::FETCH_CLASS, $this->設定['クラス修飾'].$this->テーブル); // http://php.net/manual/ja/pdostatement.setfetchmode.php
        }

        for($i = 0;  $i < count($プレースホルダ);  $i++){
            $type = gettype($プレースホルダ[$i]);
            if($type === "integer" or $type === "boolean"){
                $state->bindValue($i+1, $プレースホルダ[$i], PDO::PARAM_INT);
            }
            elseif($type === "resource"){
                $state->bindParam($i+1, $プレースホルダ[$i], PDO::PARAM_LOB);
            }
            elseif($type === "NULL"){
                $state->bindValue($i+1, $プレースホルダ[$i], PDO::PARAM_NULL);
            }
            else{
                $state->bindValue($i+1, $プレースホルダ[$i], PDO::PARAM_STR);
            }
        }

        return $state->execute() ? $state : false;
    }


    function 取得(?int $offset=0, ?int $limit=0, array $order=[]){
        [$where文, $プレースホルダ] = $this->where文('where');

        $プレースホルダ[] = (int)($limit ?: $this->設定['取得件数']);
        $プレースホルダ[] = (int)$offset;
        $順番文 = $this->順番文($order);

        $SQL文 = "select * from {$this->テーブル} {$where文} {$順番文} limit ? offset ?";
        $state = $this->実行($SQL文, $プレースホルダ);

        return $state ? $state->fetchAll() : false;
    }


    function 列取得($列, ?int $offset=0, ?int $limit=0, array $order=[]){
        if(!$this->列なら($列)){
            return false;
        }

        [$where文, $プレースホルダ] = $this->where文('where');

        $プレースホルダ[] = (int)($limit ?: $this->設定['取得件数']);
        $プレースホルダ[] = (int)$offset;
        $順番文 = $this->順番文($order);
        $列文   = implode(",", (array)$列);

        $SQL文  = "select {$列文} from {$this->テーブル} {$where文} {$順番文} limit ? offset ?";
        $state = $this->実行($SQL文, $プレースホルダ);
        $mode  = is_string($列) ? PDO::FETCH_COLUMN : null;

        return $state ? $state->fetchAll($mode) : false;
    }


    function 行取得($id){
        [$where文, $プレースホルダ] = $this->where文('and');
        array_unshift($プレースホルダ, $this->id型変換($id));

        $SQL文  = "select * from {$this->テーブル} where {$this->主キー} = ? {$where文}";
        $state  = $this->実行($SQL文, $プレースホルダ);
        if($state === false){
            return false;
        }

        $data = $state->fetchAll();
        return $data ? $data[0] : $data;
    }


    function セル取得($id, string $列){
        if(!$this->列なら($列)){
            return false;
        }

        [$where文, $プレースホルダ] = $this->where文('and');
        array_unshift($プレースホルダ, $this->id型変換($id));

        $SQL文  = "select {$列} from {$this->テーブル} where {$this->主キー} = ? {$where文}";
        $state = $this->実行($SQL文, $プレースホルダ);

        return $state ? $state->fetchColumn() : false;
    }


    function 件数(){
        [$where文, $プレースホルダ] = $this->where文('where');

        $SQL文 = "select count(*) from {$this->テーブル} {$where文}";
        $state = $this->実行($SQL文, $プレースホルダ);

        return $state ? $state->fetchColumn() : false;
    }


    function 検索($word, $列, ?int $offset=0, ?int $limit=0, array $order=[]){
        $列 = (array)$列;
        if(!$this->列なら($列)){
            return false;
        }

        if(is_string($word)){
            $word = preg_replace("/[[:space:]　]+/u", " ", $word);
            $word = explode(" ", trim($word));
        }
        if(!$word){
            return false;
        }

        $プレースホルダ = [];
        foreach($word as $v){
            $プレースホルダ[] = "%" . addcslashes($v, '_%') . "%";
        }

        $concat文 = $this->MySQLなら()  ?  sprintf('concat(%s)', implode(',',$列))  :  sprintf('(%s)', implode('||',$列));
        $検索文   = implode(' and ', array_fill(0,count($プレースホルダ),"$concat文 like ?"));
        $順番文   = $this->順番文($order);

        [$where文, $プレースホルダ2] = $this->where文('and');
        $プレースホルダ = array_merge($プレースホルダ, $プレースホルダ2);

        $プレースホルダ[] = (int)($limit ?: $this->設定['取得件数']);
        $プレースホルダ[] = (int)$offset;

        $SQL文  = "select * from {$this->テーブル} where {$検索文} {$where文} {$順番文} limit ? offset ?";
        $state  = $this->実行($SQL文, $プレースホルダ);

        return $state ? $state->fetchAll() : false;
    }


    function 追加($data){
        $data = $this->型変換($data);
        if(!$data){
            return false;
        }

        $プレースホルダ = [];
        $追加文1 = $追加文2 = "";
        foreach($data as $k => $v){
            $追加文1 .= "{$k},";
            if(is_array($v)){
                $追加文2 .= "{$v[0]},";
            }
            else{
                $追加文2 .= "?,";
                $プレースホルダ[] = $v;
            }
        }
        $追加文1 = rtrim($追加文1, ',');
        $追加文2 = rtrim($追加文2, ',');

        $SQL文  = "insert into {$this->テーブル} ($追加文1) values ($追加文2)";
        $state  = $this->実行($SQL文, $プレースホルダ);

        return $state ? self::$pdo[$this->接続名]->lastInsertId() : false;
    }


    function 更新($id, $data) :bool{
        $data = $this->型変換($data);
        if(!$data){
            return false;
        }

        $プレースホルダ = [];
        $更新文 = '';
        foreach($data as $k => $v){
            if(is_array($v)){
                $更新文 .= "{$k}={$v[0]},";
            }
            else{
                $更新文 .= "{$k}=?,";
                $プレースホルダ[] = $v;
            }
        }
        $更新文 = rtrim($更新文, ',');
        $プレースホルダ[] = $this->id型変換($id);

        [$where文, $プレースホルダ2] = $this->where文('and');

        $SQL文  = "update {$this->テーブル} set {$更新文} where {$this->主キー} = ? {$where文}";
        $state  = $this->実行($SQL文, array_merge($プレースホルダ, $プレースホルダ2));

        return $state ? (bool)$state->rowCount() : false;
    }


    function 削除($id) :bool{
        [$where文, $プレースホルダ] = $this->where文('and');
        array_unshift($プレースホルダ, $this->id型変換($id));

        $SQL文  = "delete from {$this->テーブル} where {$this->主キー} = ? {$where文}";
        $state  = $this->実行($SQL文, $プレースホルダ);

        return $state ? (bool)$state->rowCount() : false;
    }


    function テーブル作成() :bool{
        if(!$this->定義){
            return false;
        }

        $作成文 = "";
        foreach($this->定義 as $k => $v){
            $作成文 .= "$k $v,";
        }
        $作成文 = rtrim($作成文, ',');
        $SQL文 = "create table IF NOT EXISTS {$this->テーブル} ($作成文) ";

        if($this->MySQLなら()){
            $追加定義 = $this->設定['クラス修飾'] . $this->テーブル . "::追加定義";
            $SQL文    = str_replace('autoincrement', 'auto_increment', $SQL文);
            $SQL文   .= defined($追加定義)  ?  constant($追加定義)  :  "ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci";
        }
        else {
            $SQL文  = str_replace('auto_increment', 'autoincrement', $SQL文);
        }

        return $this->実行($SQL文) ? true : false;
    }


    function where(string $where文, array $プレースホルダ=[]) :データベース{
        $this->where = [$where文, $プレースホルダ];
        return $this;
    }
    


    function インデックス作成(string $列) :bool{
        if(!$this->列なら($列)){
            return false;
        }
        $SQL文  = "create index {$列}インデックス on {$this->テーブル} ($列)";

        return $this->実行($SQL文) ? true : false;
    }


    function トランザクション開始() :データベース{
        if(isset(self::$pdo[$this->接続名])){
            self::$pdo[$this->接続名]->beginTransaction();
        }
        return $this;
    }


    function トランザクション確定() :データベース{
        if(isset(self::$pdo[$this->接続名])){
            self::$pdo[$this->接続名]->commit();
        }
        return $this;
    }


    function トランザクション元に戻す() :データベース{
        if(isset(self::$pdo[$this->接続名])){
            self::$pdo[$this->接続名]->rollBack();
        }
        return $this;
    }


    function 切断() :void{
        if(isset(self::$pdo[$this->接続名])){
            self::$pdo[$this->接続名] = null; //staticはGCの対象になるのか？(不明)
        }
    }




    private function MySQLなら() :bool{
        return (bool)preg_match("/^mysql/i", $this->設定['接続'][0]);
    }

    private function 列なら($arg) :bool{
        if(empty($arg)){
            return false;
        }

        $列一覧 = array_keys($this->定義);
        foreach((array)$arg as $v){
            if(!in_array($v, $列一覧, true)){
                return false;
            }
        }
        return true;
    }


    private function where文(string $prefix='') :array{
        if($this->where){
            $this->where[0] = preg_replace("/^where/i", "", ltrim($this->where[0]));
            $where文        = sprintf("%s %s", $prefix, $this->where[0]);
            $プレースホルダ = $this->where[1];
        }
        else{
            $where文        = "";
            $プレースホルダ = [];
        }
        return [$where文, $プレースホルダ];
    }


    private function 順番文(array $arg=null) :string{
        if(is_array($arg) and $this->列なら(key($arg))){
            $列名 = key($arg);
            $順番 = ($arg[$列名] === "大きい順") ? 'desc' : 'asc';
        }
        else{
            $列名 = $this->主キー;
            $順番 = 'desc';
        }
        return " order by $列名 $順番 ";
    }


    private function 型変換($arg) :array{
        $return = [];

        if(!is_array($arg) and !($arg instanceof $this->設定['クラス修飾'].$this->テーブル)){
            return $return;
        }

        foreach($arg as $k => $v){
            if(!isset($this->定義[$k])){
                continue;
            }
            if(is_array($v)){
                $return[$k] = $v;
                continue;
            }
            preg_match("/^(\S+)/", ltrim($this->定義[$k]), $型);
            if(preg_match("/INT/i", $型[1])){
                $return[$k] = (int)$v;
            }
            elseif(preg_match("/CHAR|TEXT|CLOB/i", $型[1])){
                $return[$k] = (string)$v;
            }
            elseif(preg_match("/REAL|FLOA|DOUB/i", $型[1])){
                $return[$k] = (float)$v;
            }
            else{
                $return[$k] = $v;
            }
        }
        return $return;
    }


    private function id型変換($id){
        preg_match("/^(\S+)/", ltrim($this->定義[$this->主キー]), $型);
        return preg_match("/int/i", $型[1])  ?  (int)$id  :  (string)$id;
    }
}


class 設定 implements \ArrayAccess, \IteratorAggregate, \Countable{
    private $記憶 = [];
    private $記法 = '.';


    function offsetGet($name){
        $array = $this->記憶;
        foreach(explode($this->記法, $name) as $k){
            if(isset($array[$k])){
                $array = $array[$k];
            }
            else{
                return;
            }
        }
        return $array;
    }

    function offsetSet($name, $value){
        $array = &$this->記憶;
        $keys  = explode($this->記法, $name);

        while(count($keys) > 1){
            $k = array_shift($keys);

            if(!isset($array[$k])){
                $array[$k] = [];
            }
            elseif(!is_array($array[$k])){
                return;
            }

            $array = &$array[$k];
        }

        if(!isset($array[$keys[0]])){
            $array[$keys[0]] = $value;
        }
    }

    function offsetExists($name){
        $array = $this->記憶;
        foreach(explode($this->記法, $name) as $k){
            if(isset($array[$k])){
                $array = $array[$k];
            }
            else{
                return false;
            }
        }
        return true;
    }

    function offsetUnset($offset){
    }

    function getIterator(){
        return new \ArrayIterator($this->記憶);
    }

    function count() { 
        return count($this->記憶);
    }

    function __construct(array $array = null){
        if(is_array($array)){
            $this->記憶 = $array;
        }
    }
    

    function __invoke(){
        return $this->記憶;
    }

    function __toString(){
        return __CLASS__;
    }

    function __set($name, $value){
    }

    function 記法(string $str){
        $this->記法 = $str;
    }
}



function 部品(string $部品名, ...$引数) :string{
    try{
        return 部品::作成($部品名, $引数);
    }
    catch(\Error $e){ //部品コードをevalした時と、部品コードを実行した時に飛んでくる例外
        trigger_error(sprintf("部品ファイル: %s の部品コード%s行目でPHPエラー「%s」が発生しました", $部品名, $e->getLine(), $e->getMessage()), E_USER_WARNING);
    }
    catch(\Exception $e){
        $back = debug_backtrace()[0];
        trigger_error(sprintf("%s\n%s: %s行目\n\n", $e->getMessage(), $back['file'], $back['line']), E_USER_WARNING);
    }
    return "";
}


class 部品{
    private static $設定;
    private static $記憶;
    private static $タグ;


    static function 開始(string $dir, array $option=[]) :bool{
        if(self::$設定){
            return false;
        }
        if(!is_dir($dir)){
            trigger_error("部品ディレクトリ: $dir が見つかりません", E_USER_WARNING);
            return false;
        }

        self::$設定 = $option + [
            "手動"  => false,
            "nonce" => null,
        ];
        self::$設定["ディレクトリ"] = realpath($dir);

        self::$記憶 = ['部品コード'=>[], 'stack'=>[], '読み込み済みURL'=>[], 'fromparts'=>[]];
        self::$タグ = ['css'=>'', 'jsinhead'=>'', 'jsinbody'=>'', 'fromparts'=>''];

        if(!self::$設定['手動']){
            ob_start(["部品", "差し込む"]);
        }
        return true;
    }


    static function 終了() :string{
        if(!self::$設定){
            return "";
        }
        $return = self::$設定['手動']  ?  ""  :  self::差し込む(ob_get_clean());
        self::$設定 = self::$記憶 = self::$タグ = null;
        return $return;
    }


    static function 作成($部品名, $引数) :string{
        if(!isset(self::$記憶['部品コード'][$部品名])){
            $解析 = self::ファイル解析($部品名);

            self::$記憶['部品コード'][$部品名] = preg_match("/^function/i", $解析['php'])  ?  eval(sprintf("return %s;", $解析['php']))  :  $解析['php'];

            self::$タグ['css']      .= self::タグ作成($解析['css'], "href", $部品名);
            self::$タグ['jsinhead'] .= self::タグ作成($解析['jsh'], "src", $部品名);
            self::$タグ['jsinbody'] .= self::タグ作成($解析['jsb'], "src", $部品名);
        }

        self::$記憶['stack'][] = $部品名;
        if(count(self::$記憶['stack']) > 1000){
            throw new \Exception("部品ファイル読み込みのループが1000回を超えました。\n" . implode("→", array_slice(self::$記憶['stack'], -50))); //部品関数でキャッチする
        }

        $html = is_callable(self::$記憶['部品コード'][$部品名])  ?  self::$記憶['部品コード'][$部品名](...$引数)  :  self::$記憶['部品コード'][$部品名];

        array_pop(self::$記憶['stack']);
        return (string)$html;
    }


    static function 差し込む(string $buf="") :string{
        self::$タグ['fromparts'] = self::frompartsタグ作成();

        if(self::$タグ['jsinbody']){
            $pos = strripos($buf, "</body>");
            if($pos !== false){ //最後に出現する</body>の前にJSを挿入する
                $buf = substr_replace($buf, self::$タグ['jsinbody'], $pos, 0);
            }
            else{
                $buf = $buf . self::$タグ['jsinbody'];
            }
        }
        if(self::$タグ['css'] || self::$タグ['jsinhead'] || self::$タグ['fromparts']){
            $pos = stripos($buf, "</title>");
            if($pos !== false){ //最初に出現する</title>の前に挿入する
                $buf = substr_replace($buf, "\n".self::$タグ['css'].self::$タグ['fromparts'].self::$タグ['jsinhead'], $pos+8, 0);
            }
        }
        return $buf;
    }


    static function タグ取得() :array{
        self::$タグ['fromparts'] = self::frompartsタグ作成();
        return self::$タグ;
    }


    static function fromparts($data) :void{
        $部品名 = end(self::$記憶['stack']);
        if($部品名){
            self::$記憶['fromparts'][$部品名] = json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
        }
    }




    private static function ファイル取得(string $部品名) :string{
        if(!isset(self::$設定['ディレクトリ'])){
            throw new \Exception("部品::開始() が行われていません"); //部品関数でキャッチする
        }
        if(preg_match("/^[^a-zA-Z\x7f-\xff][^a-zA-Z0-9_\x7f-\xff]*/", $部品名)){
            throw new \Exception("部品名: $部品名 はPHP変数の命名規則に沿っていません"); //部品関数でキャッチする
        }

        $path = sprintf("%s/%s.html", self::$設定['ディレクトリ'], str_replace("_", "/", $部品名));
        $return = @file_get_contents($path);
        if($return === false){
            throw new \Exception("部品名: $部品名 の部品ファイル: $path が見つかりません"); //部品関数でキャッチする
        }
        return $return;
    }


    private static function ファイル解析(string $部品名) :array{
        $return = ["css"=>[], "jsh"=>[], "jsb"=>[], "php"=>""];
        $html = self::ファイル取得($部品名);

        //コメント除去
        $html = preg_replace("|<!--[\s\S]*?-->|", "", $html);

        //部品定数処理
        $html = str_replace("__部品__", $部品名, $html);

        //CSS処理
        $pos  = stripos($html, "</head");
        $head = substr($html, 0, $pos);
        preg_match_all("|<style([\s\S]*?)</style>|i", $head, $style, PREG_OFFSET_CAPTURE);
        preg_match_all("|<link[^>]+>|i", $head, $link, PREG_OFFSET_CAPTURE);

        $css = [];
        foreach(array_merge($style[0], $link[0]) as $val){
            $css[$val[1]] = $val[0];
        }
        ksort($css);
        $return["css"] = array_values($css);

        //JS処理
        preg_match_all("|<script([^>]*>)([\s\S]*?)</script>|i", $html, $script, PREG_OFFSET_CAPTURE);
        for($i = 0;  $i < count($script[0]);  $i++){
            if(preg_match("/\stype=[\"']部品[\"']/ui", $script[1][$i][0])){
                $return["php"] = preg_replace("/^function[^\(]*/i", "function", ltrim($script[2][$i][0]));
            }
            elseif($script[0][$i][1] < $pos){
                $return["jsh"][] = $script[0][$i][0];
            }
            else{
                $return["jsb"][] = $script[0][$i][0];
            }
        }
        return $return;
    }


    private static function タグ作成(array $array, string $link, string $部品名) :string{
        $return = "";
        $部品ファイルの位置 = self::$設定['ディレクトリ'] . "/" . dirname(str_replace("_", "/", $部品名));

        foreach($array as $v){
            preg_match("|^([^>]+)|", $v, $attr);
            if(preg_match("|\s$link=[\"\']([\s\S]*?)[\"\']|i", $attr[0], $url)){
                $url = $url[1];
                if(!preg_match("#^(/|https?://)#", $url)){ //相対パスなら
                    $url = realpath("$部品ファイルの位置/$url");
                    if(in_array($url, self::$記憶['読み込み済みURL'])){
                        continue;
                    }
                    $contents = file_get_contents($url);
                    $v = ($link === "href")  ?  "<style>\n$contents</style>"  :  "<script>\n$contents</script>";
                }
                else{
                    if(in_array($url, self::$記憶['読み込み済みURL'])){
                        continue;
                    }
                }
                self::$記憶['読み込み済みURL'][] = $url;
            }
            if(isset(self::$設定['nonce'])){
                $v = preg_replace("/^<(\w+)/", sprintf('<$1 nonce="%s" ', self::$設定['nonce']), $v);
            }
            $return .= "$v\n";
        }

        return $return;
    }


    private static function frompartsタグ作成() :string{
        if(!self::$記憶['fromparts']){
            return "";
        }

        $return  = isset(self::$設定['nonce'])  ?  sprintf('<script nonce="%s">', self::$設定['nonce'])  :  '<script>';
        $return .= "\n";
        $return .= "var fromparts = {};\n";

        foreach(self::$記憶['fromparts'] as $k => $v){
            $return .= sprintf("fromparts['%s'] = %s;\n", $k, $v);
        }
        $return .= "</script>\n";

        return $return;
    }
}




class 文書 implements Countable, IteratorAggregate, ArrayAccess{
    private $文書;
    private $選択 = [];
    private $選択記憶 = [];
    private $種類 = "html";


    function __construct($str = '<!DOCTYPE html><html lang="ja"><head><meta charset="utf-8"><title></title></head><body></body></html>'){
        $this->文書 = new \DOMDocument(); // https://secure.php.net/manual/ja/class.domdocument.php
        libxml_use_internal_errors(true);  // loadHTML() の警告抑制
        libxml_disable_entity_loader(true);

        $str = preg_replace("/^[^<]+/", "", $str);
        if(!preg_match("/^<\?xml/i", $str)){ //HTML
            if(!preg_match("/^<\!DOCTYPE/i", $str)){ //ドキュメントタイプがないと変なドキュメントタイプが勝手に追加されるので対策
                $str = "<!DOCTYPE html>\n$str";
                $this->種類 = "断片";
            }
            $str = '<?xml encoding="UTF-8">' . $str; //文字化け対策のおまじない。出力時のsaveXML($this->文書->doctype).saveHTML($this->文書->documentElement)とセットで使う
            $this->文書->loadHTML($str, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED | LIBXML_NONET | LIBXML_COMPACT | LIBXML_PARSEHUGE); // https://php.net/manual/ja/libxml.constants.php
        }
        else{ //XML
            $this->文書->loadXML($str, LIBXML_NONET | LIBXML_COMPACT | LIBXML_PARSEHUGE);
            $this->種類 = "xml";
        }

        $this->文書->formatOutput = true;
        $this->文書->encoding = "utf-8";
        $this->文書->preserveWhiteSpace = false;
    }


    function 本文($str = null){
        if($str === null){
            $return = "";
            if(isset($this->選択[0])){
                $return = $this->選択[0]->textContent;
            }
            return $return;
        }
        else{
            foreach($this->選択 as $where){
                $where->textContent = $str;
            }
            return $this;
        }
    }


    function html($str = null){
        if($str === null){ //innerHTML
            $return = "";
            if(isset($this->選択[0])){
                foreach($this->選択[0]->childNodes as $child){
                    $return .= $this->文書->saveHTML($child);
                }
            }
            return $return;
        }
        if($str === true){ //outerHTML
            $return = "";
            if(isset($this->選択[0])){
                $return = $this->文書->saveHTML($this->選択[0]);
            }
            return $return;
        }
        else{
            $add = $this->文字列DOM化($str);
            foreach($this->選択 as $where){
                $where->textContent = "";
                if($str !== ""){
                    $where->appendChild($add->cloneNode(true));
                }
            }
            return $this;
        }
    }


    function タグ名(){
        $return = "";
        if(isset($this->選択[0])){
            $return = strtolower($this->選択[0]->tagName);
        }
        return $return;
    }


    function 属性($name = null, $value = null){
        if(is_string($name) and $value === null){ //属性値を1つ取得
            $return = "";
            if(isset($this->選択[0])){
                $return = $this->選択[0]->getAttribute($name);
            }
            return $return;
        }
        else if(is_string($name)){ //属性値を1つ設定
            foreach($this->選択 as $where){
                $where->setAttribute($name, $value);
            }
            return $this;
        }
        else if(is_array($name)){ //属性を複数設定
            foreach($this->選択 as $where){
                foreach($name as $k => $v){
                    $where->setAttribute($k, $v);
                }
            }
            return $this;
        }
        else if($name === null){ //全属性取得
            $return = [];
            if(isset($this->選択[0])){
                $attrs = $this->選択[0]->attributes;
                for($i = 0;  $i < $attrs->length;  $i++){
                    $return[$attrs->item($i)->name] = $attrs->item($i)->value;
                }
            }
            return $return;
        }
        return $this;
    }


    function 属性削除($name = null){
        if(is_string($name)){
            foreach($this->選択 as $where){
                $where->removeAttribute($name);
            }
        }
        else{
            foreach($this->選択 as $where){
                $attrs = $where->attributes;
                for($i = $attrs->length - 1;  $i >= 0;  $i--){
                    $where->removeAttribute($attrs->item($i)->name);
                }
            }
        }
        return $this;
    }


    function dom($tag = null, $attr = null, $content = null){
        if(is_string($tag)){
            $dom = $this->文書->createElement($tag);
            if(is_array($attr)){
                foreach($attr as $k => $v){
                    $dom->setAttribute($k, $v);
                }
            }
            else if(is_string($attr)){
                $dom->textContent = $attr;
            }
            if(is_string($content)){
                $dom->textContent = $content;
            }
            return $dom;
        }
        else{
            return isset($this->選択[0]) ? $this->選択[0] : null;
        }
    }


    function 追加($add, $relation){
        return $this->DOM操作($this->DOM箱作成($add), $relation);
    }


    function 貼り付け($selector, $relation){
        $this($selector);
        return $this->DOM操作($this->選択記憶, $relation);
    }


    function 削除(){
        return $this->DOM操作([], "削除");
    }


    function 検索($selector){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->セレクタ検索($selector, false, $where));
        }
        return $this->選択保存($this->重複ノード解消($新選択));
    }


    function 最初(){
        return $this->選択保存(array_slice($this->選択, 0, 1));
    }


    function 最後(){
        return $this->選択保存(array_slice($this->選択, -1, 1));
    }


    function n($n, $m = 1){
        return $this->選択保存(array_slice($this->選択, $n, $m));
    }


    function 親($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "parentNode", true));
        }
        if($selector){
            $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false));
        }
        return $this->選択保存($this->重複ノード解消($新選択));
    }


    function 兄($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "previousSibling", true));
        }
        if($selector){
            $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false));
        }
        return $this->選択保存($this->重複ノード解消($新選択));
    }


    function 弟($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "nextSibling", true));
        }
        if($selector){
            $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false));
        }
        return $this->選択保存($this->重複ノード解消($新選択));
    }


    function 子持ち($selector = null){
        $新選択 = [];
        if(!$selector){ $selector = "*"; }
        foreach($this->選択 as $where){
            if(count($this->セレクタ検索($selector, false, $where))){
                $新選択[] = $where;
            }
        }
        return $this->選択保存($新選択);
    }


    function 親全て($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "parentNode", false));
        }
        if($selector){
            $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false));
        }
        return $this->選択保存($this->重複ノード解消($新選択));
    }


    function 兄全て($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "previousSibling", false));
        }
        if($selector){
            $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false));
        }
        return $this->選択保存($this->重複ノード解消($新選択));
    }


    function 弟全て($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "nextSibling", false));
        }
        if($selector){
            $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false));
        }
        return $this->選択保存($this->重複ノード解消($新選択));
    }


    function 兄弟全て($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "previousSibling", false), $this->家族探索($where, "nextSibling", false));
        }
        if($selector){
            $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false));
        }
        return $this->選択保存($this->重複ノード解消($新選択));
    }


    function 子($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            foreach($where->childNodes as $child){
                if($child->nodeType === XML_ELEMENT_NODE){
                    $新選択[] = $child;
                    continue 2;
                }
            }
        }
        if($selector){
            $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false));
        }
        return $this->選択保存($this->重複ノード解消($新選択));
    }


    function 子全て($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            foreach($where->childNodes as $child){
                if($child->nodeType === XML_ELEMENT_NODE){
                    $新選択[] = $child;
                }
            }
        }
        if($selector){
            $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false));
        }
        return $this->選択保存($this->重複ノード解消($新選択));
    }


    function かつ($selector){
        $新選択 = $this->積集合($this->選択, $this->セレクタ検索($selector, false));
        return $this->選択保存($新選択);
    }


    function 足す($selector){
        $新選択 = array_merge($this->選択, $this->セレクタ検索($selector, false));
        return $this->選択保存($this->重複ノード解消($新選択));
    }


    function 引く($selector){
        $新選択 = $this->差集合($this->選択, $this->セレクタ検索($selector, false));
        return $this->選択保存($新選択);
    }


    function なら($selector){
        foreach($this->セレクタ検索($selector, false) as $val){
            foreach($this->選択 as $where){
                if($where->isSameNode($val)){
                    return true;
                }
            }
        }
        return false;
    }


    function 逆順(){
        return $this->選択保存(array_reverse($this->選択));
    }


    function 前の選択(){
        list($this->選択記憶, $this->選択) = [$this->選択, $this->選択記憶];
        return $this;
    }


    function d(){
        $print = "■現在選択中の要素 (" . count($this->選択) . ")\n\n";
        foreach($this->選択 as $key => $dom){
            $print .= "[$key]: {$this->文書->saveHTML($dom)}\n";
        }

        $print .= "\n\n■エラー情報\n\n";
        foreach(libxml_get_errors() as $error){
            switch ($error->level) {
                case LIBXML_ERR_WARNING:
                    $print .= "警告: {$error->line}行目 {$error->message}\n";
                    break;
                 case LIBXML_ERR_ERROR:
                    $print .= "エラー: {$error->line}行目 {$error->message}\n";
                    break;
                case LIBXML_ERR_FATAL:
                    $print .= "致命的なエラー: {$error->line}行目 {$error->message}\n";
                    break;
            }
        }
        return $print;
    }


    //■マジックメソッドの実装
    function __toString(){
        return $this->HTML全体文字列();
    }


    function __invoke($selector = null){
        if(is_string($selector)){
            if(!preg_match("/^</", $selector)){
                $this->セレクタ検索($selector);
                return clone $this;
            }
            else{
                return clone $this->選択保存($this->DOM箱作成($selector));
            }
        }
        else if($selector instanceof self){
            return clone $this->選択保存($this->DOM箱作成($selector));
        }
        else if($selector instanceof \DOMElement){
            return clone $this->選択保存($this->DOM箱作成($selector));
        }
        else if(is_array($selector)){
            return clone $this->選択保存($this->DOM箱作成($selector));
        }
        else{
            return clone $this;
        }
    }


    //■IteratorAggregateインターフェースの実装
    function getIterator(){
        $clone = [];
        for($i = 0;  $i < count($this->選択);  $i++){
            $clone[] = clone $this;
            $clone[$i]->選択 = [$this->選択[$i]];
            $clone[$i]->選択記憶 = [];
        }
        return new \ArrayObject($clone);
    }


    //■Countableインターフェースの実装
    function count() { 
        return count($this->選択);
    }


    //■ArrayAccessインターフェースの実装
    function offsetGet($offset){
        return $this->選択保存(array_slice($this->選択, $offset, 1));
    }
    function offsetSet($offset, $value){}
    function offsetExists($offset){}
    function offsetUnset($offset) {}




    //■以下プライベートメソッド
    private function 文字列DOM化($str = ""){
        $fragment = $this->文書->createDocumentFragment();
        $elements = (new self("<dummy>$str</dummy>"))->文書->documentElement->childNodes;
        foreach($elements as $child){
            $fragment->appendChild($this->文書->importNode($child, true));
        }
        return $fragment;
    }


    private function DOM箱作成($input){
        $dom箱 = [];
        if(is_string($input)){ //文字の場合
            $input = new self($input);
        }
        if($input instanceof self){ //文書オブジェクトの場合
            if($input === $this){
                return $dom箱;
            }
            $root = $input->文書->documentElement;
            $input = [];
            while($root){
                if($root->nodeType === XML_ELEMENT_NODE){
                    $input[] = $root;
                }
                $root = $root->nextSibling;
            }
        }
        if($input instanceof \DOMElement){ //DOMの場合
            $input = [$input];
        }
        if(is_array($input) or ($input instanceof \DOMNodeList)){ //配列の場合
            foreach($input as $node){
                if(!($node instanceof \DOMElement)){
                    continue;
                }
                if($node->ownerDocument !== $this->文書){
                    $node = $this->文書->importNode($node, true);
                }
                $dom箱[] = $node->cloneNode(true);
            }
        }
        return $dom箱;
    }


    private function DOM操作(array $dom箱, $relation){
        $新選択 = [];
        switch($relation){
            case "上":
                foreach($this->選択 as $where){
                    foreach($dom箱 as $add){
                        $新選択[] = $where->parentNode->insertBefore($add->cloneNode(true), $where);
                    }
                }
                break;
            case "下":
                foreach($this->選択 as $where){
                    foreach(array_reverse($dom箱) as $add){
                        $新選択[] = $where->parentNode->insertBefore($add->cloneNode(true), $where->nextSibling);
                    }
                }
                break;
            case "中上":
                foreach($this->選択 as $where){
                    foreach(array_reverse($dom箱) as $add){
                        $新選択[] = $where->insertBefore($add->cloneNode(true), $where->firstChild);
                    }
                }
                break;
            case "中下":
                foreach($this->選択 as $where){
                    foreach($dom箱 as $add){
                        $新選択[] = $where->appendChild($add->cloneNode(true));
                    }
                }
                break;
            case "置換":
                foreach($this->選択 as $where){
                    foreach($dom箱 as $add){
                        $新選択[] = $where->parentNode->insertBefore($add->cloneNode(true), $where);
                    }
                    $where->parentNode->removeChild($where);
                }
                break;
            case "削除":
                foreach($this->選択 as $where){
                    $新選択[] = $where->parentNode->removeChild($where);
                }
                break;
        }
        return $this->選択保存($新選択);
    }


    private function セレクタ検索($selector = null, $記録する = true, DOMNode $context = null){
        $return = [];
        if(!$selector){
            return $return;
        }

        $expression = $this->selector2XPath($selector);
        if($context){
            $expression = preg_replace("|^//|", ".//", $expression); //相対パスにする
        }
        foreach((new \DOMXPath($this->文書))->query($expression, $context) as $node){ //DOMNodeList(複数形)が返る https://secure.php.net/manual/ja/class.domxpath.php
            $return[] = $node;
        }
        if($記録する){
            $this->選択保存($return);
        }
        return $return;
    }


    private function 選択保存(array $array){
        $this->選択記憶 = $this->選択;
        $this->選択 = $array;
        return $this;
    }


    private function 家族探索(DOMNode $開始ノード, $続柄, $一人だけ){
        $return = [];
        $node = $開始ノード->$続柄;
        while($node){
            if($node->nodeType === XML_ELEMENT_NODE){
                $return[] = $node;
                if($一人だけ){
                    break;
                }
            }
            $node = $node->$続柄;
        }
        return $return;
    }


    private function 重複ノード解消(array $array){
        $return = [];
        foreach($array as $v1){
            if(!($v1 instanceof \DOMNode)){
                continue;
            }
            foreach($return as $v2){
                if($v1->isSameNode($v2)){
                    continue 2;
                }
            }
            $return[] = $v1;
        }
        return $return;
    }


    private function 積集合(array $a, array $b){
        $return = [];
        foreach($a as $v1){
            foreach($b as $v2){
                if($v1->isSameNode($v2)){
                    $return[] = $v1;
                    continue 2;
                }
            }
        }
        return $return;
    }


    private function 差集合(array $a, array $b){
        $return = [];
        foreach($a as $v1){
            foreach($b as $v2){
                if($v1->isSameNode($v2)){
                    continue 2;
                }
            }
            $return[] = $v1;
        }
        return $return;
    }


    private function HTML全体文字列(){
        $type = $this->文書->doctype;
        $node = $this->文書->documentElement;

        $return = "";
        switch($this->種類){
            case "html":
                return $this->文書->saveXML($type) . "\n" . $this->文書->saveHTML($node);
            case "xml":
                return $this->文書->saveXML($type) . "\n" . $this->文書->saveXML($node);
            case "断片":
                while($node){
                    if($node->nodeType === XML_ELEMENT_NODE){
                        $return .= $this->文書->saveHTML($node) . "\n";
                    }
                    $node = $node->nextSibling;
                }
                return $return;
        }
    }


    private function xml2array(DOMElement $node){
        $return = [];

        foreach($node->attributes as $attr){
            $return['@'.$attr->name] = $attr->value;
        }

        foreach($node->childNodes as $child){
            if($child->nodeType === XML_ELEMENT_NODE){
                $return[$child->nodeName][] = $this->xml2array($child);
            }
            else if($child->nodeType === XML_TEXT_NODE){
                $return['#text'] = $child->textContent;
            }
        }
        return $return;
    }


    /**
     * HTML_CSS_Selector2XPath.php The MIT License
     * Copyright (c) 2008 Daichi Kamemoto <daikame@gmail.com>
     * Copyright (c) 2009 Daichi Kamemoto <daikame@gmail.com>, TANAKA Koichi <tanaka@ensites.com>
     */
    private function selector2XPath($input_selector, $throw_exception = false){
        $regex = [
            'element'    => '/^(\*|[a-z_][a-z0-9_-]*|(?=[#:.\[]))/i',
            'id_class'   => '/^([#.])([a-z0-9*_-]*)/i',
            'attribute'  => '/^\[\s*([^~|=\s]+)\s*([~|]?=)\s*"([^"]+)"\s*\]/',
            'attr_box'   => '/^\[([^\]]*)\]/',
            'attr_not'   => '/^:not\(([^)]*)\)/i',
            'pseudo'     => '/^:([a-z0-9_-]+)(\(\s*([a-z0-9_\s+-]+)\s*\))?/i',
          //'combinator' => '/^(\s*[>+~\s])/i',
          //'comma'      => '/^(,)/',
            'combinator_or_comma' => '/^(\s*[>+~\s,])/i',
        ];
        $parts[] = '//';
        $last = '';
        $selector = trim($input_selector);
        $element = true;

        $pregMatchDelete = function ($pattern, &$subject, &$matches){ // 正規表現でマッチをしつつ、マッチ部分を削除
            if (preg_match($pattern, $subject, $matches)) {
                $subject = substr($subject, strlen($matches[0]));
                return true;
            }
        };

        while ((strlen(trim($selector)) > 0) && ($last != $selector)){
            $selector = trim($selector);
            $last = trim($selector);

            // Elementを取得
            if($element){
                if ($pregMatchDelete($regex['element'], $selector, $e)){
                    $parts[] = $e[1]==='' ? '*' : $e[1];
                }
                elseif($throw_exception) {
                    throw new UnexpectedValueException("parser error: '$input_selector' is not valid selector.(missing element)");
                }
                $element = false;
            }

            // IDとClassの指定を取得
            if($pregMatchDelete($regex['id_class'], $selector, $e)) {
                switch ($e[1]){
                    case '.':
                        $parts[] = '[contains(concat( " ", @class, " "), " ' . $e[2] . ' ")]';
                        break;
                    case '#':
                        $parts[] = '[@id="' . $e[2] . '"]';
                        break;
                    default:
                        if($throw_exception) throw new LogicException("Unexpected flow occured. please conntact authors.");
                        break;
                }
            }

            // atribauteを取得
            if($pregMatchDelete($regex['attribute'], $selector, $e)) {
                switch ($e[2]){ // 二項(比較)
                    case '!=':
                        $parts[] = '[@' . $e[1] . '!=' . $e[3] . ']';
                        break;
                    case '~=':
                        $parts[] = '[contains(concat( " ", @' . $e[1] . ', " "), " ' . $e[3] . ' ")]';
                        break;
                    case '|=':
                        $parts[] = '[@' . $e[1] . '="' . $e[3] . '" or starts-with(@' . $e[1] . ', concat( "' . $e[3] . '", "-"))]';
                        break;
                    default:
                        $parts[] = '[@' . $e[1] . '="' . $e[3] . '"]';
                        break;
                }
            }
            else if ($pregMatchDelete($regex['attr_box'], $selector, $e)) {
                $parts[] = '[@' . $e[1] . ']';  // 単項(存在性)
            }

            // notつきのattribute処理
            if ($pregMatchDelete($regex['attr_not'], $selector, $e)) {
                if ($pregMatchDelete($regex['attribute'], $e[1], $sub_e)) {
                    switch ($sub_e[2]){ // 二項(比較)
                        case '=':
                            $parts[] = '[@' . $sub_e[1] . '!=' . $sub_e[3] . ']';
                            break;
                        case '~=':
                            $parts[] = '[not(contains(concat( " ", @' . $sub_e[1] . ', " "), " ' . $sub_e[3] . ' "))]';
                            break;
                        case '|=':
                            $parts[] = '[not(@' . $sub_e[1] . '="' . $sub_e[3] . '" or starts-with(@' . $sub_e[1] . ', concat( "' . $sub_e[3] . '", "-")))]';
                            break;
                        default:
                            break;
                    }
                }
                else if ($pregMatchDelete($regex['attr_box'], $e[1], $e)) {
                    $parts[] = '[not(@' . $e[1] . ')]'; // 単項(存在性)
                }
            }

            // 疑似セレクタを処理
            if ($pregMatchDelete($regex['pseudo'], $selector, $e)) {
                switch ($e[1]) {
                    case 'root':
                        $parts = ['.'];
                        break 2;
                    case 'first-child':
                        $parts[] = '[not(preceding-sibling::*)]';
                        break;
                    case 'last-child':
                        $parts[] = '[not(following-sibling::*)]';
                        break;
                    case 'nth-of-type':
                        if (is_numeric($e[3])) {
                            $parts[] = '[' . $e[3] . ']';
                        }
                        break;
                    case 'nth-child':
                        // CSS3
                        if (is_numeric($e[3])) {
                            $parts[] = '[count(preceding-sibling::*) = ' . $e[3] . ' - 1]';
                        }
                        else if ($e[3] == 'odd') {
                            $parts[] = '[count(preceding-sibling::*) mod 2 = 0]';
                        }
                        else if ($e[3] == 'even') {
                            $parts[] = '[count(preceding-sibling::*) mod 2 = 1]';
                        }
                        else if (preg_match('/^([+-]?)(\d*)n(\s*([+-])\s*(\d+))?\s*$/i', $e[3], $sub_e)) {
                            $coefficient = $sub_e[2]==='' ? 1 : intval($sub_e[2]);
                            $constant_term = array_key_exists(3, $sub_e) ?  intval($sub_e[4]==='+' ? $sub_e[5] : -1 * $sub_e[5]) : 0;
                            if($sub_e[1]==='-') {
                                $parts[] = '[(count(preceding-sibling::*) + 1) * ' . $coefficient . ' <= ' . $constant_term . ']';
                            }
                            else { // '+' or ''
                                $parts[] = '[(count(preceding-sibling::*) + 1) ' . ($coefficient===0 ? '': 'mod ' . $coefficient . ' ') . '= ' . ($constant_term>=0 ? $constant_term : $coefficient + $constant_term) . ']';
                            }
                        }
                        break;
                    case 'lang':
                        $parts[] = '[@xml:lang="' . $e[3] . '" or starts-with(@xml:lang, "' . $e[3] . '-")]';
                        break;
                    default:
                        break;
                }
            }

             // combinatorとカンマがあったら、区切りを追加。また、次は型選択子又は汎用選択子でなければならない
            if ($pregMatchDelete($regex['combinator_or_comma'], $selector, $e)) {
                switch (trim($e[1])) {
                    case ',':
                        $parts[] = ' | //*';
                        break;
                    case '>':
                        $parts[] = '/';
                        break;
                    case '+':
                        $parts[] = '/following-sibling::*[1]/self::';
                        break;
                    case '~': // CSS3
                        $parts[] = '/following-sibling::';
                        break;
                  //case '':
                    default:
                        $parts[] = '//';
                        break;
                }
                $element = true;
            }
        }

        return implode('', $parts);
    }
}


function 内部エラー(string $str="エラーが発生しました", string $type="停止", string $除外パス="") :bool{
    $backtrace = debug_backtrace();
    $除外パス  = $除外パス ?: $backtrace[0]['file'];

    foreach($backtrace as $trace){
        if(strpos($trace['file'], $除外パス) !== 0){
            break;
        }
    }
    $message = sprintf("【%s】%s \n%s :%s行目 %s%s%s()\n\n", $type, $str, $trace['file'], $trace['line'], @$trace['class'], @$trace['type'], @$trace['function']);

    if(PHP_SAPI !== "cli"){
        $message = nl2br(htmlspecialchars($message, ENT_QUOTES, "UTF-8", false));
    }

    trigger_error($message, ['停止'=>E_USER_ERROR, '警告'=>E_USER_WARNING, '注意'=>E_USER_NOTICE][$type]);
    return false;
}
