<?php
//======================================================
// ■function.php http://musou.s38.xrea.com/php/
// 
// 呼び出し元: "../../index.php"
//======================================================


function route(array $route, $arg = 1){
    $_ENV['route.return'] = $arg;
    $_ENV['route.dir'] = dirname(debug_backtrace()[0]['file']);

    foreach((array)$route as $_ENV['route.file']){
        if(!preg_match("#^(/|\\\\|\w:)#", $_ENV['route.file'])){ //相対パスなら
            $_ENV['route.file'] = $_ENV['route.dir'] . "/" . $_ENV['route.file'];
        }
        $func = function (){ return require_once $_ENV['route.file']; };
        $_ENV['route.return'] = $func();
    }
    exit;
}


function routed(){
    return $_ENV['route.return'];
}


function テキスト表示($str = ""){
    header("Content-Type: text/plain; charset=utf-8");
    print $str;
    exit;
}


function JSON表示($json = [], $allow = null){
    if(!$allow){
        $allow = ($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : "*";
    }
    else{
        $allow = implode(" ", (array)$allow);
    }
    header("Access-Control-Allow-Origin: $allow");
    header("Access-Control-Allow-Credentials: true");
    if(is_string($_GET['callback'])){ //JSONP
        header("Content-Type: application/javascript; charset=utf-8");
        print $_GET['callback'] . "(" . json_encode($json, JSON_PARTIAL_OUTPUT_ON_ERROR) . ");";
    }
    else{ //JSON
        header("Content-Type: application/json; charset=utf-8");
        print json_encode($json, JSON_PARTIAL_OUTPUT_ON_ERROR);
    }
    exit;
}


function RSS表示(array $channel, array $items){ // http://www.futomi.com/lecture/japanese/rss20.html
    $tag = function ($name, $value){
        return "<$name>$value</$name>\n";
    };
    
    if(isset($channel["title"])){ $rss .= $tag("title", h($channel['title'])); }
    if(isset($channel["link"])){ $rss .= $tag("link", h($channel['link'])); }
    if(isset($channel["description"])){ $rss .= $tag("description", h($channel['description'])); }
    
    foreach($items as $item){
        $rss .= "<item>\n";
        if(isset($item["title"])){ $rss .= $tag("title", h($item['title'])); }
        if(isset($item["link"])){ $rss .= $tag("link", h($item['link'])); }
        if(isset($item["pubDate"])){ $rss .= $tag("pubDate", date("r", $item["pubDate"])); }
        $rss .= "</item>\n";
    }
    
    header("Content-Type: application/xml; charset=UTF-8");
    print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<rss version=\"2.0\">\n<channel>\n{$rss}</channel>\n</rss>";
    exit;
}


function リダイレクト($url){
    header("Location: $url");
    exit;
}


function 自動読み込み($dir = __DIR__){
    if(!preg_match("#^(/|\\\\|\w:)#", $dir)){ $dir  = dirname(debug_backtrace()[0]['file']) . "/$dir"; }
    spl_autoload_register(function($class) use($dir){
        $class = str_replace(["_","\\"], "/", $class);
        require_once "$dir/$class.php";
    });
}


function オブジェクト($file, ...$args){
    static $記憶 = [];

    if(!preg_match("#^(/|\\\\|\w:)#", $file)){ //相対パスなら
        $dir  = dirname(debug_backtrace()[0]['file']);
        $file = "$dir/$file";
    }
    $file = realpath($file);

    if(!isset($記憶[$file])){
        $記憶[$file] = require($file);
    }
    return ($args) ? $記憶[$file]($args) : $記憶[$file];
}


function 検査($type, $name, $func){
    if(preg_match("/^get$/i", $type)){
        $value = $_GET[$name];
    }
    elseif(preg_match("/^post$/i", $type)){
        $value = $_POST[$name];
    }
    elseif(preg_match("/^cookie$/i", $type)){
        $value = $_COOKIE[$name];
    }

    if(is_callable($func)){
        $result = $func($value);
    }
    else{
        if     (is_callable("検査::$func"))                          { $result = 検査::$func($value); }
        else if(preg_match("/^([0-9]+)文字$/u", $func, $m))          { $result = 検査::文字($value, (int)$m[1]); }
        else if(preg_match("/^([0-9]+)文字以上$/u", $func, $m))      { $result = 検査::文字以上($value, (int)$m[1]); }
        else if(preg_match("/^([0-9]+)文字以下$/u", $func, $m))      { $result = 検査::文字以下($value, (int)$m[1]); }
        else if(preg_match("/^(-?[0-9\.]+)以上$/u", $func, $m))      { $result = 検査::以上($value, (int)$m[1]); }
        else if(preg_match("/^(-?[0-9\.]+)以下$/u", $func, $m))      { $result = 検査::以下($value, (int)$m[1]); }
        else if(preg_match("/^(-?[0-9\.]+)より大きい$/u", $func, $m)){ $result = 検査::より大きい($value, (int)$m[1]); }
        else if(preg_match("/^(-?[0-9\.]+)より小さい$/u", $func, $m)){ $result = 検査::より小さい($value, (int)$m[1]); }
        else if(preg_match("/^(-?[0-9\.]+)と同じ$/u", $func, $m))    { $result = 検査::と同じ($value, (int)$m[1]); }
        else                                                         { throw new Exception(__function__."() 第3引数の関数名が間違っています", 500); }
    }
    
    if(検査::$例外 === true and $result === false){
        throw new Exception("{$name}の値が間違っています", 400);
    }
    
    if($result === true){ return true; }
    elseif($result === false){ return false; }
    else{ throw new Exception(__function__."() 第3引数の関数はtrueまたはfalseを返してください", 500); }
}


class 検査{
    public static $例外 = false;

    public static function 必須($v){
        return (is_string($v) and strlen($v) > 0);
    }
    public static function 数($v){
        return (is_numeric($v) and !preg_match("/^-?0+\d/", $v));
    }
    public static function 自然数($v){
        return preg_match("/^[1-9][0-9]*$/", $v) > 0;
    }
    public static function 自然数と0($v){
        return preg_match("/^(0|[1-9]\d*)$/", $v) > 0;
    }
    public static function 数字($v){
        return preg_match("/^[0-9]+$/", $v) > 0;
    }
    public static function 英字($v){
        return preg_match("/^[A-Za-z]+$/", $v) > 0;
    }
    public static function 英数字($v){
        return preg_match("/^[A-Za-z0-9]+$/", $v) > 0;
    }
    public static function URL($v){
        return preg_match("|^https?://.{4,}|i", $v) > 0;
    }
    public static function 画像データ($v){
        return getimagesizefromstring($v)[0] > 0; //getimagesize()[0]:横サイズ [1]:縦サイズ [2]:GIFは1、JPEGは2、PNGは3
    }
    public static function UTF8($v){
        return preg_match('//u', $v); //mb_check_encoding($v, 'UTF-8')
    }
    public static function と同じ($v, $num){
        return $v == $num;
    }
    public static function 以上($v, $num){
        return (is_numeric($v) and ($v >= $num));
    }
    public static function 以下($v, $num){
        return (is_numeric($v) and ($v <= $num));
    }
    public static function より大きい($v, $num){
        return (is_numeric($v) and ($v > $num));
    }
    public static function より小さい($v, $num){
        return (is_numeric($v) and ($v < $num));
    }
    public static function 文字($v, $num){
        return mb_strlen($v,"UTF-8") == $num;
    }
    public static function 文字以上($v, $num){
        return mb_strlen($v,"UTF-8") >= $num;
    }
    public static function 文字以下($v, $num){
        return mb_strlen($v,"UTF-8") <= $num;
    }
}


function 整形($type, $name, $func){
    if(preg_match("/^get$/i", $type)){
        $result = $_GET[$name] = $func($_GET[$name]);
    }
    elseif(preg_match("/^post$/i", $type)){
        $result = $_POST[$name] = $func($_POST[$name]);
    }
    elseif(preg_match("/^cookie$/i", $type)){
        $result = $_COOKIE[$name] = $func($_COOKIE[$name]);
    }
    return $result;
}


function 設定($name = null, $value = null){
    static $記憶 = [];

    if(!isset($name)){ return $記憶; }

    if(isset($value)){
        if(!isset($記憶[$name])){
            $記憶[$name] = $value;
            return $value;
        }
    }
    else{
        return (isset($記憶[$name])) ? $記憶[$name] : null;
    }
}
$_ENV['設定'] = function($name, $value){ return 設定($name, $value); };


function テンプレート($_file_, array $_data_ = null, $エスケープしない = false){
    if(!preg_match("#^(/|\\\\|\w:)#", $_file_)){ //相対パスなら
        $_file_ = dirname(debug_backtrace()[0]['file']) . "/$_file_";
    }
    $_h_ = function($arg) use (&$_h_){
        if(is_array($arg)){ return array_map($_h_, $arg); }
        return htmlspecialchars($arg, ENT_QUOTES, "UTF-8", false);
    };
    
    if($エスケープしない){
        foreach((array)$_data_ as $_key1_ => $_val_){
            $$_key1_ = $_val_;
        }
    }
    else{
        foreach((array)$_data_ as $_key1_ => $_val_){
            $$_key1_ = $_h_($_val_);
            $_key2_ = "__" . $_key1_;
            $$_key2_ = $_val_;
        }
    }
    ob_start();
    require $_file_;
    return ob_get_clean();
}


function ファイルダウンロード($data, $filename = null, $timeout = 60*60*12){
    ini_set("max_execution_time", $timeout);
    if(!file_exists($data)){ throw new Exception("ファイルが存在しません", 404); }
    $filesize = filesize($data);
    if(!$filename){ $filename = basename($data); }
    $filenameE = rawurlencode($filename);
    header("Content-Type: application/force-download");
    header("Content-Length: $filesize");
    header("Content-Disposition: attachment; filename=\"$filename\"; filename*=UTF-8''$filenameE");

    while(ob_get_level()){ ob_end_clean(); }
    readfile($data);
}


function データダウンロード($data, $filename = "data", $timeout = 60*60*12){
    ini_set("max_execution_time", $timeout);
    $filesize = strlen($data);
    $filenameE = rawurlencode($filename);
    header("Content-Type: application/force-download");
    header("Content-Length: $filesize");
    header("Content-Disposition: attachment; filename=\"$filename\"; filename*=UTF-8''$filenameE");

    while(ob_get_level()){ ob_end_clean(); }
    print $data;
}


function メール送信($送信先, $送信元 = "", $送信者 = "", $題名 = "", $本文 = "", array $添付 = null, $cc = "", $bcc = "", array $add = null){
    $送信先 = 改行変換($送信先);
    $送信元 = 改行変換($送信元);
    $送信者 = 改行変換($送信者);
    $題名   = 改行変換($題名);
    $cc     = 改行変換($cc);
    $bcc    = 改行変換($bcc);
    $add    = 改行変換($add);

    $送信先 = implode(",", (array)$送信先);
    $題名   = mb_encode_mimeheader($題名, "jis");
    $body   = mb_convert_encoding($本文, "jis", "UTF-8");

    if($送信元 and $送信者) { $header .= "From: " . mb_encode_mimeheader($送信者,"jis") . " <$送信元>\r\n"; }
    else if($送信元) { $header .= "From: $送信元\r\n"; }
    if($cc) {
        $cc = implode(",", (array)$cc);
        $header .= "Cc: $cc\r\n";
    }
    if($bcc){
        $bcc = implode(",", (array)$bcc);
        $header .= "Bcc: $bcc\r\n";
    }
    if(is_array($add)){ $header .= implode("\r\n", $add) . "\r\n"; }

    if(is_array($添付)){
        $区切り = "__" . uuid() . "__";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"{$区切り}\"\r\n";

        $body  = "--{$区切り}\r\n";
        $body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\r\n\r\n";
        $body .= mb_convert_encoding($本文, "jis", "UTF-8") . "\r\n";

        foreach($添付 as $name => $value){
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


function GET送信($url, array $querymap = null, array $request_header = []){
    if($querymap){
        $url .= preg_match("/\?/", $url) ? "&" : "?";
        $url .= http_build_query($querymap, "", "&", PHP_QUERY_RFC3986);
    }
    foreach($request_header as $k => $v){
        $header .= trim($k) . ": " . trim($v) . "\r\n";
    }
    $context = stream_context_create([
        'http'=>[
            'method' => 'GET',
            'header' => $header,
        ]
    ]);
    $return = file_get_contents($url, false, $context);
    $_ENV['RESPONSE_HEADER'] = ($return !== false) ? $http_response_header : [];
    return $return;
}


function POST送信($url, array $querymap = null, array $request_header = []){
    $content = http_build_query((array)$querymap, "", "&");
    $request_header = $request_header + [
        "Content-Type" => "application/x-www-form-urlencoded; charset=UTF-8",
        "Content-Length" => strlen($content),
    ];
    foreach($request_header as $k => $v){
        $header .= trim($k) . ": " . trim($v) . "\r\n";
    }
    $context = stream_context_create([
        'http'=>[
            'method' => 'POST',
            'header' => $header,
            'content' => $content,
        ]
    ]);
    $return = file_get_contents($url, false, $context);
    $_ENV['RESPONSE_HEADER'] = ($return !== false) ? $http_response_header : [];
    return $return;
}


function ファイル送信($url, array $querymap = null, array $request_header = []){
    $区切り = "__" . uuid() . "__";
    foreach((array)$querymap as $name => $value){
        if(is_array($value)){
            foreach($value as $name2 => $value2){
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

    $request_header = $request_header + [
        "Content-Type" => "multipart/form-data; boundary=$区切り",
        "Content-Length" => strlen($content),
    ];
    foreach($request_header as $k => $v){
        $header .= trim($k) . ": " . trim($v) . "\r\n";
    }

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => $header,
            'content' => $content
        ]
    ]);
    $return = file_get_contents($url, false, $context);
    $_ENV['RESPONSE_HEADER'] = ($return !== false) ? $http_response_header : [];
    return $return;
}


function キャッシュ無効(){
    if(headers_sent()){ return false; }
    header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
}


function 現在のURL($no_query = false){
    if(filter_input(INPUT_SERVER, 'HTTPS', FILTER_VALIDATE_BOOLEAN)){
        $scheme = "https://";
        if($_SERVER['SERVER_PORT'] != 443){ $port = ":" . $_SERVER['SERVER_PORT']; }
    }
    else {
        $scheme = "http://";
        if($_SERVER['SERVER_PORT'] != 80) { $port = ":" . $_SERVER['SERVER_PORT']; }
    }
    $url = $scheme . $_SERVER["HTTP_HOST"] . $port . $_SERVER['REQUEST_URI'];
    return ($no_query) ? preg_replace("/\?.*$/","",$url) : $url;
}


function ホームURL($url){
    $parsed = explode("/", $url);
    if(!isset($parsed[2])){ return false; }
    return $parsed[0] . "//" . $parsed[2] . "/";
}


function トップURL($url){
    $url = preg_replace("/\?.*/", "", $url);
    return (substr_count($url, "/") === 2) ? $url."/" : dirname($url."a")."/";
}


function Windowsなら(){
    return preg_match("/^WIN/i", PHP_OS);
}


function GETなら(){
    return (strtoupper(filter_input(INPUT_SERVER, "REQUEST_METHOD")) === 'GET') ? true : false;
}


function POSTなら(){
    return (strtoupper(filter_input(INPUT_SERVER, "REQUEST_METHOD")) === 'POST') ? true : false;
}


function 連想配列なら($array){
    return (is_array($array) and array_values($array) !== $array) ? true : false;
}


function 開発環境なら(){
    return ini_get("display_errors") ? true : false;
}


function PHP≧($str){
    return version_compare(PHP_VERSION, $str) >= 0;
}


function 日付($str = '[年]/[0月]/[0日] [0時]:[0分]', $time = 0){
    if(!$time){ $time = time(); }
    $week = ['日','月','火','水','木','金','土'][date('w', $time)];
    $from = ['[年]','[月]','[0月]','[日]','[0日]','[時]','[0時]','[0分]','[0秒]','[曜日]'];
    $to   = ['Y'   ,'n'   ,'m'    ,'j'   ,'d'    ,'G'   ,'H'    ,'i'    ,'s'    ,$week];
    $str  = str_replace($from, $to, $str);
    $str  = str_replace('[分]', ltrim(date('i',$time),"0"), $str);
    $str  = str_replace('[秒]', ltrim(date('s',$time),"0"), $str);
    return date($str, $time);
}


function 経過($time){
    if(!preg_match("/^\d+$/", $time)){ return ""; }
    $時間差 = time() - $time;
    switch($時間差){
        case $時間差 < 0        : return "未来";
        case $時間差 < 1        : return "今";
        case $時間差 < 60       : return "{$時間差}秒前";
        case $時間差 < 3600     : return floor($時間差/60)."分前";
        case $時間差 < 86400    : return floor($時間差/3600)."時間前";
        case $時間差 < 2592000  : return floor($時間差/86400)."日前";
        case $時間差 < 31536000 : return floor($時間差/2592000)."ヶ月前";
        default: return floor($時間差/31536000)."年前";
    }
}


function 日本語設定(){
    (preg_match("/^WIN/i", PHP_OS)) ? setlocale(LC_ALL, '') : setlocale(LC_ALL, 'ja_JP.UTF-8');
    ini_set("default_charset", "UTF-8");
    ini_set("mbstring.language", "Japanese");
    ini_set("mbstring.detect_order", "UTF-8,CP932,EUC-JP,ASCII");
    ini_set("date.timezone", "Asia/Tokyo");
}


function h($arg = ""){
    if(is_string($arg)){ return htmlspecialchars($arg, ENT_QUOTES, "UTF-8", false); }
    else if(is_array($arg)){ return array_map("h", $arg); }
    else { return $arg; }
}


function 改行変換($arg = "", $replace = ""){
    if(is_array($arg)){ return array_map(function($str) use($replace){ return 改行変換($str, $replace); }, $arg); }
    $arg = str_replace("\r", "", $arg);
    $arg = str_replace("\n", $replace, $arg);
    return $arg;
}


function 制御文字削除($arg = "", $LF = false){ // http://blog.sarabande.jp/post/52701231276
    if(is_array($arg)){ return array_map(function($str) use($LF){ return 制御文字削除($str, $LF); }, $arg); }
    $arg = preg_replace("/\t/", "    ", $arg);
    $arg = preg_replace("/\xC2[\x80-\x9F]/", "", $arg); //Unicode制御文字
    return ($LF) ? preg_replace("/(?!\n)[[:cntrl:]]/u", "", $arg) : preg_replace("/[[:cntrl:]]/u", "", $arg);
}


function タグ($tag, array $attr = []){
    $閉じる   = true;
    $単独タグ = false;
    if(preg_match("|^\!|", $tag)){
        $閉じる = false;
        $tag = ltrim($tag, "!");
    }
    if(preg_match("/[^a-zA-Z0-9\-]/", $tag)){ 
        trigger_error("[$tag]はタグ名に使用できません");
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


function 属性文字列(array $attr = []){
    $str = "";
    foreach($attr as $key => $val){
        if($key === "本文"){ continue; }
        if(preg_match("/[^a-zA-Z\-]/", $key)){
            trigger_error("[$key]は属性名に使用できません");
            continue;
        }
        if(in_array($key, ["src", "href", "action", "formaction", "poster"], true)){ //cite, srcset
            if(strlen($val) > 0 and !preg_match("%^(https*://|/|\./|#)%", $val)){ $val = "./" . $val; }
        }
        $val = htmlspecialchars($val, ENT_QUOTES, "UTF-8", false);
        $str .= " $key=\"$val\"";
    }
    return $str;
}


function 自動リンク($arg = "", array $attr = [], $dont_escape = false){
    if(is_array($arg)){ return array_map(function($str) use($attr){ return 自動リンク($str, $attr); }, $arg); }
    $attr_str = 属性文字列($attr);
    if($dont_escape === false){ $arg = h($arg); }
    return preg_replace("|(https?://[^[:space:]　\r\n<>]+)|ui", "<a href=\"$1\"$attr_str>$1</a>", $arg);
}


function 配列→table(array $array, array $attr = []){
    $firstkey = key($array);
    if(is_array($array[$firstkey])){ $tag = "<tr>\n<th></th>\n<th>" . implode("</th>\n<th>",h(array_keys($array[$firstkey]))) . "</th>\n</tr>\n"; }
    foreach($array as $key1 => $value1){
        $tag .= "<tr>\n<th>" . h($key1) . "</th>\n";
        foreach((array)$value1 as $key2 => $value2){ $tag .= "<td>" . h($value2) . "</td>\n"; }
        $tag .= "</tr>\n";
    }
    $attr_str = 属性文字列($attr);
    return "<table$attr_str>\n$tag\n</table>";
}


function パーミッション($path, $permission = null){
    if(!preg_match("/^0/", $permission) and $permission >= 100 and $permission <= 777){
        chmod($path, octdec($permission));
    }
    return decoct(fileperms($path) & 0777);
}


function ファイル一覧($dir = ".", $pattern = "/./"){
    if(!is_dir($dir)){ return false; }
    $dir = realpath($dir);
    foreach(array_diff(scandir($dir), ['.','..']) as $file){
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if(is_file($path)){
            if(preg_match($pattern, $file)){
                yield $path;
            }
        }
        elseif(is_dir($path)){
            foreach(ファイル一覧($path, $pattern) as $sub){
                yield $sub;
            }
        }
    }
}


function ディレクトリ一覧($dir = ".", $pattern = "/./"){
    if(!is_dir($dir)){ return false; }
    $dir = realpath($dir);
    foreach(array_diff(scandir($dir), ['.','..']) as $file){
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if(is_dir($path)) {
            if(preg_match($pattern, $file)){
                yield $path;
            }
            foreach(ディレクトリ一覧($path, $pattern) as $sub){
                yield $sub;
            }
        }
    }
}


function ディレクトリ作成($path, $permission = 707){
    if(!$path){ return false; }
    if(is_dir($path)){ return $path; }
    $mask = umask();
    umask(0);
    $result = mkdir($path, octdec($permission), true);
    umask($mask);
    return ($result) ? $path : false;
}


function ディレクトリ削除($path){
    if(!$path or !is_dir($path)){ return false; }
    foreach(array_diff(scandir($path), ['.','..']) as $file){
        (is_dir("$path/$file")) ? ディレクトリ削除("$path/$file") : unlink("$path/$file");
    }
    return rmdir($path);
}


function zip圧縮($zipfile, array $filemap){
    $zip = new ZipArchive();
    if(!$zip->open($zipfile, ZipArchive::CREATE)){ return false; }
    foreach($filemap as $name => $value){ $zip->addFromString($name, $value); } //$nameに/を含めるとディレクトリになる
    $zip->close();
    return $zipfile;
}


function zip解凍($zipfile, $where = ""){
    $zip = new ZipArchive();
    if(!$zip->open($zipfile)){ return false; }
    if(!$where){ $where = dirname(realpath($zipfile)); }
    $result = $zip->extractTo($where);
    $zip->close();
    return $result;
}



function 一時保存($name, $data){
    $tempfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . get_current_user() . "_" . md5($name);
    $result = file_put_contents($tempfile, json_encode($data, JSON_PARTIAL_OUTPUT_ON_ERROR), LOCK_EX);
    return ($result === false) ? false : $name;
}


function 一時取得($name){
    $tempfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . get_current_user() . "_" . md5($name);
    return (file_exists($tempfile)) ? json_decode(file_get_contents($tempfile)) : false;
}


function JSON保存($file, $data){
    $prefix = preg_match("/\.php$/i", $file) ? "<?php\n" : "";
    $result = file_put_contents($file, $prefix.json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR), LOCK_EX);
    return ($result === false) ? false : $file;
}


function JSON取得($file){
    $json = file_get_contents($file);
    if($json === false){ return false; }
    $json = preg_replace("/^<\?php\s*/i", "", $json);
    return json_decode($json, true);
}


function 配列保存($file, array $array){
    $return = file_put_contents($file, "<?php\nreturn " . var_export($array,true) . ";", LOCK_EX);
    return ($return === false) ? false : true;
}


function XML取得($xml, $options = array()) {
    if(!is_object($xml)){
        $xml = ltrim($xml);
        if(!preg_match("/^</", $input)){
            $xmlstr = file_get_contents($xml);
        }
        else{
            $xmlstr = $xml;
        }
        $xmlstr = preg_replace("/&(?!([a-zA-Z0-9]{2,8};)|(#[0-9]{2,5};)|(#x[a-fA-F0-9]{2,4};))/", "&amp;" ,$xmlstr);
        $xml = simplexml_load_string($xmlstr);
        if(!$xml){ return false; }
    }
    //xmlToArray Tamlyn Rhodes <http://tamlyn.org> Public Domain
    $defaults = array(
        'namespaceSeparator' => ':',//you may want this to be something other than a colon
        'attributePrefix' => '',   //to distinguish between attributes and nodes with the same name
        'alwaysArray' => array(),   //array of xml tag names which should always become arrays
        'autoArray' => true,        //only create arrays for tags which appear more than once
        'textContent' => 'content',       //key used for the text content of elements
        'autoText' => true,         //skip textContent key if node has no attributes or child nodes
        'keySearch' => false,       //optional search and replace on tag and attribute names
        'keyReplace' => false       //replace values for above search values (as passed to str_replace())
    );
    $options = array_merge($defaults, $options);
    $namespaces = $xml->getDocNamespaces();
    $namespaces[''] = null;
 
    $attributesArray = array();
    foreach ($namespaces as $prefix => $namespace) {
        foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
            if ($options['keySearch']) { $attributeName = str_replace($options['keySearch'], $options['keyReplace'], $attributeName); }
            $attributeKey = $options['attributePrefix'] . ($prefix ? $prefix . $options['namespaceSeparator'] : '') . $attributeName;
            $attributesArray[$attributeKey] = (string)$attribute;
        }
    }

    $tagsArray = array();
    foreach ($namespaces as $prefix => $namespace) {
        foreach ($xml->children($namespace) as $childXml) {
            $childArray = XML取得($childXml, $options);
            list($childTagName, $childProperties) = each($childArray);
 
            if ($options['keySearch']){ $childTagName = str_replace($options['keySearch'], $options['keyReplace'], $childTagName); }
            if ($prefix){ $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName; }
 
            if (!isset($tagsArray[$childTagName])) {
                $tagsArray[$childTagName] = in_array($childTagName, $options['alwaysArray']) || !$options['autoArray'] ? array($childProperties) : $childProperties;
            } elseif (is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName]) === range(0, count($tagsArray[$childTagName]) - 1)) {
                $tagsArray[$childTagName][] = $childProperties;
            } else {
                $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
            }
        }
    }
 
    $textContentArray = array();
    $plainText = trim((string)$xml);
    if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
 
    $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '') ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
 
    return array($xml->getName() => $propertiesArray);
}


function fromphp($data){
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
}


function 当たり($確率){
    $確率 = str_replace("%", "", $確率);
    if($確率 <= 0){ return false; }
    if($確率 >= 100){ return true; }

    $i = mt_rand(1, round(100/$確率*100000));
    return ($i <= 100000) ? true : false;
}


function uuid($hyphen = false) { //uuid v4
    $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0x0fff)|0x4000,mt_rand(0,0x3fff)|0x8000,mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff));
    return ($hyphen) ? $uuid : str_replace("-", "", $uuid);
}


