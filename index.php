<?php


switch($_GET['action']){
    case 'videopostform' : include('./action/videopostform.php'); break;
    case 'proxy'         : include('./action/proxy.php');         break;
    default              : include('./action/index.php');

}