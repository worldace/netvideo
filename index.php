<?php

include './action/lib/function.php';
include './setting.php';
include './boot.php';

switch($_GET['action']){
    case ''              : route('./action/index.php');
    case 'video'         : route('./action/video.php');
    case 'commentget'    : route('./action/commentget.php');
    case 'commentpost'   : route('./action/commentpost.php');
    case 'videopost'     : route('./action/videopost.php');
    case 'videopostform' : route('./action/videopostform.php');
    case 'proxy'         : route('./action/proxy.php');
    default              : route('./action/index.php');

}