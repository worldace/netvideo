<?php




//部品
ob_start();
register_shutdown_function(function(){
    global $設定;
    if($設定['追加js'] or $設定['追加css']){
        $buf = ob_get_contents();
        ob_end_clean();
        if($設定['追加js']){
            $js  = implode($設定['追加js'], "\n");
            $buf = substr_replace($buf, "\n<script>\n$js</script>\n", strrpos($buf,"</body>"), 0); //最後に出現する</body>の前にJSを挿入する
        }
        if($設定['追加css']){
            $css = implode($設定['追加css'], "\n");
            $buf = substr_replace($buf, "\n<style>\n$css</style>\n", strpos($buf,"</head>"), 0);  //最初に出現する</head>の前にCSSを挿入する
        }
        print $buf;
    }
    else{
        ob_end_flush();
    }
});
