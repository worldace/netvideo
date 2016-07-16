<?php

include('./setting.php');
include('./action/lib/function.php');
include('./action/lib/boot.php');

switch($_GET['action']){
    case 'index'         : include('./action/index.php');         break;
    case 'video'         : include('./action/video.php');         break;
    case 'commentget'    : include('./action/commentget.php');    break;
    case 'commentpost'   : include('./action/commentpost.php');   break;
    case 'videopost'     : include('./action/videopost.php');     break;
    case 'videopostform' : include('./action/videopostform.php'); break;
    case 'proxy'         : include('./action/proxy.php');         break;
    default              : include('./action/index.php');

}