function パスワード発行($length = 8, $userfriendly = false){
    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    if($userfriendly){ $str = "ABDEFGHJLMNQRTYabdefghmnrty23456789"; }
    $str = str_repeat($str, floor($length/2));
    return substr(str_shuffle($str), 0, $length);
}


function パスワードハッシュ($password){
    return password_hash($password, PASSWORD_DEFAULT);
}


function パスワード認証($password, $hash){
    return password_verify($password, $hash);
}


function 暗号化($str, $key){
    $iv = openssl_random_pseudo_bytes(16); // openssl_cipher_iv_length('aes-128-cbc') == 16
    return bin2hex($iv) . openssl_encrypt($str, 'aes-128-cbc', $key, 0, $iv); //先頭32バイトがiv
}


function 復号化($str, $key){
    $iv = substr($str, 0, 32);
    return openssl_decrypt(substr($str, 32), 'aes-128-cbc', $key, 0, hex2bin($iv));
}


function jwt発行($data, $key){
    $header     = ['typ'=>'jwt', 'alg'=>'HS256'];
    $segments   = [];
    $segments[] = base64_encode_urlsafe(json_encode($header), JSON_PARTIAL_OUTPUT_ON_ERROR);
    $segments[] = base64_encode_urlsafe(json_encode($data), JSON_PARTIAL_OUTPUT_ON_ERROR);

    $sign       = hash_hmac('sha256', implode('.', $segments), $key, true);
    $segments[] = base64_encode_urlsafe($sign);

    return implode('.', $segments);
}


