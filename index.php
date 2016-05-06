<?php


switch($_GET['action']){
    case 'index'         : include('./action/index.php');         break;
    case 'video'         : include('./action/video.php');         break;
    case 'commentpost'   : include('./action/commentpost.php');   break;
    case 'videopostform' : include('./action/videopostform.php'); break;
    case 'proxy'         : include('./action/proxy.php');         break;
    default              : include('./action/index.php');

}