<?php
//======================================================
// ■プレーヤー部品
// 
// 呼び出し元: ../action/php/function.php 部品()
//======================================================


function parts_player($video){

    return <<<━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
<div id="video-player" class="video-player"
><div id="video-screen" class="video-screen"
><video id="video" class="video" src="{$video['動画URL']}" loop autoplay></video
></div
><div id="video-controller" class="video-controller"
><div class="controller-wrap"
><img id="controller-play-toggle" class="controller-img" width="20" height="20" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAAAuklEQVQ4y2NsaGhgIBcwka0TRTN3cGF9fWEwz3niNbOgcvl0ijfoMDzb4ZV10tyQPGdLeWzbUF8Yyn3yPBmaIY7QKtm2oT7VioIAk3Ktr68vDBYgSzM0JPKxhiULMZqhJmCEJanxLOWBFBDE2wwFT3cGzDlhSLLmT1cm9q/9wMCAiH/iND/b3Tj7GKYwIc2frvVEVH3FkdrwaP50pTdu7RdDBtzpFLtmWKgQSN7omjFDBQ9gHASFARkAAAB5PwlcqrFmAAAAAElFTkSuQmCC"
><span id="controller-time-current" class="controller-time controller-time-current">00:00</span
><div id="controller-time-seek" class="controller-seek controller-time-seek"><div id="controller-time-seekbar" class="controller-seekbar controller-time-seekbar"><span id="controller-time-seeker" class="controller-seeker"></span></div></div
><span id="controller-time-total" class="controller-time controller-time-total">00:00</span
><img id="controller-volume-toggle" class="controller-img" width="20" height="20" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAABuElEQVQ4y72UsU7jQBCGfxBdfCuL0kE8gFkKV47TWQhhpUKyIh1NGggFFMbCfZLmCuQoUEBjaK6KQJauihxFiA57KwosHuBEtkSWLw9whXM+FDm6QHFT7Yz22393/tEutdttfDaWP01+AC6ZdZU9LQqHQt2sTHeHTDTWZWPwo2XXS++OWJ5H3p7KdPM4SzU18XsdozYaE9npf8v5lXkkyVMmun2LkjTuNvZruBlsH57pPT8pUJ4hc9mAE3r63d263B9yQqfvXwEQsurNYLtceH8mun1r/edFz+ug2TL2jnFwF2tW9UxnfvKPbmtq4oScUOug8nT+zCFtnODhdQKyKi9klRZ5AceXso44SSGsbSF+Sxf1ORTqVQm/xg+gIsHk9R50lSwEh0x0mzLho+tIOdmUkCYx9DUB6dsLgKWZ2Q4rzWBHmiZ81PEes2XJtB2KuNtw6FWwI8Tdhj9RZpW1yDOGfEbctFsOJeNhw7nXXU0CZ/5EKR4SLfIM/NXPfPaBkOlu36KEB18voc6Bc/62/JJXSqYdtAjAg9oRU5WsWAxnfO9922wyHu5eRwr+kAUN+1D8l8+gMH4D4qKxk5B4zcwAAAAASUVORK5CYII="
><div id="controller-volume-seek" class="controller-seek controller-volume-seek"><div id="controller-volume-seekbar" class="controller-seekbar controller-volume-seekbar"><span id="controller-volume-seeker" class="controller-seeker"></span></div></div
><img id="controller-comment-toggle" class="controller-img" width="20" height="20" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAABgUlEQVQ4y2NsaGhgIBcwka2TgYGBBVPo+EmBnhX5OnzIYp+u9Mat/WKIT/Nxi9Qd7lLY7ODTKd6gw8DA8Gx34+xj6M4+flIguLAeh04kIOVaX18YzHMeoRmbO/EAPp3iRRD9TAwMDAz5oUTrhOmPzIZqLtBFcu2z3R5eu59CQ2miR++1TwwMDAwMT3cGeOx8huR+bfOT55nZOQNCoizE2KFad4Q3MJr/mfpTLUbs8eT0ueJi7y6LGFn9OdS/6Y/sk3M/1BxUeCEq//94sZ7J0vzDky8IA626HI+fFOixlGLg04rMZzh+0ipSh49ByjyY5/xxnlArhBO/PNnLwNDQ0NB9+eN/UsHHq92e/kwMDAx7yuZf+URSgH26Mrvqq7khEwMDg6X5h5IIaCARA57uhKY2ZgcHBwYGBlmZx+cOHkQKD1zg2Q6v2M0M0HSKSJ7HTwoEF+LRBUuY5ogUjpS2kZLK050Bc04YMhACUM3cwYU7IFqhNhDWycDAwDhghQFFmgFoMLfsYBAAkAAAAABJRU5ErkJggg=="
><img id="controller-screen-toggle" class="controller-img" width="20" height="20" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAAA0klEQVQ4y2NsaGhgIBcwka2TgYGBhaCK4zyhq4q1+JCFnu1unH2MKJstv6yedeUT+c7+urZ/xzNyNZun1ntIkaUZrvPTlYk9qO5nIl5n/9oPX9f291z59PTyVMKaj5+0kuH5BNcJ9/+cE4YQNiMliQR7PJun1ntIfbrSG7f2iyEezVBnH+cR4D55HtWffDqR2YRtPn5SoGdFvk5x6A6vLIY5G5BDiHhnS3ls2wBhEaMTe2gTqRO7Zj6dUHOY/0nWzMAg5bFtGjH6KYpnigoDijQDAEFsWdMJ3UD+AAAAAElFTkSuQmCC"
></div
><form id="comment-form" class="comment-form"
><input id="comment-form-input" class="comment-form-input" type="text" name="comment" value="" autocomplete="off" spellcheck="false" disabled
><input id="comment-form-submit" class="comment-form-submit" type="submit" value="コメントする" disabled
><input id="comment-form-id" type="hidden" name="id" value="{$video['動画ID']}"
><input id="comment-form-path" type="hidden" name="path" value="{$video['投稿時間']}"
></form
></div
></div>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━;
}