function jwt認証($jwt, $key){
    if(substr_count($jwt, ".") !== 2) { return false; }

    list($headb64, $datab64, $cryptob64) = explode('.', $jwt);
    $header = json_decode(base64_decode_urlsafe($headb64));
    $data   = json_decode(base64_decode_urlsafe($datab64), true);
    $sign   = base64_decode_urlsafe($cryptob64);

    if(!$header or !$data) { return false; }
    if($header->alg !== "HS256") { return false; }
    if($sign !== hash_hmac('sha256', "$headb64.$datab64", $key, true)) { return false; }

    return $data;
}


function base64_encode_urlsafe($input){
    return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
}


function base64_decode_urlsafe($input){
    $remainder = strlen($input) % 4;
    if($remainder){
        $padlen = 4 - $remainder;
        $input .= str_repeat('=', $padlen);
    }
    return base64_decode(strtr($input, '-_', '+/'));
}


function ベーシック認証($認証関数, $realm="member only"){
    if(isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW'])){
        if(call_user_func($認証関数, $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) === true){
            return $_SERVER['PHP_AUTH_USER'];
        }
    }
    header("WWW-Authenticate: Basic realm=\"$realm\"");
    header("HTTP/1.0 401 Unauthorized");
    return false;
}


function 連想配列ソート(array &$array){
    array_multisort(array_values($array), SORT_DESC, SORT_NATURAL, array_keys($array), SORT_ASC, SORT_NATURAL, $array);
}


