<?php




//部品
ob_start();
register_shutdown_function(function(){
    global $設定;
    if($設定['追加css'] or $設定['追加js']){
        $css = @implode($設定['追加css'], "\n");
        $js  = @implode($設定['追加js'], "\n");
        $buf = ob_get_contents();
        ob_end_clean();
        
        print preg_replace("|</head>|i", "\n<style>\n$css</style>\n<script>\n$js</script>\n</head>", $buf, 1);
    }
    else{
        ob_end_flush();
    }
});
