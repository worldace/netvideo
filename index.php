<?php

include './netvideo/0.1.0/setting.php';
include './netvideo/0.1.0/function.php';
include './netvideo/0.1.0/boot.php';

switch($_GET['action']){
    case ''              : route(['./netvideo/0.1.0/action/index.php']);
    case 'video'         : route(['./netvideo/0.1.0/action/video.php']);
    case 'commentget'    : route(['./netvideo/0.1.0/action/commentget.php']);
    case 'commentpost'   : route(['./netvideo/0.1.0/action/commentpost.php']);
    case 'videopost'     : route(['./netvideo/0.1.0/action/videopost.php']);
    case 'videopostform' : route(['./netvideo/0.1.0/action/videopostform.php']);
    case 'proxy'         : route(['./netvideo/0.1.0/action/proxy.php']);
    default              : route(['./netvideo/0.1.0/action/index.php']);
}