function MIMEタイプ($path){ // http://www.iana.org/assignments/media-types/media-types.xhtml
    $list = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'bmp'  => 'image/bmp',
        'svg'  => 'image/image/svg+xml',
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


$_ENV['定数'] = function ($v){ return $v; };


function データベース($table, $driver = null, $user = null, $pass = null){
    return new データベース($table, $driver, $user, $pass);
}


class データベース{
    private static $pdo = [];
    public  static $件数 = 31;
    private $ドライバー;
    private $接続名;
    private $テーブル;
    private $主キー = "id";

    public function __construct($table, $driver = null, $user = null, $password = null){
        $this->テーブル($table);
        if(!$driver){
            $driver   = $_ENV['データベース.ドライバー'];
            $user     = $_ENV['データベース.ユーザー名'];
            $password = $_ENV['データベース.パスワード'];
            if(!$driver){ throw new Exception('データベースの設定がありません。$_ENV[\'データベース.ドライバー\']に値を設定してください', 500);}
        }
        $this->ドライバー = $driver;
        $this->接続名     = md5($driver.$user.$password);
        if(!isset(self::$pdo[$this->接続名])){ self::$pdo[$this->接続名] = $this->接続($driver, $user, $password); }
        return $this;
    }

    private function 接続($driver, $user = null, $password = null){
        $setting = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        ];
        foreach((array)$_ENV['データベース.詳細設定'] as $name => $value){ $setting[$name] = $value; }

        try{
            $pdo = new PDO($driver, $user, $password, $setting);
        }
        catch(PDOException $e){
            throw new Exception("データベースに接続できません。データベースの設定(ドライバー,ユーザー名,パスワード)を再確認してください", 500, $e);
        }
        return $pdo;
    }

    public function 実行($SQL文, array $割当 = null){
        $stmt = self::$pdo[$this->接続名] -> prepare($SQL文);
        for($i = 1; $i <= count($割当); $i++){
            $type = gettype($割当[$i-1]);
            if($type === "integer" or $type === "boolean"){ $stmt -> bindValue($i, $割当[$i-1], PDO::PARAM_INT); }
            else if($type === "resource"){ $stmt -> bindValue($i, $割当[$i-1], PDO::PARAM_LOB); }
            else if($type === "NULL"){ $stmt -> bindValue($i, $割当[$i-1], PDO::PARAM_NULL); }
            else { $stmt -> bindValue($i, $割当[$i-1], PDO::PARAM_STR); }
        }
        $result = $stmt -> execute();
        return ($result) ? $stmt : false;
    }

    public function 取得(array $条件 = null){
        list($追加文, $割当, $行タイプ) = $this->追加SQL文($条件, "where");
        $SQL文 = "select * from {$this->テーブル} $追加文";
        return $this -> 実行($SQL文, $割当) -> fetchAll(...$行タイプ);
    }

    public function 行取得($id, array $条件 = null){
        list($追加文, $割当, $行タイプ) = $this->追加SQL文($条件, "where");
        $SQL文 = "select * from {$this->テーブル} where {$this->主キー} = ?";
        return $this -> 実行($SQL文, [(int)$id]) -> fetchAll(...$行タイプ)[0];
    }

    public function 列取得($列, array $条件 = null){
        $this->文字列検証($列);
        list($追加文, $割当) = $this->追加SQL文($条件, "where");
        $SQL文 = "select {$列} from {$this->テーブル} $追加文 ";
        return $this -> 実行($SQL文, $割当) -> fetchAll(PDO::FETCH_COLUMN);
    }

    public function セル取得($id, $列){
        $this->文字列検証($列);
        $SQL文 = "select {$列} from {$this->テーブル} where {$this->主キー} = ?";
        return $this -> 実行($SQL文, [(int)$id]) -> fetchColumn();
    }

    public function 件数(array $条件 = null){
        if($条件['式']){ $追加文 = "where {$条件['式']}"; }
        $SQL文 = "select count(*) from {$this->テーブル} $追加文";
        return $this -> 実行($SQL文, $条件['割当']) -> fetchColumn();
    }

    public function 検索($検索ワード, $列, array $条件 = null){

        foreach((array)$検索ワード as $単語){
            $単語 = addcslashes($単語, '_%');
            $割当1[] = "%$単語%";
        }

        $列 = (array)$列;
        foreach($列 as $単列){ $this->文字列検証($単列); }
        if(preg_match("/sqlite/i", $this->ドライバー)){
            $concat文字列 = "(" . implode('||',$列) . ")";
        }
        else{
            $concat文字列 = "concat(" . implode(',',$列) . ")";
        }
        $検索SQL = implode(' and ', array_fill(0,count($割当1),"$concat文字列 like ?"));

        list($追加文, $割当2, $行タイプ) = $this->追加SQL文($条件, "and");
        $割当 = array_merge($割当1, $割当2);

        $SQL文 = "select * from {$this->テーブル} where {$検索SQL} {$追加文} ";
        
        return $this -> 実行($SQL文, $割当) -> fetchAll(...$行タイプ);
    }

    public function 追加($data){
        if(gettype($data) === "object" and get_class($data) === "{$this->テーブル}定義"){ $data = $this->型変換($data, "{$this->テーブル}定義"); }
        foreach($data as $name => $value){
            $this->文字列検証($name);
            $into文1 .= "{$name},";
            $into文2 .= "?,";
            $割当[] = $value;
        }
        $into文1 = rtrim($into文1, ',');
        $into文2 = rtrim($into文2, ',');

        $SQL文 = "insert into {$this->テーブル} ($into文1) values ($into文2)";
        $this -> 実行($SQL文, $割当);
        return self::$pdo[$this->接続名] -> lastInsertId();
    }

    public function 更新($id, $data){
        if(gettype($data) === "object" and get_class($data) === "{$this->テーブル}定義"){ $data = $this->型変換($data, "{$this->テーブル}定義"); }
        foreach($data as $name => $value){
            $this->文字列検証($name);
            if(is_array($value) and array_key_exists('式', $value)){
                $set文 .= "{$name}={$value['式']},";
            }
            else{
                $set文 .= "{$name}=?,";
                $割当[] = $value;
            }
        }
        $set文 = rtrim($set文, ',');
        $割当[] = (int)$id;

        $SQL文 = "update {$this->テーブル} set {$set文} where {$this->主キー} = ?";
        return $this -> 実行($SQL文, $割当) -> rowCount();
    }

    public function 削除($id){
        $SQL文 = "delete from {$this->テーブル} where {$this->主キー} = ?";
        return $this -> 実行($SQL文, [(int)$id]) -> rowCount();
    }

    public function 作成(array $テーブル定義, $追加文 = ""){
        foreach($テーブル定義 as $name => $value){
            $this->文字列検証($name);
            $列情報 .= "$name $value,";
        }
        $列情報 = rtrim($列情報, ',');
        $SQL文 = "create table IF NOT EXISTS {$this->テーブル} ($列情報) ";

        if(preg_match('/^sqlite/i', $this->ドライバー)){ //SQLite用
            $SQL文  = str_replace('auto_increment', 'autoincrement', $SQL文);
        }
        else { //MySQL用
            $SQL文  = str_replace('autoincrement', 'auto_increment', $SQL文);
            $追加文 = ($追加文) ?: "ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
        }
        $result = $this -> 実行($SQL文.$追加文);
        return ($result) ? true : false;
    }

    public function インデックス作成($列){
        $this->文字列検証($列);
        $SQL文  = "create index {$列}インデックス on {$this->テーブル} ($列)";
        $result = $this -> 実行($SQL文);
        return ($result) ? true : false;
    }

    public function トランザクション開始(){
        self::$pdo[$this->接続名] -> beginTransaction();
        return $this;
    }

    public function トランザクション終了(){
        self::$pdo[$this->接続名] -> commit();
        return $this;
    }

    public function トランザクション失敗(){
        self::$pdo[$this->接続名] -> rollBack();
        return $this;
    }

    public function 切断(){
        self::$pdo[$this->接続名] = null; //staticはGCの対象にならなくて切断できないかも(不明)
    }

    public function テーブル($arg = null){
        if($arg){
            $this->文字列検証($arg);
            $this->テーブル = $arg;
            return $this;
        }
        else{
            return $this->テーブル;
        }
    }

    public function 主キー($arg = null){
        if($arg){
            $this->文字列検証($arg);
            $this->主キー = $arg;
            return $this;
        }
        else{
            return $this->id;
        }
    }

    private function 文字列検証($str){
        if(preg_match("/[[:cntrl:][:punct:][:space:]]/", $str)){ throw new Exception("引数に不正な文字列が含まれています", 500); }
    }

    private function 追加SQL文(array $条件 = null, $WHEREorAND = "where"){
        $割当  = (array)$条件['割当'];
        if($条件["式"]){ $SQL文 = " $WHEREorAND {$条件['式']} "; }

        if(count($条件["順番"]) === 2){
            $this->文字列検証($条件["順番"][0]);
            $順番列 = ($条件["順番"][0]) ? $条件["順番"][0] : $this->主キー;
            $順番順 = ($条件["順番"][1] == "小さい順") ? "asc" : "desc";
        }
        else{
            $順番列 = $this->主キー;
            $順番順 = "desc";
        }
        $SQL文 .= " order by $順番列 $順番順 ";

        if(!$条件["件数"]){ $条件["件数"] = self::$件数; }
        if(!$条件["位置"]){ $条件["位置"] = 0; }
        if($条件["件数"] === "∞"){
            $SQL文 .= " offset ? ";
            $割当[] = (int)$条件["位置"];
        }
        else{
            $SQL文 .= " limit ? offset ?";
            $割当[] = (int)$条件["件数"];
            $割当[] = (int)$条件["位置"];
        }

        $行タイプ = [];
        if($条件['行タイプ']){
            if($条件['行タイプ'] === "オブジェクト"){ $行タイプ[0] = PDO::FETCH_OBJ; }
            else if($条件['行タイプ'] === "連想配列"){ $行タイプ[0] = PDO::FETCH_ASSOC; }
            else if($条件['行タイプ'] === "配列"){ $行タイプ[0] = PDO::FETCH_NUM; }
            else { $行タイプ = [PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $条件['行タイプ']]; }
        }
        else{
            if($_ENV['データベース.詳細設定'][PDO::ATTR_DEFAULT_FETCH_MODE] & PDO::FETCH_CLASS){
                $行タイプ = [$_ENV['データベース.詳細設定'][PDO::ATTR_DEFAULT_FETCH_MODE], "{$this->テーブル}定義"];
            }
        }

        return [$SQL文, $割当, $行タイプ];
    }

    private function 型変換($object, $table){
        $定義 = constant("$table::定義");
        $data = [];
        foreach($定義 as $列名 => $型情報){
            if(!isset($object->$列名)){ continue; } //nullどうしようか
            if(isset($object->$列名['式'])){
                $data[$列名] = $object->$列名;
                continue;
            }
            $型 = explode(" ", $型情報)[0];
            if(preg_match("/INT/i", $型)){ $data[$列名] = (int)$object->$列名; }
            else if(preg_match("/CHAR|TEXT|CLOB/i", $型)){ $data[$列名] = (string)$object->$列名; }
            else if(preg_match("/REAL|FLOA|DOUB/i", $型)){ $data[$列名] = (float)$object->$列名; }
            else{ $data[$列名] = $object->$列名; }
        }
        return $data;
    }
}



