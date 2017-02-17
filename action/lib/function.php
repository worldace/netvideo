<?php
//======================================================
// ■function.php http://musou.s38.xrea.com/php/
// 
// 呼び出し元: "../../index.php"
//======================================================


function クラスローダ($dir = __DIR__){
    spl_autoload_register(function($class) use($dir){
        $class = str_replace("_", "/", $class);
        require_once "{$dir}/{$class}.php";
    });
}


function route(array $route, $arg = 1){
    $_ENV['route.return'] = $arg;
    $_ENV['route.dir'] = dirname(debug_backtrace()[0]['file']);
    if(!function_exists("required")){
        function required(){ return $_ENV['route.return']; }
    }

    foreach((array)$route as $_ENV['route.file']){
        if(preg_match("#^(?!(/|\w:|http)).+#", $_ENV['route.file'])){ //相対パスなら
            $_ENV['route.file'] = $_ENV['route.dir'] . "/" . $_ENV['route.file'];
        }
        $func = function (){ return require_once $_ENV['route.file']; };
        $_ENV['route.return'] = $func();
    }
    exit;
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
        print $_GET['callback'] . "(" . json_encode($json) . ");";
    }
    else{ //JSON
        header("Content-Type: application/json; charset=utf-8");
        print json_encode($json);
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
        else                                                         { throw new プログラミングバグ(__function__."() 第3引数の関数名が間違っています"); }
    }
    
    if(検査::$例外 === true and $result === false){
        エラー400("{$name}の値が間違っています");
    }
    
    if($result === true){ return true; }
    elseif($result === false){ return false; }
    else{ throw new プログラミングバグ(__function__."() 第3引数の関数はtrueまたはfalseを返してください"); }
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


