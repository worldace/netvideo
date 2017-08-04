<?php

include './netvideo/0.1.0/function.php';
include './netvideo/0.1.0/setting.php';
include './netvideo/0.1.0/boot.php';


switch($_GET['action']){
    case ''                : route('GET', ['./netvideo/0.1.0/action/index.php']);
    case 'video'           : route('GET', ['./netvideo/0.1.0/action/video.php']);
    case 'comment-get'     : route('GET', ['./netvideo/0.1.0/action/comment-get.php']);
    case 'comment-post'    : route('POST',['./netvideo/0.1.0/action/comment-post.php']);
    case 'video-post'      : route('POST',['./netvideo/0.1.0/action/video-post.php']);
    case 'video-post-form' : route('GET', ['./netvideo/0.1.0/action/video-post-form.php']);
    case 'proxy'           : route('GET', ['./netvideo/0.1.0/action/proxy.php']);
    default                : route('ANY', ['./netvideo/0.1.0/action/index.php']);
}