class 部品{
    private static $設定;
    private static $記憶;
    private static $結果;
    private static $解析;


    public static function 開始($dir = __DIR__."/部品", array $option = []){
        if(self::$設定){ return; }
        if(!is_dir($dir)){ throw new Exception("部品ディレクトリが存在しません", 500); }
        self::$設定 = $option + [
            "手動"=>false,
            "nonce"=>null,
        ];
        self::$設定["ディレクトリ"] = realpath($dir);

        self::$記憶 = ['部品コード'=>[], 'stack'=>[], '読み込み済みURL'=>[], 'fromphp'=>[], 'キャプチャ開始'=>false];
        self::$結果 = ['css'=>'', 'jsinhead'=>'', 'jsinbody'=>'', 'fromphp'=>''];
        self::$解析 = [];
        self::関数登録();

        if(!self::$設定['手動']){
            self::$記憶['キャプチャ開始'] = true;
            ob_start(["部品", "差し込む"]);
        }
    }

    public static function 終了(){
        $return = (self::$記憶['キャプチャ開始'] === true)  ?  self::差し込む(ob_get_clean())  :  "";
        self::$設定 = self::$記憶 = self::$結果 = self::$解析 = null;
        return $return;
    }

    public static function 作成($部品名, $引数){
        if(!isset(self::$記憶['部品コード'][$部品名])){
            if(!isset(self::$解析[$部品名])){ self::$解析[$部品名] = self::ファイル解析($部品名); }

            if(preg_match("/^function/", self::$解析[$部品名]['php'])){
                self::$記憶['部品コード'][$部品名] = eval("return " . self::$解析[$部品名]['php'] . ";");
            }
            else{
                self::$記憶['部品コード'][$部品名] = self::$解析[$部品名]['php'];
            }

            self::$結果['css'] .= self::タグ完成(self::$解析[$部品名]['css'], "href", $部品名);
            self::$結果['jsinhead'] .= self::タグ完成(self::$解析[$部品名]['jsh'], "src", $部品名);
            self::$結果['jsinbody'] .= self::タグ完成(self::$解析[$部品名]['jsb'], "src", $部品名);
        }

        self::$記憶['stack'][] = $部品名;
        if(count(self::$記憶['stack']) > 250){ throw new Exception("[$部品名]:ループ数が上限に達しました", 500); }

        if(is_callable(self::$記憶['部品コード'][$部品名])){
            $html = self::$記憶['部品コード'][$部品名](...$引数);
        }
        else{
            $html = self::$記憶['部品コード'][$部品名];
        }

        array_pop(self::$記憶['stack']);
        return $html;
    }

