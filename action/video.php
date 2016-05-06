<?php

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title></title>
  <style>
.comment{
    position: absolute;
    font-size: 24px;
    line-height: 1;
    z-index: 2;
    color: #fff;
    text-shadow: -1px -1px #000, 1px -1px #000,	-1px 1px #000, 1px 1px #000;
    animation-name: lane0;
    animation-timing-function: linear;
    animation-duration: 8s;

}
#video-screen{
    background-color: #000;
    width: 640px;
    height: 360px;
    margin: 50px;
    overflow: hidden;
    white-space : nowrap;
    cursor: default;
    -ms-user-select: none;
    -moz-user-select: none;
    -webkit-user-select: none;
    user-select: none;
    position: relative;
}

@keyframes lane0 {
from{ transform: translate(640px, 0); }
to{ transform: translate(-1280px, 0); }
}
@keyframes lane1 {
from{ transform: translate(640px, 25px); }
to{ transform: translate(-1280px, 25px); }
}
@keyframes lane2 {
from{ transform: translate(640px, 50px); }
to{ transform: translate(-1280px, 50px); }
}
@keyframes lane3 {
from{ transform: translate(640px, 75px); }
to{ transform: translate(-1280px, 75px); }
}
@keyframes lane4 {
from{ transform: translate(640px, 100px); }
to{ transform: translate(-1280px, 100px); }
}
@keyframes lane5 {
from{ transform: translate(640px, 125px); }
to{ transform: translate(-1280px, 125px); }
}
@keyframes lane6 {
from{ transform: translate(640px, 150px); }
to{ transform: translate(-1280px, 150px); }
}
@keyframes lane7 {
from{ transform: translate(640px, 175px); }
to{ transform: translate(-1280px, 175px); }
}
@keyframes lane8 {
from{ transform: translate(640px, 200px); }
to{ transform: translate(-1280px, 200px); }
}
@keyframes lane9 {
from{ transform: translate(640px, 225px); }
to{ transform: translate(-1280px, 225px); }
}
@keyframes lane10 {
from{ transform: translate(640px, 250px); }
to{ transform: translate(-1280px, 250px); }
}
@keyframes lane11 {
from{ transform: translate(640px, 275px); }
to{ transform: translate(-1280px, 275px); }
}
</style>
</head>
<body>

<div id="video-screen">
<video id="video" src="http://127.0.0.1/netvideo/doc/1.mp4" width="640" height="360" autoplay loop controls></video>
</div>
<form id="comment-form">
<input type="text" id="comment-input" autofocus autocomplete="off"><input type="submit" id="comment-button" value="コメント">
</form>


<div id="info"></div>



<script>
var $v = {};