function ファイルダウンロード($data, $filename = null, $timeout = 60*60*12){
    ini_set("max_execution_time", $timeout);
    if(!file_exists($data)){ エラー404("ダウンロードファイルが存在しません"); }
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
    print($data);
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


function GET送信($url, array $querymap = null, array $request_header = null){
    if(is_array($request_header)){ $request_header = str_replace(["\r","\n"], "", $request_header); }
    if($querymap and preg_match("/\?/", $url)){ $url .= "&" . http_build_query($querymap, "", "&"); }
    else if($querymap){ $url .= "?" . http_build_query($querymap, "", "&"); }
    $request = stream_context_create(['http'=>['method'=>'GET', 'header'=>implode("\r\n", (array)$request_header)]]);
    $contents = file_get_contents($url, false, $request);
    $_ENV['RESPONSE_HEADER'] = ($contents !== false) ? $http_response_header : [];
    return $contents;
}


function POST送信($url, array $querymap = null, array $request_header = null){
    if(is_array($request_header)){ $request_header = str_replace(["\r","\n"], "", $request_header); }
    $request = stream_context_create(['http'=>['method'=>'POST','header'=>implode("\r\n",(array)$request_header),'content'=>http_build_query((array)$querymap,"","&")]]);
    $contents = file_get_contents($url, false, $request);
    $_ENV['RESPONSE_HEADER'] = ($contents !== false) ? $http_response_header : [];
    return $contents;
}


function ファイル送信($url, array $querymap = null, array $request_header = null){
    if(is_array($request_header)){ $request_header = str_replace(["\r","\n"], "", $request_header); }
    $区切り = "__" . uuid() . "__";
    $request_header[] = "Content-Type: multipart/form-data; boundary=$区切り";
    foreach($querymap as $name => $value){
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
    $request = stream_context_create(['http'=>['method'=>'POST', 'header'=>implode("\r\n",(array)$request_header), 'content'=>$content]]);
    $contents = file_get_contents($url, false, $request);
    $_ENV['RESPONSE_HEADER'] = ($contents !== false) ? $http_response_header : [];
    return $contents;
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
    if(count($parsed) < 2 or empty($parsed[2])){ return false; }
    return $parsed[0] . "//" . $parsed[2] . "/";
}


function 親URL($url){
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


function 本番環境なら(){
    return ini_get("display_errors") ? false : true;
}


function 開発環境なら(){
    return ini_get("display_errors") ? true : false;
}


function PHPが($str){
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
    if(is_array($arg)){ return array_map("h", $arg); }
    return htmlspecialchars($arg, ENT_QUOTES, "UTF-8");
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


function 開始タグ($tag, array $attr = []){
    foreach($attr as $name => $value){ $attr_str .= " $name=\"$value\""; }
    return "<$tag$attr_str>";
}


function 終了タグ($tag){
    return "</$tag>";
}


function 自動リンク($arg = "", array $attr = []){
    if(is_array($arg)){ return array_map(function($str) use($attr){ return 自動リンク($str, $attr); }, $arg); }
    foreach($attr as $name => $value){ $attr_str .= " $name=\"$value\""; }
    return preg_replace("|(https?://[^[:space:]　\r\n<>]+)|ui", "<a href=\"$1\"$attr_str>$1</a>", $arg);
}


function 配列→table(array $array, array $attr = []){
    $firstkey = key($array);
    if(is_array($array[$firstkey])){ $tag = "<tr>\n<th></th>\n<th>" . implode("</th>\n<th>",array_keys($array[$firstkey])) . "</th>\n</tr>\n"; }
    foreach($array as $key1 => $value1){
        $tag .= "<tr>\n<th>$key1</th>\n";
        foreach((array)$value1 as $key2 => $value2){ $tag .= "<td>$value2</td>\n"; }
        $tag .= "</tr>\n";
    }
    foreach($attr as $name => $value){ $attr_str .= " $name=\"$value\""; }
    return "<table$attr_str>\n$tag\n</table>";
}


function dd(){
    foreach(func_get_args() as $var){
        $out = var_export($var, true);
        if(php_sapi_name() === 'cli'){ $str .= $out; }
        else{ $str .= "<pre>$out</pre>"; }
        $str .= "\n\n";
    }
    return $str;
}


function newa($class = null, $args = null, $trait = null){
    if($class !== null){
        if(class_exists($class)){ $class_code = "extends $class"; }
        else if(interface_exists($class)){ $class_code = "implements $class"; }
        else{ throw new プログラミングバグ(__function__."() 第2引数の[$class]クラス/インターフェースが見つかりません"); }
    }
    if($trait !== null){
        foreach((array)$trait as $value){
            if(!trait_exists($value)){ throw new プログラミングバグ(__function__."() 第1引数の[$value]トレイトが見つかりません"); };
        }
        $trait_code = "use " . implode(",", (array)$trait) . ";";
    }
    eval("\$object = new class(...(array)\$args) $class_code{ $trait_code };");
    return $object;
}


function パーミッション($path, $permission = null){
    if(!preg_match("/^0/", $permission) and $permission >= 100 and $permission <= 777){
        chmod($path, octdec($permission));
    }
    return decoct(fileperms($path) & 0777);
}


function ファイル一覧($dir = ".", $pattern = "/./"){
    if(!is_dir($dir)){ return false; }
    foreach(array_diff(scandir($dir), ['.','..']) as $file){
        if(is_file("$dir/$file") and preg_match($pattern, $file)){
            yield realpath("$dir/$file");
        }
        if(is_dir("$dir/$file")) {
            foreach(ファイル一覧("$dir/$file", $pattern) as $sub){
                yield $sub;
            }
        }
    }
}


function ディレクトリ一覧($dir = ".", $pattern = "/./"){
    if(!is_dir($dir)){ return false; }
    foreach(array_diff(scandir($dir), ['.','..']) as $file){
        if(is_dir("$dir/$file")) {
            if(preg_match($pattern, $file)){
                yield realpath("$dir/$file");
            }
            foreach(ディレクトリ一覧("$dir/$file", $pattern) as $sub){
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
    $result = file_put_contents($tempfile, serialize($data), LOCK_EX);
    return ($result === false) ? false : $name;
}


function 一時取得($name){
    $tempfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . get_current_user() . "_" . md5($name);
    return (file_exists($tempfile)) ? unserialize(file_get_contents($tempfile)) : false;
}


function JSON保存($file, $data){
    $result = file_put_contents($file, "<?php\n".json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES), LOCK_EX);
    return ($result === false) ? false : $file;
}


function JSON取得($file){
    $json = file_get_contents($file);
    if($json === false){ return false; }
    $json = preg_replace("/^<\?php\r?\n/i", "", $json);
    return json_decode($json, true);
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
        $xmlstr = preg_replace("/&(?!amp;)/", "&amp;" ,$xmlstr);
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


function tojs($data){
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
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


function 暗号化($str, $key, $type = 'aes-256-ecb'){
    return openssl_encrypt($str, $type, $key);
}


function 復号化($str, $key, $type = 'aes-256-ecb'){
    return openssl_decrypt($str, $type, $key);
}


function jwt発行($data, $key){
    $header     = ['typ'=>'jwt', 'alg'=>'HS256'];
    $segments   = [];
    $segments[] = base64_encode_urlsafe(json_encode($header));
    $segments[] = base64_encode_urlsafe(json_encode($data));

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


function テンプレート変換($テンプレート, array $変換関係 = [], $自動エスケープ = true){
    if($自動エスケープ === true){ $変換関係 = h($変換関係); }
    return preg_replace_callback("|《([^》]*)》|u", function($match) use($変換関係){ return $変換関係[$match[1]]; }, $テンプレート);
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
            if(!$driver){ throw new 設定バグ('データベースの設定がありません。$_ENV[\'データベース.ドライバー\']に値を設定してください');}
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
            throw new 設定バグ("データベースに接続できません。データベースの設定(ドライバー,ユーザー名,パスワード)を再確認してください", 0, $e);
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
        if(preg_match("/[[:cntrl:][:punct:][:space:]]/", $str)){ エラー500("引数に不正な文字列が含まれています"); }
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


function 部品(){
    $引数   = func_get_args();
    $部品名 = array_shift($引数);
    return 部品::作成($部品名, true, $引数);
}


function 生部品(){
    $引数   = func_get_args();
    $部品名 = array_shift($引数);
    return 部品::作成($部品名, false, $引数);
}


class 部品{
    private static $ディレクトリ = "./parts";
    private static $初期化済み = false;
    private static $記憶 = [];
    private static $結果 = [];


    public static function 設定($dir = "./parts"){
        self::$ディレクトリ   = preg_replace("|/$|", "", $dir);
        if(!self::$初期化済み){ self::初期化(); }
    }

    public static function 作成($部品名, $自動エスケープ, $引数){
        if($部品名 === null){ throw new プログラミングバグ('部品名がありません'); }
        if(!self::$初期化済み){ throw new 設定バグ('部品::設定()で部品ディレクトリを指定してください'); }
        if($自動エスケープ){ $引数 = self::h($引数); }

        if(!self::$記憶[$部品名]['読み込み済み']){
            require self::$ディレクトリ . "/$部品名.php";
            self::$記憶[$部品名]['読み込み済み'] = true;
            self::$記憶[$部品名]['html'] = $html;

            if($cssfile){
                $cssfile = is_callable($cssfile) ? call_user_func_array($cssfile, $引数) : $cssfile;
                foreach((array)$cssfile as $_url){
                    if(in_array($_url, (array)self::$記憶['読み込み済みURL'])){ continue; }
                    $_cssfile .= "<link rel=\"stylesheet\" href=\"{$_url}\">\n";
                    self::$記憶['読み込み済みURL'][] = $_url;
                }
            }
            if($css){
                $_css = is_callable($css) ? call_user_func_array($css, $引数) : $css;
                $_css = ltrim($_css);
                $_css = preg_match("/^</", $_css) ? "$_css\n" : "<style>\n$_css\n</style>\n";
            }
            self::$結果['css'] .= $_cssfile . $_css;

            if($jsfile){
                $jsfile = is_callable($jsfile) ? call_user_func_array($jsfile, $引数) : $jsfile;
                foreach((array)$jsfile as $_url){
                    if(in_array($_url, (array)self::$記憶['読み込み済みURL'])){ continue; }
                    $_jsfile .= "<script src=\"{$_url}\"></script>\n";
                    self::$記憶['読み込み済みURL'][] = $_url;
                }
            }
            if($js){
                $_js = is_callable($js) ? call_user_func_array($js, $引数) : $js;
                $_js = ltrim($_js);
                $_js = preg_match("/^</", $_js) ? "$_js\n" : "<script>\n$_js\n</script>\n";
            }

            if($jsinhead){
                self::$結果['jsinhead'] .= $_jsfile . $_js;
            }
            else{
                self::$結果['jsinbody'] .= $_jsfile . $_js;
            }
        }
        else{
            $html = self::$記憶[$部品名]['html'];
        }
        
        return is_callable($html) ? call_user_func_array($html, $引数) : $html;
    }

    public static function 差し込み($buf){
        $code_in_head = self::$結果['css'] . self::$結果['jsinhead']; //面倒なので合成してしまう
        
        if(self::$結果['jsinbody']){
            $pos = strripos($buf, "</body>");
            if($pos !== false){
                $buf = substr_replace($buf, self::$結果['jsinbody'], $pos, 0); //最後に出現する</body>の前にJSを挿入する
            }
        }
        if($code_in_head){
            $pos = stripos($buf, "</head>");
            if($pos !== false){
                $buf = substr_replace($buf, $code_in_head, $pos, 0); //最初に出現する</head>の前に挿入する
            }
        }
        return $buf;
    }

    private static function 初期化(){
        self::$初期化済み = true;
        ob_start(["部品", "差し込み"]);
    }

    private static function h($arg = ""){
        if(is_array($arg)){ return array_map("部品::h", $arg); }
        return htmlspecialchars($arg, ENT_QUOTES, "UTF-8");
    }
}


function エラー400($str){
    throw new 実行エラー($str, 400);
}

function エラー404($str){
    throw new 実行エラー($str, 404);
}

function エラー500($str){
    throw new 実行エラー($str, 500);
}

class 実行エラー extends RuntimeException{
    use 例外の実装;
}

class プログラミングバグ extends LogicException{
    use 例外の実装;
}

class 設定バグ extends LogicException{
    use 例外の実装;
}

trait 例外の実装{
    public function __toString() {
        $trace = $this->getTrace();
        if(is_array($trace)){
            for($i=0; $i<count($trace); $i++){
                if($trace[$i]["file"] !== __FILE__){
                    $file = $trace[$i]["file"];
                    $line = $trace[$i]["line"];
                    break;
                }
            }
        }
        if(!$file){
            $file = $this->file;
            $line = $this->line;
        }

        $str  = "【" . get_class($this) . "】{$this->message}\n";
        $str .= "{$file} {$line}行目\n\n";
        $str .= "---------------------------------------------------\n";
        $str .= $this->getTraceAsString();
        $str .= "\n---------------------------------------------------\n";
        return $str;
    }

    public function getTitle() {
        return get_class($this);
    }

}