    public static function 差し込む($buf){
        self::$結果['fromphp'] = self::fromphpタグ完成();

        if(self::$結果['jsinbody']){
            $pos = strripos($buf, "</body>");
            if($pos !== false){
                $buf = substr_replace($buf, self::$結果['jsinbody'], $pos, 0); //最後に出現する</body>の前にJSを挿入する
            }
            else{
                $buf = $buf . self::$結果['jsinbody'];
            }
        }
        if(self::$結果['css'] || self::$結果['jsinhead'] || self::$結果['fromphp']){
            $pos = stripos($buf, "</title>");
            if($pos !== false){
                $buf = substr_replace($buf, "\n".self::$結果['css'].self::$結果['fromphp'].self::$結果['jsinhead'], $pos+8, 0); //最初に出現する</title>の前に挿入する
            }
        }
        return $buf;
    }

    public static function タグ取得(){
        self::$結果['fromphp'] = self::fromphpタグ完成();
        return self::$結果;
    }

    public static function fromphp($data){
        $部品名 = end(self::$記憶['stack']);
        if($部品名){
            self::$記憶['fromphp'][$部品名] = json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
        }
    }




    private static function 関数登録(){
        if(function_exists("部品")){ return; }
        function 部品($部品名, ...$引数){
            return 部品::作成($部品名, $引数);
        }
    }