document.addEventListener('DOMContentLoaded', function(){

$v.video  = document.getElementById("video");
$v.screen = document.getElementById("video-screen");
$v.screen.pos = $v.screen.getBoundingClientRect();

$v.comment = {}
$v.comment.form   = document.getElementById("comment-form");
$v.comment.input  = document.getElementById("comment-input");
$v.comment.button = document.getElementById("comment-button");

$v.comment.list = [
    [
        ["000000000000000000000000000000000"],
        ["00000000000000"],
        ["000000000000000000000"],
        ["0000000000000"],
        ["000000000000000000000000000"],
        ["0", 0.21],
    ],
    [
        ["1111111111111111"],
    ],
    [
        ["22222222"],
    ],
    [
        ["33333333"],
    ],
    [
    ],
    [
        ["55555555555"],
        ["55555555555"],
        ["55555555555"],
        ["55555555555"],
    ],
    [
        ["66666666666"],
    ],
    [
        ["あああああああああああ"],
        ["あああああ"],
        ["あああああああああああ"],
        ["あああああ"],
        ["あああああああああ"],
    ],
    [
        ["いいいいいいいいいいいいいい"],
        ["いいいいいいいいいいいいいい"],
        ["いいいいいいいいいいいいいい"],
        ["いいいいいいいいいいいいいい"],
    ],
    [
        ["うううううううううう"],
        ["うううううううううう"],
        ["うううううううううう"],
    ],
    [
        ["ええええええええええええええええ"],
        ["ええええええええええええええええ"],
    ],
    [
        ["おおおおおおおおおおおおおおおおおおおおおおおおおお"],
    ],
    [
        ["あああああああああああ"],
        ["ああああああ"],
        ["あああああああああああ"],
        ["あああ"],
        ["あああああああああああ"],
    ],
    [
        ["いいいいいいいいいいいいいい"],
        ["いいいいいいいいいいいいいい"],
        ["いいいいいいいいいいいいいい"],
        ["いいいいいいいいいいいいいい"],
    ],
    [
        ["うううううううううう"],
        ["うううううううううう"],
        ["うううううううううう"],
    ],
    [
        ["ええええええええええええええええ"],
        ["ええええええええええええええええ"],
    ],
    [
        ["おおおおおおおおおおおおおおおおおおおおおおおおおお"],
    ],
];

$v.comment.release = function(comments, lane){
    for(var i = 0; i < comments.length; i++){
        for(var j = 0; j < lane.length; j++){
            if(lane[j] === false){ continue; }

            var comment = document.createElement("span");
            comment.textContent = comments[i][0];
            comment.setAttribute("class", "comment");
            comment.setAttribute("data-lane", j);
            comment.style.animationName = "lane" + j;
            $v.screen.insertBefore(comment, $v.screen.firstChild);
            lane[j] = false;
            break;
        }
    }
};

$v.comment.out = function(){
    var comments = $v.screen.getElementsByClassName("comment");

    for(var i = comments.length-1; i >= 0; i--){
        var comment_pos = comments[i].getBoundingClientRect();

        if(comment_pos.right < $v.screen.pos.left){
            $v.screen.removeChild(comments[i]);
        }
    }
};

$v.comment.clear = function(){
    var comments = $v.screen.getElementsByClassName("comment");
    for(var i = comments.length-1; i >= 0; i--){
        $v.screen.removeChild(comments[i]);
    }
};

$v.comment.pause = function(){
    var comments = document.getElementsByClassName("comment");
    for(var i = 0; i < comments.length; i++){
        comments[i].style.animationPlayState = "paused";
    }
};

$v.comment.run = function(){
    var comments = $v.screen.getElementsByClassName("comment");
    for(var i = 0; i < comments.length; i++){
        comments[i].style.animationPlayState = "running";
    }
};

$v.comment.lane = function(){
    var comments = $v.screen.getElementsByClassName("comment");
    var lane = [true,true,true,true,true,true,true,true,true,true,true,true];

    for(var i = 0; i < comments.length; i++){
        var comment_pos = comments[i].getBoundingClientRect();

        if(comment_pos.right > $v.screen.pos.right){
            lane[comments[i].getAttribute("data-lane")] = false;
        }
    }
    return lane;
};

$v.post = function(url, param){
    var body = "";
    for(var key in param){
        if(!param.hasOwnProperty(key)){ continue; }
        body += encodeURIComponent(key) + "=" + encodeURIComponent(param[key]) + "&";
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhr.send(body);
};



$v.video.addEventListener('timeupdate', function(){
    var sec_now = Math.floor($v.video.currentTime);

    $v.comment.out();

    //コメント放出
    if(sec_now !== $v.video.beforeTime && sec_now in $v.comment.list && $v.video.paused === false){
        $v.comment.release($v.comment.list[sec_now], $v.comment.lane());
        $v.video.beforeTime = sec_now;
    }

    document.getElementById("info").textContent = $v.video.currentTime;
});

$v.video.addEventListener('seeking', function(){
    $v.comment.clear();
});

$v.video.addEventListener('ended', function(){
    $v.comment.clear();
});

$v.video.addEventListener('pause', function(){
    $v.comment.pause();
});

$v.video.addEventListener('play', function(){
    $v.comment.run();
});

$v.video.addEventListener('click', function(event){
    event.preventDefault();
    $v.video.paused ? $v.video.play() : $v.video.pause();
});

$v.video.addEventListener('dblclick', function(event){
    event.preventDefault();//ieで最大化してしまう
});

$v.comment.form.addEventListener('submit', function(event){
    event.preventDefault();

    var text = $v.comment.input.value.trim();
    if(text == ""){ return; }
    //文字数制限
    //IDチェック
    //動画時間チェック

    $v.comment.input.value = "";
    $v.comment.input.focus();
    $v.post('?action=commentpost', {id:1, text:text, time:"123456789"});
});

});
</script>



</body>
</html>