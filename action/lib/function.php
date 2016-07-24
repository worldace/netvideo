<?php
//======================================================
// ■function.php http://musou.s38.xrea.com/php/
// 
// 呼び出し元: "../../index.php"
//======================================================


function クラスローダ($dir = __DIR__){
    spl_autoload_register(function($class) use($dir){
        if(preg_match("/^win/i", PHP_OS)){ $class = addslashes(mb_convert_encoding($class, 'SJIS', 'UTF-8')); }
        $class = str_replace("_", "/", $class);
        require_once "{$dir}/{$class}.php";
    });
}


function エラー($str = "エラーが発生しました"){
    header('HTTP', true, 400);
    print $str;
    exit;
}


function テキスト表示($str = ""){
    header("Content-Type: text/plain; charset=utf-8");
    print $str;
    exit;
}


function JSON表示($json = [], $callback = null, $allow = null){
    if(!$allow){ $allow = ($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : "*"; }
    header("Access-Control-Allow-Origin: $allow");
    header("Access-Control-Allow-Credentials: true");
    if($callback){ //JSONP
        header("Content-Type: text/javascript; charset=utf-8");
        print $callback . "(" . json_encode($json) . ");";
    }
    else{ //JSON
        header("Content-Type: application/json; charset=utf-8");
        print json_encode($json);
    }
    exit;
}


function リダイレクト($url){
    header("Location: $url");
    exit;
}


function ダウンロード($filepath = "", $filename = "", $data = "", $timeout = 60*60*24){
    @set_time_limit($timeout);
    if($data){
        $filesize = strlen($data);
    }
    else{
        $filesize = filesize($filepath);
        if(!$filename){ $filename = basename($filepath); }
    }
    $filenameE = rawurlencode($filename);
    header("Content-Type: application/force-download");
    header("Content-Length: $filesize");
    header("Content-Disposition: attachment; filename=\"$filename\"; filename*=UTF-8''$filenameE");

    while(ob_get_level()){ ob_end_clean(); }
    ($data) ? print($data) : readfile($filepath);
}


function メール送信($送信先, $送信元 = "", $送信者 = "", $題名 = "", $本文 = "", array $添付 = null, $cc = "", $bcc = ""){
    $題名 = mb_encode_mimeheader($題名, "ISO-2022-JP");
    $body = mb_convert_encoding($本文, "ISO-2022-JP", "UTF-8");

	if($送信元 and $送信者) { $header .= "From: " . mb_encode_mimeheader($送信者,"ISO-2022-JP") . " <$送信元>\r\n"; }
	else if($送信元) { $header .= "From: $送信元\r\n";	}
    if($cc) { $header .= "Cc: $cc\r\n"; }
    if($bcc){ $header .= "Bcc: $bcc\r\n"; }

    if(is_array($添付)){
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"__PHPMAIL__\"\r\n";

        $body  = "--__PHPMAIL__\r\n";
        $body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\r\n\r\n";
        $body .= mb_convert_encoding($本文, "ISO-2022-JP", "UTF-8") . "\r\n";

        foreach($添付 as $name => $value){
            $body .= "--__PHPMAIL__\r\n";
            $body .= "Content-Type: " . MIMEタイプ($name) . "\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename=\"" . mb_encode_mimeheader($name, "ISO-2022-JP") . "\"\r\n\r\n";
            $body .= chunk_split(base64_encode($value)) . "\r\n";
        }
        $body .= "--__PHPMAIL__--\r\n";
    }
    return mail($送信先, $題名, $body, $header);
}


function GET送信($url, array $querymap = null, array $request = null, &$response){
    if($querymap and preg_match("/\?/", $url)){ $url .= "&" . http_build_query($querymap, "", "&"); }
    else if($querymap){ $url .= "?" . http_build_query($querymap, "", "&"); }
    $request = stream_context_create(['http'=>['method'=>'GET', 'header'=>implode("\r\n", (array)$request)]]);
    $contents = @file_get_contents($url, false, $request);
    $response = $http_response_header;
    return $contents;
}


function POST送信($url, array $querymap = null, array $request = null, &$response){
    $request = stream_context_create(['http'=>['method'=>'POST', 'header'=>implode("\r\n",(array)$request), 'content'=>http_build_query((array)$querymap,"","&")]]);
    $contents = @file_get_contents($url, false, $request);
    $response = $http_response_header;
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


function ベースURL($url){
    return (substr_count($url, "/") === 2) ? $url."/" : dirname($url."a")."/";
}


function Windowsなら(){
    return preg_match("/^WIN/i", PHP_OS);
}


function GETなら(){
    return (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') ? true : false;
}


function POSTなら(){
    return (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') ? true : false;
}


function 連想配列なら($array){
    return (is_array($array) and array_values($array) !== $array) ? true : false;
}


function 日付($time = 0, $str = '[年]/[0月]/[0日] [0時]:[0分]'){
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
    if(!$time){ return ""; }
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


function 自動リンク($arg = "", array $attrmap = []){
    if(is_array($arg)){ return array_map(function($str) use($attrmap){ return 自動リンク($str, $attrmap); }, $arg); }
    foreach($attrmap as $name => $value){ $attr .= " $name=\"$value\""; }
    return preg_replace("|(https?://[^[:space:]　\r\n]+)|ui", "<a href=\"$1\"$attr>$1</a>", $arg);
}


function テーブルタグ作成(array $array){
    $firstkey = key($array);
    if(is_array($array[$firstkey])){ $tag = "<tr>\n<th></th>\n<th>" . implode("</th>\n<th>",array_keys($array[$firstkey])) . "</th>\n</tr>\n"; }
    foreach($array as $key1 => $value1){
        $tag .= "<tr>\n<th>$key1</th>\n";
        foreach((array)$value1 as $key2 => $value2){ $tag .= "<td>$value2</td>\n"; }
        $tag .= "</tr>\n";
    }
    return $tag;
}


function ファイル一覧($path = "./", $pattern = "."){
    if(!is_dir($path)){ return false; }
    foreach(array_diff(scandir($path), ['.','..']) as $file){
        if(is_file("$path/$file") and preg_match("#$pattern#i", $file)){ $list[] = realpath("$path/$file"); }
        if(is_dir("$path/$file")) { $list = array_merge($list, ファイル一覧("$path/$file", $pattern)); }
    }
    return (array)$list;
}


function ディレクトリ一覧($path = "./", $pattern = "."){
    if(!is_dir($path)){ return false; }
    foreach(array_diff(scandir($path), ['.','..']) as $file){
        if(is_dir("$path/$file")) {
            if(preg_match("#$pattern#i", $file)){ $list[] = realpath("$path/$file"); }
            $list = array_merge($list, ディレクトリ一覧("$path/$file", $pattern));
        }
    }
    return (array)$list;
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


function zip解凍($zipfile, $where){
    $zip = new ZipArchive();
    if(!$zip->open($zipfile)){ return false; }
    $result = $zip->extractTo($where);
    $zip->close();
    return $result;
}



function キャッシュ保存($name, $data){
    $tempfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . get_current_user() . "-" . $name;
    $result = file_put_contents($tempfile, serialize($data), LOCK_EX);
    return ($result === false) ? false : $name;
}


function キャッシュ取得($name){
    $tempfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . get_current_user() . "-" . $name;
    return (file_exists($tempfile)) ? unserialize(file_get_contents($tempfile)) : false;
}


function JSON保存($file, $data){
    $result = file_put_contents($file, "<?php\n".json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES), LOCK_EX);
    return ($result === false) ? false : $file;
}


function JSON取得($file){
    return json_decode(file_get_contents($file,null,null,5), true);
}


function uuid($hyphen = false) { //uuid v4
    $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0x0fff)|0x4000,mt_rand(0,0x3fff)|0x8000,mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff));
    return ($hyphen) ? $uuid : str_replace("-", "", $uuid);
}


function ランダム英数字($length = 8, $userfriendly = true){
    $str = "ABDEFGHJLMNQRTYabdefghmnrty23456789";
    if(!$userfriendly){ $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; }
    $str = str_repeat($str, floor($length/2));
    return substr(str_shuffle($str), 0, $length);
}


function 連想配列ソート(array &$array){
    array_multisort(array_values($array), SORT_DESC, array_keys($array), SORT_ASC, $array);
}


function テンプレート変換($テンプレート, array $変換関係 = []){
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
    private static $現在のドライバー;
    private static $pdo;
    private $テーブル;
    private $id列名 = "id";
    public static $件数 = 31;

    public function __construct($table, $driver = null, $user = null, $pass = null){
        $this->テーブル($table);
        if($driver){ $this->接続($driver, $user, $pass); }
        else{
            if(!self::$pdo or (self::$現在のドライバー != $_ENV['データベースドライバー'])){
                $this->接続($_ENV['データベースドライバー'], $_ENV['データベースユーザー名'], $_ENV['データベースパスワード']);
            }
        }
        return $this;
    }

    private function 接続($driver, $user = null, $pass = null){
        $setting = $_ENV['データベース付加設定'] ?: [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        ];

        try{ self::$pdo = new PDO($driver, $user, $pass, $setting); }
        catch(Exception $e){ print "接続エラー。データベース::設定(ドライバ,ユーザID,パスワード)を再確認してください"; }
        self::$現在のドライバー = $driver;
        return $this;
    }

    public function 実行($SQL文, array $割当 = null){
        $stmt = self::$pdo -> prepare($SQL文);
        for($i = 1; $i <= count($割当); $i++){
            $type = gettype($割当[$i-1]);
            if($type === "integer" or $type === "boolean"){
                $stmt -> bindValue($i, $割当[$i-1], PDO::PARAM_INT);
            }
            else {
                $stmt -> bindValue($i, $割当[$i-1], PDO::PARAM_STR);
            }
        }
        $stmt -> execute();
        return $stmt;
    }

    public function 取得(array $条件 = null){
        list($追加文, $割当, $行タイプ) = $this->追加SQL文($条件, "where");
        $SQL文 = "select * from {$this->テーブル} $追加文";
        return $this -> 実行($SQL文, $割当) -> fetchAll(...$行タイプ);
    }

    public function 行取得($id, array $条件 = null){
        list($追加文, $割当, $行タイプ) = $this->追加SQL文($条件, "where");
        $SQL文 = "select * from {$this->テーブル} where {$this->id列名} = ?";
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
        $SQL文 = "select {$列} from {$this->テーブル} where {$this->id列名} = ?";
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
        if(preg_match("/sqlite/i", self::$現在のドライバー)){
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

    public function 追加(array $data){
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
        return self::$pdo -> lastInsertId();
    }

    public function 更新($id, array $data){
        foreach($data as $name => $value){
            if(preg_match("/=/", $name)){
                $set文 .= "{$name},";
            }
            else{
                $this->文字列検証($name);
                $set文 .= "{$name}=?,";
                $割当[] = $value;
            }
        }
        $set文 = rtrim($set文, ',');
        $割当[] = (int)$id;

        $SQL文 = "update {$this->テーブル} set {$set文} where {$this->id列名} = ?";
        return $this -> 実行($SQL文, $割当) -> rowCount();
    }

    public function 削除($id){
        $SQL文 = "delete from {$this->テーブル} where {$this->id列名} = ?";
        return $this -> 実行($SQL文, [(int)$id]) -> rowCount();
    }

    public function 作成(array $テーブル定義){
        if(!$テーブル定義){ throw new Exception("テーブル定義が存在しません"); }
        foreach($テーブル定義 as $name => $value){
            $this->文字列検証($name);
            $列情報 .= "$name $value,";
        }
        $列情報 = rtrim($列情報, ',');
        $SQL文 = "create table IF NOT EXISTS {$this->テーブル} ($列情報)";

        if(preg_match('/^sqlite/i', self::$現在のドライバー)){ //SQLite用
            $SQL文  = str_replace('auto_increment', 'autoincrement', $SQL文);
        }
        else { //MySQL用
            $SQL文  = str_replace('autoincrement', 'auto_increment', $SQL文);
            $SQL文 .= " ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
        }
        $this -> 実行($SQL文);
        return $this;
    }

    public function インデックス作成($列){
        $this->文字列検証($列);
        $SQL文  = "create index {$列}インデックス on {$this->テーブル} ($列)";
        $this -> 実行($SQL文);
        return $this;
    }

    public function トランザクション開始(){
        self::$pdo -> beginTransaction();
        return $this;
    }

    public function トランザクション終了(){
        self::$pdo -> commit();
        return $this;
    }

    public function トランザクション失敗(){
        self::$pdo -> rollBack();
        return $this;
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

    public function id($arg = null){
        if($arg){
            $this->文字列検証($arg);
            $this->id列名 = $arg;
            return $this;
        }
        else{
            return $this->id;
        }
    }

    private function 文字列検証($str){
        if(preg_match("/[[:cntrl:][:punct:][:space:]]/", $str)){ throw new Exception("引数に不正な文字列"); }
    }

    private function 追加SQL文(array $条件 = null, $WHEREorAND = "where"){
        $割当  = (array)$条件['割当'];
        if($条件["式"]){ $SQL文 = " $WHEREorAND {$条件['式']} "; }

        if(count($条件["順番"]) === 2){
            $this->文字列検証($条件["順番"][0]);
            $順番列 = ($条件["順番"][0]) ? $条件["順番"][0] : $this->id列名;
            $順番順 = ($条件["順番"][1] == "小さい順") ? "asc" : "desc";
        }
        else{
            $順番列 = $this->id列名;
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
            if($_ENV['データベース付加設定'][PDO::ATTR_DEFAULT_FETCH_MODE] & PDO::FETCH_CLASS){
                $行タイプ = [$_ENV['データベース付加設定'][PDO::ATTR_DEFAULT_FETCH_MODE], "{$this->テーブル}テーブル"];
            }
        }

        return [$SQL文, $割当, $行タイプ];
    }
}


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
    public  static $イベント;
    public  static $js;
    public  static $css;

    public static function 作成($部品名, $引数){
        if($部品名 === null){ throw new Exception('部品名がありません'); }
        if(!self::$初期化済み){ self::初期化実行(); }

        if(self::$キャッシュ[$部品名]["読み込み済み"] === true){
            $html = self::$キャッシュ[$部品名]["html"];
            $css  = self::$キャッシュ[$部品名]["css"];
            $js   = self::$キャッシュ[$部品名]["js"];
        }
        else{
            $path = self::$ディレクトリ . "/{$部品名}.php";
            if(self::$windows){ $path = self::$ディレクトリ . addslashes(mb_convert_encoding("/{$部品名}.php", 'SJIS', 'UTF-8')); }
            require $path;
            self::$キャッシュ[$部品名]["読み込み済み"] = true;
            self::$キャッシュ[$部品名]["html"] = $html;
            self::$キャッシュ[$部品名]["css"]  = $css;
            self::$キャッシュ[$部品名]["js"]   = $js;
        }

        if($css) { self::$css[$部品名] = is_callable($css) ? call_user_func_array($css, $引数) : $css; }
        if($js)  { self::$js[$部品名]  = is_callable($js)  ? call_user_func_array($js,  $引数) : $js; }

        return is_callable($html) ? call_user_func_array($html, $引数) : $html;
    }

    public static function 終了処理(){
        $buf = ob_get_contents();
        ob_end_clean();
        if(self::$js){
            $js     = "\n<script>\n" . implode(self::$js,"\n") . "\n</script>\n";
            $js_pos = strripos($buf, "</body>");
            if($js_pos !== false){
                $buf = substr_replace($buf, $js, $js_pos, 0); //最後に出現する</body>の前にJSを挿入する
            }
            else{
                $buf .= $js;
            }
        }
        if(self::$css){
            $css     = "\n<style>\n " . implode(self::$css,"\n") . "\n</style>\n";
            $css_pos = stripos($buf, "</head>");
            if($css_pos !== false){
                $buf = substr_replace($buf, $css, $css_pos, 0); //最初に出現する</head>の前にCSSを挿入する
            }
            else{
                $buf = $css . $buf;
            }
        }
        if(is_callable(self::$イベント['出力前'])){ call_user_func(self::$イベント['出力前'], $buf); }
        print $buf;
    }

    private static function 初期化実行(){
        self::$初期化済み = true;
        self::$windows = preg_match("/win/i", PHP_OS);
        ob_start();
        register_shutdown_function("部品::終了処理");
    }

    public static function 初期化($dir = null){
        if($dir){ self::$ディレクトリ = $dir; }
        if(!self::$初期化済み){ self::初期化実行(); }
    }
}


function 確認($value, $method = ""){
    return new 検証("確認", $value, $method);
}


function GET検証($key){
    return new 検証("検証", $key, "GET");
}


function POST検証($key){
    return new 検証("検証", $key, "POST");
}


function 検証($value, $method = ""){
    return new 検証("検証", $value, $method);
}


class 検証{
    public $エラー関数;
    public $key;
    public $value;
    public $mode;
    public $method;

    public function __construct($mode, $value, $method = ""){
        $this->エラー関数 = (検証エラー関数) ? 検証エラー関数 : "エラー";
        $this->mode = $mode;
        if($method){
            $this->key   = $value;
            $this->value = ($method == "POST") ? @$_POST[$value] : @$_GET[$value];
        }
        else{
            $this->value = $value;
        }
    }
    
    public function 必須($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}を入力してください"; }
        return ($this->value !== null or $this->value !== "") ? $this->成功() : $this->失敗($comment);
    }

    public function 数($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}には数値を入力してください"; }
        return (is_numeric($this->value) and !preg_match("/^-?0+\d/", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 自然数($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}は1以上にしてください"; }
        return (preg_match("/^[1-9][0-9]*$/", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 自然数と0($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}は0以上にしてください"; }
        return (preg_match("/^(0|[1-9]\d*)$/", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 数字($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}に数字以外の文字が含まれています"; }
        return (preg_match("/^[0-9]+$/", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 英語($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}に英語以外の文字が含まれています"; }
        return (preg_match("/^[A-Za-z]+$/", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 英数字($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}に英数字以外の文字が含まれています"; }
        return (preg_match("/^[A-Za-z0-9]+$/", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function URL($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}にはURLを入力してください"; }
        return (preg_match("|^https?://.{4,}|i", $this->value)) ? $this->成功() : $this->失敗($comment);
    }

    public function 画像データ($comment = ""){
        if(!$comment and $this->key){ $comment = "{$this->key}を取得できませんでした"; }
        return (getimagesizefromstring($this->value)[0] > 0) ? $this->成功() : $this->失敗($comment); //getimagesize()[0]:横サイズ [1]:縦サイズ [2]:GIFは1、JPEGは2、PNGは3
    }

    public function と同じ($value, $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}が{$value}でありません"; }
        return ($this->value == $value) ? $this->成功() : $this->失敗($comment);
    }

    public function 以上($num, $unit = "", $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}以上にしてください"; }
        return (is_numeric($this->value) and ($this->value >= $num)) ? $this->成功() : $this->失敗($comment);
    }

    public function 以下($num, $unit = "", $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}以下にしてください"; }
        return (is_numeric($this->value) and ($this->value <= $num)) ? $this->成功() : $this->失敗($comment);
    }

    public function より大きい($num, $unit = "", $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}より大きくしてください"; }
        return (is_numeric($this->value) and ($this->value > $num)) ? $this->成功() : $this->失敗($comment);
    }

    public function より小さい($num, $unit = "", $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}{$unit}より小さくしてください"; }
        return (is_numeric($this->value) and ($this->value < $num)) ? $this->成功() : $this->失敗($comment);
    }

    public function 文字以上($num, $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}文字以上にしてください"; }
        return (mb_strlen($this->value,"UTF-8") < $num) ? $this->成功() : $this->失敗($comment);
    }

    public function 文字以下($num, $comment = ""){ //非文書化
        if(!$comment and $this->key){ $comment = "{$this->key}は{$num}文字以内にしてください"; }
        return (mb_strlen($this->value,"UTF-8") > $num) ? $this->成功() : $this->失敗($comment);
    }

    private function 全角数字変換($num){
        $num = preg_replace("/^ー/u", "-", $num);
        $num = preg_replace("/．/u", ".", $num);
        $num = mb_convert_kana($num, "n", "utf-8");
        return $num;
    }

    private function 成功(){
        return ($this->mode === "確認") ? true : $this;
    }

    private function 失敗($comment = "エラーが発生しました"){
        if($this->mode === "確認"){ return false; }
        if(is_callable($this->エラー関数)){ call_user_func($this->エラー関数, $comment, $this); }
        return $this;
    }

    function __call($name, $args){
        if     (preg_match("/^([０-９]+)文?字以上$/u", $name, $m)) { return $this->文字以上($this->全角数字変換($m[1]), $args[0]); }
        else if(preg_match("/^([０-９]+)文?字以/u", $name, $m))    { return $this->文字以下($this->全角数字変換($m[1]), $args[0]); }
        else if(preg_match("/^(ー?[０-９．]+)(\w*)以上$/u", $name, $m)) { return $this->以上($this->全角数字変換($m[1]), $m[2], $args[0]); }
        else if(preg_match("/^(ー?[０-９．]+)(\w*)以/u", $name, $m))    { return $this->以下($this->全角数字変換($m[1]), $m[2], $args[0]); }
        else if(preg_match("/^(ー?[０-９．]+)(\w*)より大/u", $name, $m)){ return $this->より大きい($this->全角数字変換($m[1]), $m[2], $args[0]); }
        else if(preg_match("/^(ー?[０-９．]+)(\w*)より小/u", $name, $m)){ return $this->より小きい($this->全角数字変換($m[1]), $m[2], $args[0]); }
        else {
            if(preg_match("/^(ー?[０-９．]+)$/u", $name)){ $name = $this->全角数字変換($name); }
            return $this->と同じ($name, $args[0]);
        }
    }
}