$js=<<<'━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━'

var $v = {};

document.addEventListener('DOMContentLoaded', function(){

$v.video  = document.getElementById("video");
$v.screen = document.getElementById("video-screen");
$v.controller = document.getElementById("video-controller");
$v.comment = {};

$v.screen.pos = $v.screen.getBoundingClientRect();
$v.screen.posDefault = $v.screen.pos.concat();

$v.controller.parts = {
    play:       "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAAAuklEQVQ4y2NsaGhgIBcwka0TRTN3cGF9fWEwz3niNbOgcvl0ijfoMDzb4ZV10tyQPGdLeWzbUF8Yyn3yPBmaIY7QKtm2oT7VioIAk3Ktr68vDBYgSzM0JPKxhiULMZqhJmCEJanxLOWBFBDE2wwFT3cGzDlhSLLmT1cm9q/9wMCAiH/iND/b3Tj7GKYwIc2frvVEVH3FkdrwaP50pTdu7RdDBtzpFLtmWKgQSN7omjFDBQ9gHASFARkAAAB5PwlcqrFmAAAAAElFTkSuQmCC",
    pause:      "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAAAPElEQVQ4y2NsaGhgIBcwka2TgYGBhYGB4fhJq7nbXKVRJT5dmdi/9gMeKUptHtU8qnlUMxbAOGCFAUWaAUd8Eyn9ZkIkAAAAAElFTkSuQmCC",
    volume:     "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAABuElEQVQ4y72UsU7jQBCGfxBdfCuL0kE8gFkKV47TWQhhpUKyIh1NGggFFMbCfZLmCuQoUEBjaK6KQJauihxFiA57KwosHuBEtkSWLw9whXM+FDm6QHFT7Yz22393/tEutdttfDaWP01+AC6ZdZU9LQqHQt2sTHeHTDTWZWPwo2XXS++OWJ5H3p7KdPM4SzU18XsdozYaE9npf8v5lXkkyVMmun2LkjTuNvZruBlsH57pPT8pUJ4hc9mAE3r63d263B9yQqfvXwEQsurNYLtceH8mun1r/edFz+ug2TL2jnFwF2tW9UxnfvKPbmtq4oScUOug8nT+zCFtnODhdQKyKi9klRZ5AceXso44SSGsbSF+Sxf1ORTqVQm/xg+gIsHk9R50lSwEh0x0mzLho+tIOdmUkCYx9DUB6dsLgKWZ2Q4rzWBHmiZ81PEes2XJtB2KuNtw6FWwI8Tdhj9RZpW1yDOGfEbctFsOJeNhw7nXXU0CZ/5EKR4SLfIM/NXPfPaBkOlu36KEB18voc6Bc/62/JJXSqYdtAjAg9oRU5WsWAxnfO9922wyHu5eRwr+kAUN+1D8l8+gMH4D4qKxk5B4zcwAAAAASUVORK5CYII=",
    mute:       "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAAAeklEQVQ4y2NsaGhgIBcwka2TZpqP84QGW5wnR/NxntBVxVo6utkka4bo5CPD2cTrZGBgYGFgYDh+0mruNldpKgbYMNbMwsDAYGl+bE7jMQj/uEXqDncpMm22PDHbY+cz8p1NvH7sfobo//TuGmE/49LfT57N5PuZTpoBNCsk2o1mrpoAAAAASUVORK5CYII=",
    commenton:  "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAABgUlEQVQ4y2NsaGhgIBcwka2TgYGBBVPo+EmBnhX5OnzIYp+u9Mat/WKIT/Nxi9Qd7lLY7ODTKd6gw8DA8Gx34+xj6M4+flIguLAeh04kIOVaX18YzHMeoRmbO/EAPp3iRRD9TAwMDAz5oUTrhOmPzIZqLtBFcu2z3R5eu59CQ2miR++1TwwMDAwMT3cGeOx8huR+bfOT55nZOQNCoizE2KFad4Q3MJr/mfpTLUbs8eT0ueJi7y6LGFn9OdS/6Y/sk3M/1BxUeCEq//94sZ7J0vzDky8IA626HI+fFOixlGLg04rMZzh+0ipSh49ByjyY5/xxnlArhBO/PNnLwNDQ0NB9+eN/UsHHq92e/kwMDAx7yuZf+URSgH26Mrvqq7khEwMDg6X5h5IIaCARA57uhKY2ZgcHBwYGBlmZx+cOHkQKD1zg2Q6v2M0M0HSKSJ7HTwoEF+LRBUuY5ogUjpS2kZLK050Bc04YMhACUM3cwYU7IFqhNhDWycDAwDhghQFFmgFoMLfsYBAAkAAAAABJRU5ErkJggg==",
    commentoff: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAABHElEQVQ4y2NsaGhgIBcwka2TgYGBBVPo+EmBnhX5OnzIYp+u9Mat/WKIT/Nxi9Qd7lLY7ODTKd6gw8DA8Gx34+xj6M4+flIguLAeh04kIOVaX18YzHMeoRmbO/EAPp3iRRD9TAwMDAz5oUTrhOmPzIZqLtAl5Fos7tc2P3me6fhJARkekvUyMPDIODMwWZp/ePKFDM1fnuxlYGJgYLjy7hPJej99uALx856y+VdI0/7pyuyqr+aGTAwMDJbmH0oidj8lWuvTndDUxuzg4MDAwCAr8/jcwYM/1BxUePFrfLbDK3YzAzSdIpLn8ZMCwYV4dMESpjkihSOlbaSk8nRnwJwThgyEAFQzd3DhDohWqA2EdTIwMDAOWGFAkWYA9HtacJiFfLAAAAAASUVORK5CYII=",
    fullscreen: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAAA0klEQVQ4y2NsaGhgIBcwka2TgYGBhaCK4zyhq4q1+JCFnu1unH2MKJstv6yedeUT+c7+urZ/xzNyNZun1ntIkaUZrvPTlYk9qO5nIl5n/9oPX9f291z59PTyVMKaj5+0kuH5BNcJ9/+cE4YQNiMliQR7PJun1ntIfbrSG7f2iyEezVBnH+cR4D55HtWffDqR2YRtPn5SoGdFvk5x6A6vLIY5G5BDiHhnS3ls2wBhEaMTe2gTqRO7Zj6dUHOY/0nWzMAg5bFtGjH6KYpnigoDijQDAEFsWdMJ3UD+AAAAAElFTkSuQmCC"
};
$v.controller.timeSeekbar   = document.getElementById("controller-time-seekbar");
$v.controller.timeSeeker    = document.getElementById("controller-time-seeker");
$v.controller.volumeSeekbar = document.getElementById("controller-volume-seekbar");
$v.controller.volumeSeeker  = document.getElementById("controller-volume-seeker");
$v.controller.timeCurrent   = document.getElementById("controller-time-current");
$v.controller.timeTotal     = document.getElementById("controller-time-total");
$v.comment.list = [];
$v.comment.on = true;



$v.get = function(url, callback){
    var xhr = new XMLHttpRequest();

    xhr.open("GET", url);
    xhr.addEventListener("load", function(){
        if(xhr.status == 200) { callback(xhr); }
    });
    xhr.timeout = 30000;
    xhr.send();
};


$v.post = function(url, param){
    var body = "";

    if(param instanceof FormData){
        body = param;
    }
    else{
        for(var key in param){
            if(!param.hasOwnProperty(key)){ continue; }
            body += encodeURIComponent(key) + "=" + encodeURIComponent(param[key]) + "&";
        }
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    xhr.timeout = 20000;
    xhr.send(body);
};


$v.getObjectPosition = function(screenW, screenH, objectW, objectH){
    var result  = {};
    var screenR = screenW / screenH;
    var objectR = objectW / objectH;
    var scale;

    if(screenR > 1){
        scale = (objectR < screenR) ? screenH/objectH : screenW/objectW;
    }
    else{
        scale = (objectR > screenR) ? screenW/objectW : screenH/objectH;
    }
    result.w = Math.floor(objectW * scale);
    result.h = Math.floor(objectH * scale);
    result.x = Math.floor((screenW / 2) - (result.w / 2));
    result.y = Math.floor((screenH / 2) - (result.h / 2));

    return result;
};


$v.video.setPosition = function(screenW, screenH, objectW, objectH){
    var pos = $v.getObjectPosition(screenW, screenH, objectW, objectH);

    $v.video.setAttribute("width",  pos.w);
    $v.video.setAttribute("height", pos.h);
    $v.video.style.left = pos.x + "px";
    $v.video.style.top  = pos.y + "px";
};


$v.comment.release = function(comments, lane){
    for(var i = 0; i < comments.length; i++){
        for(var j = 0; j < lane.length; j++){
            if(lane[j] === false){ continue; }

            var comment = document.createElement("span");
            comment.textContent = comments[i][0];
            comment.classList.add('comment');
            comment.setAttribute("data-lane", j);
            if(!$v.screen.isFullScreen){
                comment.classList.add('comment-normal-lane');
                comment.style.animationName = "lane" + j;
            }
            else{
                comment.classList.add('comment-full-lane');
                comment.style.animationName = "fulllane" + j;
            }
            $v.screen.insertBefore(comment, $v.screen.firstChild);
            lane[j] = false;
            break;
        }
    }
};

$v.comment.gc = function(){
    var comments = $v.screen.querySelectorAll(".comment");

    for(var i = comments.length-1; i >= 0; i--){
        var comment_pos = comments[i].getBoundingClientRect();

        if(comment_pos.right < $v.screen.pos.left){
            $v.screen.removeChild(comments[i]);
        }
    }
};

$v.comment.clear = function(){
    var comments = $v.screen.querySelectorAll(".comment");
    for(var i = comments.length-1; i >= 0; i--){
        $v.screen.removeChild(comments[i]);
    }
};

$v.comment.pause = function(){
    var comments = $v.screen.querySelectorAll(".comment");
    for(var i = 0; i < comments.length; i++){
        comments[i].style.animationPlayState = "paused";
    }
};

$v.comment.run = function(){
    var comments = $v.screen.querySelectorAll(".comment");
    for(var i = 0; i < comments.length; i++){
        comments[i].style.animationPlayState = "running";
    }
};

$v.comment.laneBuild = function(){
    
    /* 通常スクリーン用レーンCSS 640*360 12レーン、各25px */
    for(var i = 0; i < 12; i++){
        var css = "";
        css += "@keyframes lane" + i + "{";
        css += "from{transform:translate(640px," + i*25 + "px);}";
        css += "to{transform:translate(-3200px," + i*25 + "px);}}";
        document.styleSheets[0].insertRule(css, 0);
    }
    /* フルスクリーン用レーンCSS screen.width*screen.height 12レーン 各?px  */
    var height = Math.floor(screen.height * 0.8 / 12); //←ここが決め打ちすぎる。要熟考
    for(var i = 0; i < 12; i++){
        css = "";
        css += "@keyframes fulllane" + i + "{";
        css += "from{transform:translate(" + screen.width + "px," + i*height + "px);}";
        css += "to{transform:translate(-" + screen.width*5 + "px," + i*height + "px);}}";
        document.styleSheets[0].insertRule(css, 0);
    }
    
    css  = ".comment-full-lane{font-size:" + height + "px;}";
    document.styleSheets[0].insertRule(css, 0);
};

$v.comment.laneCheck = function(){
    var comments = $v.screen.querySelectorAll(".comment");
    var lane = [true,true,true,true,true,true,true,true,true,true,true,true];

    for(var i = 0; i < comments.length; i++){
        var comment_pos = comments[i].getBoundingClientRect();

        if(comment_pos.right > $v.screen.pos.right){
            lane[comments[i].getAttribute("data-lane")] = false;
        }
    }
    return lane;
};


$v.comment.get = function(){
    var id   = document.getElementById("comment-form-id").value;
    var path = document.getElementById("comment-form-path").value;
    var totaltime = Math.floor($v.video.duration);

    //動画時間＋1の箱を作成する [[], [], [], ...]
    $v.comment.list = []; 
    for(var i = 0; i < totaltime+1; i++){
        $v.comment.list.push([]);
    }

    //動画時間によって取得件数(num)を変化させる(適当)
    var num = totaltime * 6;

    var url = "?action=commentget" + "&id=" + id + "&path=" + path + "&num=" + num + "&nocache=" + Date.now();
    $v.get(url, function(xhr){
        //JSON変換
        try{ var comments = JSON.parse(xhr.responseText); } catch(e){}
        if(!comments){ return; }

        //箱にコメントを詰める
        for(var i = 0; i < comments.length; i++){
            var index = Math.floor(comments[i][1]);
            if(index >= 0 && index <= totaltime){
                $v.comment.list[index].push(comments[i]);
            }
        }
    });
};

$v.comment.post = function(){
    var input = document.getElementById("comment-form-input");
    var text  = input.value.trim();
    var time  = $v.video.currentTime;
    if(text == ""){ return; }
    if(text.length > 64){ return; } //maxlength属性はどうしようか
    
    var formdata = new FormData(document.getElementById("comment-form"));
    formdata.append("time", time);
    $v.post('?action=commentpost', formdata);

    if(Math.floor(time+1) in $v.comment.list){
        $v.comment.list[Math.floor(time+1)].unshift([text, time+1, Math.floor(Date.now()/1000)]);
    }

    input.value = "";
    input.focus();
};


$v.controller.setSeeker = function(seekbar, seeker, percent){
    seekbar.pos = seekbar.getBoundingClientRect();
    seeker.pos  = seeker.getBoundingClientRect();
    var seekbarWidth = seekbar.pos.width - seeker.pos.width;

    //percentが割合の時(0-1) or percentがクリックされた位置の時
    var pos = (percent <= 1) ? seekbarWidth*percent : percent-seekbar.pos.left;

    if(pos < 0){ pos = 0; }
    if(pos > seekbarWidth){ pos = seekbarWidth; }

    seeker.style.left = pos + "px";
    return pos/seekbarWidth;
};


$v.controller.setBuffer = function(){
    var seekbarWidth = $v.controller.timeSeekbar.getBoundingClientRect().width;
    var buffer = $v.video.buffered;

    if(buffer.length == 1){
        var startPos = buffer.start(0) / $v.video.duration * seekbarWidth;
        var endPos   = buffer.end(0)  / $v.video.duration * seekbarWidth;
        
        $v.controller.timeSeekbar.style.backgroundPosition = startPos + "px";
        $v.controller.timeSeekbar.style.backgroundSize     = endPos + "px";
    }
    else{ //複数バッファには非対応
        $v.controller.timeSeekbar.style.backgroundPosition = 0;
        $v.controller.timeSeekbar.style.backgroundSize     = 0;
    }
};


$v.controller.setTime = function(time, element){
    var min = Math.floor(time / 60);
    var sec = Math.floor(time - min * 60);

    sec = ('0' + sec).slice(-2); //ゼロパディング(2桁)
    if(min < 100){ min = ('0' + min).slice(-2); }

    element.textContent = min + ":" + sec;
};


$v.screen.fullscreenEvent = function(){
    var element = document.msFullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.fullScreenElement;
    if(element){
        if(element.id != "video-screen"){ return; }
        $v.screen.isFullScreen = true;
        $v.video.setPosition(screen.width, screen.height, $v.video.videoWidth, $v.video.videoHeight);
        document.getElementById("video-screen").appendChild(document.getElementById("video-controller"));
        
        var controller = $v.controller.getBoundingClientRect();
        $v.controller.style.top  = screen.height - controller.height + "px";
        $v.controller.style.left = (screen.width/2) - (controller.width/2)+ "px";
    }
    else{
        $v.screen.isFullScreen = false;
        $v.video.setPosition($v.screen.pos.width, $v.screen.pos.height, $v.video.videoWidth, $v.video.videoHeight);
        document.getElementById("video-player").appendChild(document.getElementById("video-controller"));

        $v.controller.style.top  = 0;
        $v.controller.style.left = 0;
    }
    $v.comment.clear();
};




$v.video.addEventListener('loadedmetadata', function(){
    if(!$v.isNotPosted){
        $v.comment.get();//コメントゲット(ここではなくもっと早く起動すべき)
        $v.comment.laneBuild();
        document.getElementById("comment-form-input").disabled  = false;
        document.getElementById("comment-form-submit").disabled = false;
    }
    
    $v.controller.setBuffer();
    $v.controller.setSeeker($v.controller.volumeSeekbar, $v.controller.volumeSeeker, $v.video.volume);
    $v.controller.setTime($v.video.duration, $v.controller.timeTotal);

    //動画の位置セット
    $v.video.setPosition($v.screen.pos.width, $v.screen.pos.height, $v.video.videoWidth, $v.video.videoHeight);
});


$v.video.addEventListener('timeupdate', function(){
    var sec_now = Math.floor($v.video.currentTime);
    if(sec_now !== $v.video.beforeTime){
        $v.controller.setTime(sec_now, $v.controller.timeCurrent);
        $v.controller.setSeeker($v.controller.timeSeekbar, $v.controller.timeSeeker, sec_now/$v.video.duration);
        $v.comment.gc();
        //コメント放出
        if(sec_now in $v.comment.list && $v.video.paused === false && $v.comment.on === true){
            $v.comment.release($v.comment.list[sec_now], $v.comment.laneCheck());
        }
        
        $v.video.beforeTime = sec_now;
    }
});

$v.video.addEventListener('seeking', function(){
    $v.comment.clear();
});

$v.video.addEventListener('ended', function(){
    $v.comment.clear();
});

$v.video.addEventListener('pause', function(){
    $v.comment.pause();
    document.getElementById("controller-play-toggle").setAttribute("src", $v.controller.parts.play);
});

$v.video.addEventListener('play', function(){
    $v.comment.run();
    document.getElementById("controller-play-toggle").setAttribute("src", $v.controller.parts.pause);
});

$v.video.addEventListener('volumechange', function(){
    if(!$v.video.volume || $v.video.muted){
        document.getElementById("controller-volume-toggle").setAttribute("src", $v.controller.parts.mute);
        $v.controller.setSeeker($v.controller.volumeSeekbar, $v.controller.volumeSeeker, 0);
    }
    else{
        document.getElementById("controller-volume-toggle").setAttribute("src", $v.controller.parts.volume);
        $v.controller.setSeeker($v.controller.volumeSeekbar, $v.controller.volumeSeeker, $v.video.volume);
    }
});

$v.video.addEventListener('progress', function(){
    $v.controller.setBuffer();
});

$v.video.addEventListener('click', function(event){
    event.preventDefault();
    //$v.video.paused ? $v.video.play() : $v.video.pause();
});

$v.video.addEventListener('dblclick', function(event){
    event.preventDefault();
});


$v.video.addEventListener('error', function(){
    // http://www.html5.jp/tag/elements/video.html
});



document.getElementById("comment-form").addEventListener('submit', function(event){
    event.preventDefault();
    $v.comment.post();
});


document.getElementById("controller-play-toggle").addEventListener('click', function(){
    $v.video.paused ? $v.video.play() : $v.video.pause();
});


document.getElementById("controller-volume-toggle").addEventListener('click', function(){
    if($v.video.muted){
        $v.video.muted = false;
        $v.video.volume = 0.5;
    }
    else{
        $v.video.volume = $v.video.volume ? 0 : 0.5;
    }
});

document.getElementById("controller-comment-toggle").addEventListener('click', function(){
    if($v.comment.on){
        $v.comment.on = false;
        $v.comment.clear();
        this.setAttribute("src", $v.controller.parts.commentoff);
    }
    else{
        $v.comment.on = true;
        this.setAttribute("src", $v.controller.parts.commenton);
    }
});


document.getElementById("controller-time-seek").addEventListener('click', function(event){
    var percent = $v.controller.setSeeker($v.controller.timeSeekbar, $v.controller.timeSeeker, event.clientX);
    $v.video.currentTime = $v.video.duration * percent;

});


document.getElementById("controller-volume-seek").addEventListener('click', function(event){
    $v.video.muted = false;
    var percent = $v.controller.setSeeker($v.controller.volumeSeekbar, $v.controller.volumeSeeker, event.clientX);
    $v.video.volume = percent;
});


document.getElementById("controller-screen-toggle").addEventListener('click', function(){
    var element = document.msFullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.fullScreenElement;

    if(!element){
        if     ($v.screen.webkitRequestFullscreen){ $v.screen.webkitRequestFullscreen(); }
        else if($v.screen.mozRequestFullscreen)   { $v.screen.mozRequestFullscreen(); }
        else if($v.screen.msRequestFullscreen)    { $v.screen.msRequestFullscreen(); }
        else if($v.screen.requestFullscreen)      { $v.screen.requestFullscreen(); }
    }
    else{
        if     (document.webkitCancelFullScreen){ document.webkitCancelFullScreen(); }
        else if(document.mozCancelFullScreen)   { document.mozCancelFullScreen(); }
        else if(document.msExitFullscreen)      { document.msExitFullscreen(); }
        else if(document.cancelFullScreen)      { document.cancelFullScreen(); }
    }
});

document.addEventListener("MSFullscreenChange",    function(){ $v.screen.fullscreenEvent(); });
document.addEventListener("webkitfullscreenchange",function(){ $v.screen.fullscreenEvent(); });
document.addEventListener("mozfullscreenchange",   function(){ $v.screen.fullscreenEvent(); });
document.addEventListener("fullscreenchange",      function(){ $v.screen.fullscreenEvent(); });





});

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━;




$css=<<<'━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━'
/* box-sizing:border-box 基準 */

.video{
    position: absolute;
}
.video-screen{
    background-color: #000;
    width: 640px;
    height: 360px;
    overflow: hidden;
    white-space : nowrap;
    cursor: default;
    -ms-user-select: none;
    -moz-user-select: none;
    -webkit-user-select: none;
    user-select: none;
    position: relative;
}

.video-controller{
    display: block;
    width: 640px;
    height: 54px;
    line-height: 1;
    color: white;
    text-decoration: none;
    background: #47494f;
    border-color: #2f3034 #2f3034 #232427;
    background-image: linear-gradient(to bottom, #55585f, #47494f 66%, #3d3f44);
    cursor: default;
    -ms-user-select: none;
    -moz-user-select: none;
    -webkit-user-select: none;
    position:relative;
}
.controller-wrap{
    padding: 0 6px;
    position:absolute;
    top: 5px;
}
.controller-time{
    font-family: Arial, sans-serif;
    font-size: 12px;
    vertical-align: 6px;
    padding: 0;
    display: inline-block;
    width: 35px;
}
.controller-time-current{
    text-align: right;
    
}
.controller-time-total{
    text-align: left;
}
.controller-img{
    margin: 0 3px;
    padding: 0;
    border: none;
}
.controller-seek{
    display: inline-block;
    margin: 0 5px;
    padding: 0;
    height: 20px;
}
.controller-seekbar{
    position: relative;
    height: 5px;
    margin: 0 0 8px 0;
    padding: 0;
    background-color: #fff;
    border-radius: 2px;
    width: 100%;
    top: 7px;
}
.controller-time-seekbar{
    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAAICAIAAABcT7kVAAAAFUlEQVQI12N0WX+DgYGBiYGBgSQKAGI2AdswIf1pAAAAAElFTkSuQmCC");
    background-repeat: no-repeat;
    background-position: 0 0;
    background-size : 0 0;
}
.controller-seeker{
    position: absolute;
	cursor: pointer;
	width: 10px;
	height: 18px;
	background-color: #ccc;
    top: -7px;
    left: 0px;
    background-image: linear-gradient(to bottom, #ccc, #aaa);
    border: solid 1px #999;
    border-radius: 3px;
    display: inline;
}
.controller-time-seek{
    width: 333px;
}
.controller-volume-seek{
    width: 100px;
}

.comment-form{
    width: 100%;
    position: absolute;
    top:29px;
}
.comment-form-input{
    width: 80%;
    height: 25px;
    box-shadow: 3px 3px 3px rgba(200,200,200,0.2) inset;
    border: 1px solid #888888;
    padding:4px 6px 3px 12px;
    vertical-align:middle;
    ime-mode: active;
}
.comment-form-submit{
    width: 20%;
    height: 25px;
    text-decoration: none;
    text-align: center;
    padding: 3px 15px 3px 15px;
    font-size: 14px;
    color: #fff;
    background-color: #5ba825;
    background: linear-gradient(to bottom, #84be5c 0%, #84be5c 50%, #5ba825 50%, #5ba825 100%);
    border: 1px solid #377d00;
    line-height: 1;
    vertical-align: middle;
    font-family: 'MS PGothic', Meiryo, sans-serif;
}



.comment{
    position: absolute;
    line-height: 1;
    z-index: 20;
    color: #fff;
    text-shadow: -1px -1px #333, 1px -1px #333,	-1px 1px #333, 1px 1px #333;
    animation-fill-mode: forwards;
    animation-timing-function: linear;
    animation-duration: 16s;
}
.comment-normal-lane{
    font-size: 24px;
}


.video-screen:-ms-fullscreen{
    position: absolute;
    width: 100%;
    height: 100%;
	left: 0;
 	top: 0;
}
.video-screen:-webkit-full-screen{
    position: absolute;
    width: 100%;
    height: 100%;
	left: 0;
 	top: 0;
}
.video-screen:-moz-fullscreen{
    position: absolute;
    width: 100%;
    height: 100%;
	left: 0;
 	top: 0;
}
.video-screen:fullscreen{
    position: absolute;
    width: 100%;
    height: 100%;
	left: 0;
 	top: 0;
}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━;
