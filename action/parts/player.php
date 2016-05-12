<?php
function parts_player(){
    return <<<━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
<div id="video-player" class="video-player"
><div id="video-screen" class="video-screen"
><video id="video" src="http://127.0.0.1/1.mp4" width="640" height="360" autoplay loop controls></video
></div
><div id="video-controller" class="video-controller"
><div class="controller-wrap"
><img id="controller-play-toggle" class="controller-img" width="20" height="20" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAAAuklEQVQ4y2NsaGhgIBcwka0TRTN3cGF9fWEwz3niNbOgcvl0ijfoMDzb4ZV10tyQPGdLeWzbUF8Yyn3yPBmaIY7QKtm2oT7VioIAk3Ktr68vDBYgSzM0JPKxhiULMZqhJmCEJanxLOWBFBDE2wwFT3cGzDlhSLLmT1cm9q/9wMCAiH/iND/b3Tj7GKYwIc2frvVEVH3FkdrwaP50pTdu7RdDBtzpFLtmWKgQSN7omjFDBQ9gHASFARkAAAB5PwlcqrFmAAAAAElFTkSuQmCC"
><span id="controller-time-current" class="controller-time controller-time-current">00:00</span
><div id="controller-time-seek" class="controller-seek controller-time-seek"><div id="controller-time-seekbar" class="controller-seekbar"><span id="controller-time-seeker" class="controller-seeker"></span></div></div
><span id="controller-time-total" class="controller-time controller-time-total">00:00</span
><img id="controller-volume-toggle" class="controller-img" width="20" height="20" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAABuElEQVQ4y72UsU7jQBCGfxBdfCuL0kE8gFkKV47TWQhhpUKyIh1NGggFFMbCfZLmCuQoUEBjaK6KQJauihxFiA57KwosHuBEtkSWLw9whXM+FDm6QHFT7Yz22393/tEutdttfDaWP01+AC6ZdZU9LQqHQt2sTHeHTDTWZWPwo2XXS++OWJ5H3p7KdPM4SzU18XsdozYaE9npf8v5lXkkyVMmun2LkjTuNvZruBlsH57pPT8pUJ4hc9mAE3r63d263B9yQqfvXwEQsurNYLtceH8mun1r/edFz+ug2TL2jnFwF2tW9UxnfvKPbmtq4oScUOug8nT+zCFtnODhdQKyKi9klRZ5AceXso44SSGsbSF+Sxf1ORTqVQm/xg+gIsHk9R50lSwEh0x0mzLho+tIOdmUkCYx9DUB6dsLgKWZ2Q4rzWBHmiZ81PEes2XJtB2KuNtw6FWwI8Tdhj9RZpW1yDOGfEbctFsOJeNhw7nXXU0CZ/5EKR4SLfIM/NXPfPaBkOlu36KEB18voc6Bc/62/JJXSqYdtAjAg9oRU5WsWAxnfO9922wyHu5eRwr+kAUN+1D8l8+gMH4D4qKxk5B4zcwAAAAASUVORK5CYII="
><div id="controller-volume-seek" class="controller-seek controller-volume-seek"><div id="controller-volume-seekbar" class="controller-seekbar"><span id="controller-volume-seeker" class="controller-seeker"></span></div></div
><img id="controller-comment-toggle" class="controller-img" width="20" height="20" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAABgUlEQVQ4y2NsaGhgIBcwka2TgYGBBVPo+EmBnhX5OnzIYp+u9Mat/WKIT/Nxi9Qd7lLY7ODTKd6gw8DA8Gx34+xj6M4+flIguLAeh04kIOVaX18YzHMeoRmbO/EAPp3iRRD9TAwMDAz5oUTrhOmPzIZqLtBFcu2z3R5eu59CQ2miR++1TwwMDAwMT3cGeOx8huR+bfOT55nZOQNCoizE2KFad4Q3MJr/mfpTLUbs8eT0ueJi7y6LGFn9OdS/6Y/sk3M/1BxUeCEq//94sZ7J0vzDky8IA626HI+fFOixlGLg04rMZzh+0ipSh49ByjyY5/xxnlArhBO/PNnLwNDQ0NB9+eN/UsHHq92e/kwMDAx7yuZf+URSgH26Mrvqq7khEwMDg6X5h5IIaCARA57uhKY2ZgcHBwYGBlmZx+cOHkQKD1zg2Q6v2M0M0HSKSJ7HTwoEF+LRBUuY5ogUjpS2kZLK050Bc04YMhACUM3cwYU7IFqhNhDWycDAwDhghQFFmgFoMLfsYBAAkAAAAABJRU5ErkJggg=="
><img id="controller-screen-toggle" class="controller-img" width="20" height="20" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAAABnRSTlMAgACAAIDTzS+jAAAA0klEQVQ4y2NsaGhgIBcwka2TgYGBhaCK4zyhq4q1+JCFnu1unH2MKJstv6yedeUT+c7+urZ/xzNyNZun1ntIkaUZrvPTlYk9qO5nIl5n/9oPX9f291z59PTyVMKaj5+0kuH5BNcJ9/+cE4YQNiMliQR7PJun1ntIfbrSG7f2iyEezVBnH+cR4D55HtWffDqR2YRtPn5SoGdFvk5x6A6vLIY5G5BDiHhnS3ls2wBhEaMTe2gTqRO7Zj6dUHOY/0nWzMAg5bFtGjH6KYpnigoDijQDAEFsWdMJ3UD+AAAAAElFTkSuQmCC"
></div
><form id="comment-form" class="comment-form"
><input id="comment-input" class="comment-input" type="text" name="text" value="" autocomplete="off" spellcheck="false"
><input id="comment-submit" class="comment-submit" type="submit" value="コメントする"
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
$v.screen.pos = $v.screen.getBoundingClientRect();

$v.comment = {}
$v.comment.display = true;
$v.comment.form   = document.getElementById("comment-form");
$v.comment.input  = document.getElementById("comment-input");

$v.controller = {};
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
    var comments = document.querySelectorAll(".comment");
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

$v.comment.lane = function(){
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
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhr.timeout = 20000;
    xhr.send(body);
};

$v.controller.setSeeker = function(seekbar, seeker, percent){
    var seekbar_pos   = seekbar.getBoundingClientRect();
    var seeker_pos    = seeker.getBoundingClientRect();
    var seeker_width  = seeker_pos.right - seeker_pos.left;
    var seekbar_width = seekbar_pos.right - seekbar_pos.left - seeker_width;
    var pos = 0;

    if(percent <= 1){//percentが0-1の割合の時
        pos = seekbar_width * percent;
    }
    else{//percentがクリックされた位置の時
        pos = percent - seekbar_pos.left;
    }

    if(pos < 0){ pos = 0; }
    if(pos > seekbar_width){ pos = seekbar_width; }

    seeker.style.left = pos + "px";
    return pos/seekbar_width;
};


$v.controller.setTime = function(time, element){
    var min = Math.floor(time/60);
    var sec = Math.floor(time-min*60);

    min = ('0' + min).slice(-2); //ゼロパディング(2桁)
    sec = ('0' + sec).slice(-2);

    element.textContent = min + ":" + sec;
};


$v.video.addEventListener('canplaythrough', function(){
    //コントローラの時間セット
    $v.controller.setTime($v.video.duration, $v.controller.timeTotal);

    //ボリュームのシークセット
    $v.controller.setSeeker($v.controller.volumeSeekbar, $v.controller.volumeSeeker, $v.video.volume);
});


$v.video.addEventListener('timeupdate', function(){
    var sec_now = Math.floor($v.video.currentTime);
    if(sec_now !== $v.video.beforeTime){
        //コントローラの現在時間セット
        $v.controller.setTime(sec_now, $v.controller.timeCurrent);
        
        //コントローラの時間シークセット
        $v.controller.setSeeker($v.controller.timeSeekbar, $v.controller.timeSeeker, sec_now/$v.video.duration);

        //欄外のコメント削除
        $v.comment.out();

        //コメント放出
        if(sec_now in $v.comment.list && $v.video.paused === false && $v.comment.display === true){
            $v.comment.release($v.comment.list[sec_now], $v.comment.lane());
        }
        
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
    $v.post('?action=commentpost', {id:1, text:text, time:"123456789"});//new FormData($v.comment.form)
});






document.getElementById("controller-play-toggle").addEventListener('click', function(e){
    $v.video.paused ? $v.video.play() : $v.video.pause();
});


document.getElementById("controller-volume-toggle").addEventListener('click', function(e){
    $v.video.volume = $v.video.volume ? 0 : 0.5;
});

document.getElementById("controller-comment-toggle").addEventListener('click', function(e){
    if($v.comment.display){
        $v.comment.display = false;
        $v.comment.clear();
        this.setAttribute("src", $v.controller.parts.commentoff);
    }
    else{
        $v.comment.display = true;
        this.setAttribute("src", $v.controller.parts.commenton);
    }
});


document.getElementById("controller-time-seek").addEventListener('click', function(event){
    var percent = $v.controller.setSeeker($v.controller.timeSeekbar, $v.controller.timeSeeker, event.clientX);
    $v.video.currentTime = $v.video.duration * percent;

});


document.getElementById("controller-volume-seek").addEventListener('click', function(event){
    var percent = $v.controller.setSeeker($v.controller.volumeSeekbar, $v.controller.volumeSeeker, event.clientX);
    $v.video.volume = percent;
});






});

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━;




$css=<<<'━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━'
.video-player{
    width: 640px;
}
.video-screen{
    background-color: #000;
    width: 100%;
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
    height: 53px;
    line-height: 1;
    color: white;
    text-decoration: none;
    /*border-radius: 5px;*/
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
    margin: 0 2px;
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
    width: 640px;
    position: absolute;
    top:29px;
}
.comment-input{
    width: 80%;
    height: 24px;
    box-shadow: 3px 3px 3px rgba(200,200,200,0.2) inset;
    border: 1px solid #888888;
    padding:6px 6px 3px 12px;
    font-size:14px;
    vertical-align:middle;
    ime-mode: active;
}
.comment-submit{
    width: 20%;
    height: 24px;
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
    font-size: 24px;
    line-height: 1;
    z-index: 2;
    color: #fff;
    text-shadow: -1px -1px #000, 1px -1px #000,	-1px 1px #000, 1px 1px #000;
    animation-name: lane0;
    animation-fill-mode: forwards;
    animation-timing-function: linear;
    animation-duration: 8s;

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

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━;