    private static function ファイル取得($部品名){
        if(!self::$設定['ディレクトリ']){ throw new Exception("部品::開始() を行ってください", 500); }
        if(preg_match("/^[^a-zA-Z\x7f-\xff][^a-zA-Z0-9_\x7f-\xff]*/", $部品名)){ throw new Exception("部品名はPHPの変数の命名規則に沿ってください", 500); }

        $path = self::$設定['ディレクトリ'] . "/" . str_replace("_", "/", $部品名) . ".html";
        $return = file_get_contents($path);
        if(!$return){ throw new Exception("部品ファイルが見つかりません: $path", 500); }
        return $return;
    }

    private static function ファイル解析($部品名){
        $return = ["css"=>[], "jsh"=>[], "jsb"=>[], "php"=>""];
        $html = self::ファイル取得($部品名);

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
        for($i=0; $i<count($script[0]); $i++){
            if(preg_match("/\stype=[\"']部品[\"']/ui", $script[1][$i][0])){
                $return["php"] = preg_replace("/^function[^\(]*/", "function", ltrim($script[2][$i][0]));
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

    private static function タグ完成(array $array, $link, $部品名){
        $return = "";
        foreach($array as $v){
            preg_match("|^([^>]+)|", $v, $attr);
            if(preg_match("|\s$link=[\"\']([\s\S]*?)[\"\']|i", $attr[0], $match)){
                $url = $match[1];
                if(!preg_match("#^(/|https?://)#", $url)){ //相対パスなら
                    $url = realpath(self::$設定['ディレクトリ'] . "/" . dirname(str_replace("_", "/", $部品名)) . "/" . $url);
                    if(in_array($url, self::$記憶['読み込み済みURL'])){ continue; }
                    $content = file_get_contents($url);
                    $v = ($link === "href")  ?  "<style>\n$content</style>"  :  "<script>\n$content</script>";
                }
                else{
                    if(in_array($url, self::$記憶['読み込み済みURL'])){ continue; }
                }
                self::$記憶['読み込み済みURL'][] = $url;
            }
            if(isset(self::$設定['nonce'])){ $v = preg_replace("/^<(\w+)/", '<$1 nonce="'.self::$設定['nonce'].'" ', $v); }
            $return .= $v . "\n";
        }
        return $return;
    }

    private static function fromphpタグ完成(){
        if(!self::$記憶['fromphp']){ return ""; }

        $return  = isset(self::$設定['nonce'])  ?  '<script nonce="'.self::$設定['nonce'].'">'  :  '<script>';
        $return .= "\nvar fromphp = {};\n";

        foreach(self::$記憶['fromphp'] as $key => $val){
            $return .= "fromphp['$key'] = $val;\n";
        }
        return $return."</script>\n";
    }
}


class 文書 implements Countable, IteratorAggregate, ArrayAccess{
    private $文書;
    private $選択 = [];
    private $選択記憶 = [];
    private $種類 = "html";

    public function __construct($str = '<!DOCTYPE html><html lang="ja"><head><meta charset="utf-8"><title></title></head><body></body></html>'){
        $this->文書 = new DOMDocument(); // https://secure.php.net/manual/ja/class.domdocument.php
        libxml_use_internal_errors(true);  // loadHTML() の警告抑制
        libxml_disable_entity_loader(true);

        $str = preg_replace("/^[^<]+/", "", $str);
        if(!preg_match("/^<\?xml/i", $str)){ //HTML
            if(!preg_match("/^<\!DOCTYPE/i", $str)){ //ドキュメントタイプがないと変なドキュメントタイプが勝手に追加されるので対策
                $str = "<!DOCTYPE html>\n$str";
                $this->種類 = "断片";
            }
            $str = '<?xml encoding="UTF-8">' . $str; //文字化け対策のおまじない。出力時のsaveXML($this->文書->doctype).saveHTML($this->文書->documentElement)とセットで使う
            $this->文書->loadHTML($str, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED | LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_COMPACT); // https://php.net/manual/ja/libxml.constants.php
        }
        else{ //XML
            $this->文書->loadXML($str, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_COMPACT);
            $this->種類 = "xml";
        }

        $this->文書->formatOutput = true;
        $this->文書->encoding = "utf-8";
    }

    public function 本文($str = null){
        if($str === null){
            if(isset($this->選択[0])){
                $return = $this->選択[0]->textContent;
            }
            return (string)$return;
        }
        else{
            foreach($this->選択 as $where){
                $where->textContent = $str;
            }
            return $this;
        }
    }


    public function html($str = null){
        if($str === null){ //innerHTML
            if(isset($this->選択[0])){
                foreach($this->選択[0]->childNodes as $child){
                    $return .= $this->文書->saveHTML($child);
                }
            }
            return (string)$return;
        }
        if($str === true){ //outerHTML
            if(isset($this->選択[0])){
                $return = $this->文書->saveHTML($this->選択[0]);
            }
            return (string)$return;
        }
        else{
            $add = $this->文字列DOM化($str);
            foreach($this->選択 as $where){
                $where->textContent = "";
                if($str !== ""){ $where->appendChild($add->cloneNode(true)); }
            }
            return $this;
        }
    }


    public function タグ名(){
        if(isset($this->選択[0])){
            $return = strtolower($this->選択[0]->tagName);
        }
        return (string)$return;
    }

    public function 属性($name = null, $value = null){
        if(is_string($name) and $value === null){ //属性値を1つ取得
            if(isset($this->選択[0])){
                $return = $this->選択[0]->getAttribute($name);
            }
            return (string)$return;
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
            if(isset($this->選択[0])){
                $attrs = $this->選択[0]->attributes;
                for($i = 0; $i < $attrs->length; $i++){
                    $return[$attrs->item($i)->name] = $attrs->item($i)->value;
                }
            }
            return (array)$return;
        }
        return $this;
    }

    public function 属性削除($name = null){
        if(is_string($name)){
            foreach($this->選択 as $where){
                $where->removeAttribute($name);
            }
        }
        else{
            foreach($this->選択 as $where){
                $attrs = $where->attributes;
                for($i = $attrs->length - 1; $i >= 0; $i--){
                    $where->removeAttribute($attrs->item($i)->name);
                }
            }
        }
        return $this;
    }

    public function dom($tag = null, $attr = null, $content = null){
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

    public function 追加($add, $relation){
        return $this->DOM操作($this->DOM箱作成($add), $relation);
    }

    public function 貼り付け($selector, $relation){
        $this($selector);
        return $this->DOM操作($this->選択記憶, $relation);
    }

    public function 削除(){
        return $this->DOM操作([], "削除");
    }

    public function 検索($selector){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->セレクタ検索($selector, false, $where));
        }
        return $this->選択保存($this->重複ノード解消($新選択));
    }

    public function 最初(){
        return $this->選択保存(array_slice($this->選択, 0, 1));
    }

    public function 最後(){
        return $this->選択保存(array_slice($this->選択, -1, 1));
    }
    
    public function n($n, $m = 1){
        return $this->選択保存(array_slice($this->選択, $n, $m));
    }

    public function 親($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "parentNode", true));
        }
        if($selector){ $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false)); }
        return $this->選択保存($this->重複ノード解消($新選択));
    }

    public function 兄($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "previousSibling", true));
        }
        if($selector){ $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false)); }
        return $this->選択保存($this->重複ノード解消($新選択));
    }

    public function 弟($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "nextSibling", true));
        }
        if($selector){ $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false)); }
        return $this->選択保存($this->重複ノード解消($新選択));
    }

    public function 子持ち($selector = null){
        $新選択 = [];
        if(!$selector){ $selector = "*"; }
        foreach($this->選択 as $where){
            if(count($this->セレクタ検索($selector, false, $where))){
                $新選択[] = $where;
            }
        }
        return $this->選択保存($新選択);
    }

    public function 親全て($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "parentNode", false));
        }
        if($selector){ $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false)); }
        return $this->選択保存($this->重複ノード解消($新選択));
    }

    public function 兄全て($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "previousSibling", false));
        }
        if($selector){ $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false)); }
        return $this->選択保存($this->重複ノード解消($新選択));
    }

    public function 弟全て($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "nextSibling", false));
        }
        if($selector){ $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false)); }
        return $this->選択保存($this->重複ノード解消($新選択));
    }

    public function 兄弟全て($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            $新選択 = array_merge($新選択, $this->家族探索($where, "previousSibling", false), $this->家族探索($where, "nextSibling", false));
        }
        if($selector){ $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false)); }
        return $this->選択保存($this->重複ノード解消($新選択));
    }

    public function 子($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            foreach($where->childNodes as $child){
                if($child->nodeType === XML_ELEMENT_NODE){
                    $新選択[] = $child;
                    continue 2;
                }
            }
        }
        if($selector){ $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false)); }
        return $this->選択保存($this->重複ノード解消($新選択));
    }

    public function 子全て($selector = null){
        $新選択 = [];
        foreach($this->選択 as $where){
            foreach($where->childNodes as $child){
                if($child->nodeType === XML_ELEMENT_NODE){ $新選択[] = $child; }
            }
        }
        if($selector){ $新選択 = $this->積集合($新選択, $this->セレクタ検索($selector, false)); }
        return $this->選択保存($this->重複ノード解消($新選択));
    }

    public function かつ($selector){
        $新選択 = $this->積集合($this->選択, $this->セレクタ検索($selector, false));
        return $this->選択保存($新選択);
    }

    public function 足す($selector){
        $新選択 = array_merge($this->選択, $this->セレクタ検索($selector, false));
        return $this->選択保存($this->重複ノード解消($新選択));
    }

    public function 引く($selector){
        $新選択 = $this->差集合($this->選択, $this->セレクタ検索($selector, false));
        return $this->選択保存($新選択);
    }

    public function なら($selector){
        foreach($this->セレクタ検索($selector, false) as $val){
            foreach($this->選択 as $where){
                if($where->isSameNode($val)){ return true; }
            }
        }
        return false;
    }

    public function 逆順(){
        return $this->選択保存(array_reverse($this->選択));
    }

    public function 前の選択(){
        list($this->選択記憶, $this->選択) = [$this->選択, $this->選択記憶];
        return $this;
    }

    public function d(){
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
    public function __toString(){
        return $this->HTML全体文字列();
    }

    public function __invoke($selector = null){
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
        else if($selector instanceof DOMElement){
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
    public function getIterator(){
        $clone = [];
        for($i = 0; $i < count($this->選択); $i++){
            $clone[] = clone $this;
            $clone[$i]->選択 = [$this->選択[$i]];
            $clone[$i]->選択記憶 = [];
        }
        return new ArrayObject($clone);
    }

    //■Countableインターフェースの実装
    public function count() { 
        return count($this->選択);
    }

    //■ArrayAccessインターフェースの実装
    public function offsetGet($offset){
        return $this->選択保存(array_slice($this->選択, $offset, 1));
    }
    public function offsetSet($offset, $value){}
    public function offsetExists($offset){}
    public function offsetUnset($offset) {}


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
            if($input === $this){ return $dom箱; }
            $root = $input->文書->documentElement;
            $input = [];
            while($root){
                if($root->nodeType === XML_ELEMENT_NODE){ $input[] = $root; }
                $root = $root->nextSibling;
            }
        }
        if($input instanceof DOMElement){ //DOMの場合
            $input = [$input];
        }
        if(is_array($input) or ($input instanceof DOMNodeList)){ //配列の場合
            foreach($input as $node){
                if(!($node instanceof DOMElement)){ continue; }
                if($node->ownerDocument !== $this->文書){ $node = $this->文書->importNode($node, true); }
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
        if(!$selector){ return $return; }

        $expression = $this->selector2XPath($selector);
        if($context){ $expression = preg_replace("|^//|", ".//", $expression); } //相対パスにする

        foreach((new DOMXPath($this->文書))->query($expression, $context) as $node){ //DOMNodeList(複数形)が返る https://secure.php.net/manual/ja/class.domxpath.php
            $return[] = $node;
        }
        if($記録する){ $this->選択保存($return); }
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
                if($一人だけ){ break; }
            }
            $node = $node->$続柄;
        }
        return $return;
    }

    private function 重複ノード解消(array $array){
        $return = [];
        foreach($array as $v1){
            if(!($v1 instanceof DOMNode)){ continue; }
            foreach($return as $v2){
                if($v1->isSameNode($v2)){ continue 2; }
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
                if($v1->isSameNode($v2)){ continue 2; }
            }
            $return[] = $v1;
        }
        return $return;
    }

    private function HTML全体文字列(){
        $type = $this->文書->doctype;
        $node = $this->文書->documentElement;

        switch($this->種類){
            case "html":
                return $this->文書->saveXML($type) . "\n" . $this->文書->saveHTML($node);
            case "xml":
                return $this->文書->saveXML($type) . "\n" . $this->文書->saveXML($node);
            case "断片":
                while($node){
                    if($node->nodeType === XML_ELEMENT_NODE){ $return .= $this->文書->saveHTML($node) . "\n"; }
                    $node = $node->nextSibling;
                }
                return (string)$return;
        }
    }

    private function element2array($element) {
        $return = ["tag"=>$element->tagName];
        foreach ($element->attributes as $attribute) {
            $return[$attribute->name] = $attribute->value;
        }
        foreach ($element->childNodes as $subElement) { // nodeType: http://www.php.net/manual/en/dom.constants.php
            if ($subElement->nodeType === XML_ELEMENT_NODE) {
                $return["child"][] = $this->element2array($subElement);
            }
            elseif ($subElement->nodeType === XML_TEXT_NODE){
                $return["text"] = $subElement->textContent;
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


function functionphpエラー($str, $type = E_USER_WARNING){
    //$typeは次の4つ: E_USER_ERROR / E_USER_WARNING / E_USER_NOTICE / E_USER_DEPRECATED
    //E_USER_ERRORは停止する
    //1つ目のエラーはdisplay_errors(ini)でSTDOUT、2つ目のエラーはlog_errors(ini)でSTDERR
    //set_error_handler() で捕捉してね

    $除外パス = __FILE__;
    foreach(debug_backtrace() as $trace){
        if(strpos($trace['file'], $除外パス) !== 0){ break; }
    }
    $message = "[function.phpエラー] $str \n{$trace['file']} {$trace['line']}行目\n\n";

    if(PHP_SAPI !== "cli"){
        $message = htmlspecialchars($message, ENT_QUOTES, "UTF-8", false);
        $message = nl2br($message);
    }
    trigger_error($message, $type);
} 
