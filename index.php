<?php

define(設定, include './netvideo/0.1.0/setting.php');
include './netvideo/0.1.0/function.php';
include './netvideo/0.1.0/boot.php';

switch($_GET['action']){
    case ''                : route(['./netvideo/0.1.0/action/index.php']);
    case 'video'           : route(['./netvideo/0.1.0/action/video.php']);
    case 'comment-get'     : route(['./netvideo/0.1.0/action/comment-get.php']);
    case 'comment-post'    : route(['./netvideo/0.1.0/action/comment-post.php']);
    case 'video-post'      : route(['./netvideo/0.1.0/action/video-post.php']);
    case 'video-post-form' : route(['./netvideo/0.1.0/action/video-post-form.php']);
    case 'proxy'           : route(['./netvideo/0.1.0/action/proxy.php']);
    default                : route(['./netvideo/0.1.0/action/index.php']);
}