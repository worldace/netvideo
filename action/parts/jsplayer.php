<?php
//======================================================
// ■プレーヤー部品
// 
// 呼び出し元: "../lib/function.php" 部品()
//======================================================


$html = function($動画){
    return <<<"━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
<div class="jsplayer" id="jsplayer"
  ><div class="jsplayer-screen" tabindex="1"
    ><video class="jsplayer-video" loop></video
  ></div
  ><div class="jsplayer-controller"
    ><div class="jsplayer-controller-wrap"
      ><img class="jsplayer-controller-play-button" width="20" height="20" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xNTc2IDkyN2wtMTMyOCA3MzhxLTIzIDEzLTM5LjUgM3QtMTYuNS0zNnYtMTQ3MnEwLTI2IDE2LjUtMzZ0MzkuNSAzbDEzMjggNzM4cTIzIDEzIDIzIDMxdC0yMyAzMXoiIGZpbGw9IiNmZmYiLz48L3N2Zz4="
      ><span class="jsplayer-controller-current-time">00:00</span
      ><div class="jsplayer-controller-time-seek"
        ><div class="jsplayer-controller-time-seekbar"
          ><span class="jsplayer-controller-time-seeker"></span
        ></div
      ></div
      ><span class="jsplayer-controller-total-time">00:00</span
      ><img class="jsplayer-controller-volume-button" width="20" height="20" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik04MzIgMzUydjEwODhxMCAyNi0xOSA0NXQtNDUgMTktNDUtMTlsLTMzMy0zMzNoLTI2MnEtMjYgMC00NS0xOXQtMTktNDV2LTM4NHEwLTI2IDE5LTQ1dDQ1LTE5aDI2MmwzMzMtMzMzcTE5LTE5IDQ1LTE5dDQ1IDE5IDE5IDQ1em0zODQgNTQ0cTAgNzYtNDIuNSAxNDEuNXQtMTEyLjUgOTMuNXEtMTAgNS0yNSA1LTI2IDAtNDUtMTguNXQtMTktNDUuNXEwLTIxIDEyLTM1LjV0MjktMjUgMzQtMjMgMjktMzUuNSAxMi01Ny0xMi01Ny0yOS0zNS41LTM0LTIzLTI5LTI1LTEyLTM1LjVxMC0yNyAxOS00NS41dDQ1LTE4LjVxMTUgMCAyNSA1IDcwIDI3IDExMi41IDkzdDQyLjUgMTQyem0yNTYgMHEwIDE1My04NSAyODIuNXQtMjI1IDE4OC41cS0xMyA1LTI1IDUtMjcgMC00Ni0xOXQtMTktNDVxMC0zOSAzOS01OSA1Ni0yOSA3Ni00NCA3NC01NCAxMTUuNS0xMzUuNXQ0MS41LTE3My41LTQxLjUtMTczLjUtMTE1LjUtMTM1LjVxLTIwLTE1LTc2LTQ0LTM5LTIwLTM5LTU5IDAtMjYgMTktNDV0NDUtMTlxMTMgMCAyNiA1IDE0MCA1OSAyMjUgMTg4LjV0ODUgMjgyLjV6bTI1NiAwcTAgMjMwLTEyNyA0MjIuNXQtMzM4IDI4My41cS0xMyA1LTI2IDUtMjYgMC00NS0xOXQtMTktNDVxMC0zNiAzOS01OSA3LTQgMjIuNS0xMC41dDIyLjUtMTAuNXE0Ni0yNSA4Mi01MSAxMjMtOTEgMTkyLTIyN3Q2OS0yODktNjktMjg5LTE5Mi0yMjdxLTM2LTI2LTgyLTUxLTctNC0yMi41LTEwLjV0LTIyLjUtMTAuNXEtMzktMjMtMzktNTkgMC0yNiAxOS00NXQ0NS0xOXExMyAwIDI2IDUgMjExIDkxIDMzOCAyODMuNXQxMjcgNDIyLjV6IiBmaWxsPSIjZmZmIi8+PC9zdmc+"
      ><div class="jsplayer-controller-volume-seek"
        ><div class="jsplayer-controller-volume-seekbar"
          ><span class="jsplayer-controller-volume-seeker"></span
        ></div
      ></div
      ><img class="jsplayer-controller-comment-button" width="20" height="20" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Im02NDAsNzkycTAsLTUzIC0zNy41LC05MC41dC05MC41LC0zNy41dC05MC41LDM3LjV0LTM3LjUsOTAuNXQzNy41LDkwLjV0OTAuNSwzNy41dDkwLjUsLTM3LjV0MzcuNSwtOTAuNXptMzg0LDBxMCwtNTMgLTM3LjUsLTkwLjV0LTkwLjUsLTM3LjV0LTkwLjUsMzcuNXQtMzcuNSw5MC41dDM3LjUsOTAuNXQ5MC41LDM3LjV0OTAuNSwtMzcuNXQzNy41LC05MC41em0zODQsMHEwLC01MyAtMzcuNSwtOTAuNXQtOTAuNSwtMzcuNXQtOTAuNSwzNy41dC0zNy41LDkwLjV0MzcuNSw5MC41dDkwLjUsMzcuNXQ5MC41LC0zNy41dDM3LjUsLTkwLjV6bTM4NCwwcTAsMTc0IC0xMjAsMzIxLjV0LTMyNiwyMzN0LTQ1MCw4NS41cS0xMTAsMCAtMjExLC0xOHEtMTczLDE3MyAtNDM1LDIyOXEtNTIsMTAgLTg2LDEzcS0xMiwxIC0yMiwtNnQtMTMsLTE4cS00LC0xNSAyMCwtMzdxNSwtNSAyMy41LC0yMS41dDI1LjUsLTIzLjV0MjMuNSwtMjUuNXQyNCwtMzEuNXQyMC41LC0zN3QyMCwtNDh0MTQuNSwtNTcuNXQxMi41LC03Mi41cS0xNDYsLTkwIC0yMjkuNSwtMjE2LjV0LTgzLjUsLTI2OS41cTAsLTE3NCAxMjAsLTMyMS41dDMyNiwtMjMzLjAwMDA3NnQ0NTAsLTg1LjUwMDMydDQ1MCw4NS41MDAzMnQzMjYsMjMzLjAwMDA3NnQxMjAsMzIxLjV6IiBmaWxsPSIjZmZmIi8+PC9zdmc+"
      ><img class="jsplayer-controller-screen-button" width="20" height="20" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik04ODMgMTA1NnEwIDEzLTEwIDIzbC0zMzIgMzMyIDE0NCAxNDRxMTkgMTkgMTkgNDV0LTE5IDQ1LTQ1IDE5aC00NDhxLTI2IDAtNDUtMTl0LTE5LTQ1di00NDhxMC0yNiAxOS00NXQ0NS0xOSA0NSAxOWwxNDQgMTQ0IDMzMi0zMzJxMTAtMTAgMjMtMTB0MjMgMTBsMTE0IDExNHExMCAxMCAxMCAyM3ptNzgxLTg2NHY0NDhxMCAyNi0xOSA0NXQtNDUgMTktNDUtMTlsLTE0NC0xNDQtMzMyIDMzMnEtMTAgMTAtMjMgMTB0LTIzLTEwbC0xMTQtMTE0cS0xMC0xMC0xMC0yM3QxMC0yM2wzMzItMzMyLTE0NC0xNDRxLTE5LTE5LTE5LTQ1dDE5LTQ1IDQ1LTE5aDQ0OHEyNiAwIDQ1IDE5dDE5IDQ1eiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg=="
    ></div
    ><form class="jsplayer-form" action="javascript:void(0)"
      ><input class="jsplayer-form-input" type="text" name="本文" value="" autocomplete="off" spellcheck="false" maxlength="60" tabindex="2" disabled
      ><input class="jsplayer-form-button" type="submit" value="コメントする" disabled
      ><input class="jsplayer-form-hidden-id" type="hidden" name="id" value="{$動画['id']}"
      ><input class="jsplayer-form-hidden-time" type="hidden" name="登録時間" value="{$動画['登録時間']}"
    ></form
  ></div
></div>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━;
};



$js = function ($動画){
    $code = <<<'━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━'
var $v = {};

//■ player
$v.player = document.getElementById("jsplayer");


$v.player.entrypoint = function(url, width, height){
    width  = width  || 640;
    height = height || 360;
    $v.screen.style.width  = width + "px";
    $v.screen.style.height = height + "px";
    $v.controller.style.width = width + "px";
    $v.controller.timeSeek.style.width = width - 307 + "px";

    $v.comment.keyframe(width);
    $v.comment.setting(height);

    $v.user = $v.loadObject("jsplayer") || {};
    $v.user.volume = Number($v.user.volume) || 1;
    $v.controller.setSeeker($v.user.volume, $v.controller.volumeSeeker);

    $v.player.style.visibility = "visible";

    $v.screen.pos = $v.screen.getBoundingClientRect();
    $v.screen.focus();

    if(url){ $v.video.src = url; }
};


$v.player.addEventListener('keydown', function(event){
    $v.controller.style.visibility = "visible";
    if(event.target.tagName.match(/input/i)){ return true; }

    if(event.which == 32){ //Space
        $v.form.input.focus();
    }
    else if(event.which == 13 && event.ctrlKey){ //Ctrl+Enter ※IE11で効かない
        $v.screen.toggleFullscreen();
    }
    else if(event.which == 13){ //Enter
        $v.video.paused ? $v.video.play() : $v.video.pause();
    }
    else if(event.which == 39 && event.ctrlKey){ //Ctrl+→
        $v.video.setTime($v.video.currentTime + 30);
    }
    else if(event.which == 39 && event.shiftKey){ //Shift+→
        $v.video.setTime($v.video.currentTime + 30);
    }
    else if(event.which == 37 && event.ctrlKey){ //Ctrl+←
        $v.video.setTime($v.video.currentTime - 30);
    }
    else if(event.which == 37 && event.shiftKey){ //Shift+←
        $v.video.setTime($v.video.currentTime - 30);
    }
    else if(event.which == 39){ //→
        $v.video.setTime($v.video.currentTime + 15);
    }
    else if(event.which == 37){ //←
        $v.video.setTime($v.video.currentTime - 15);
    }
    else if(event.which == 36){ //Home
        $v.video.setTime(0);
    }
    else if(event.which == 35){ //End
        $v.video.setTime($v.video.duration - 10);
    }
    else if(event.which == 38){ //↑
        $v.video.setVolume($v.video.volume + 0.1);
    }
    else if(event.which == 40){ //↓
        $v.video.setVolume($v.video.volume - 0.1);
    }
    else if(event.which == 107){ //num+
        $v.video.setSpeed($v.video.playbackRate + 0.1);
    }
    else if(event.which == 187 && event.shiftKey){ //+
        $v.video.setSpeed($v.video.playbackRate + 0.1);
    }
    else if(event.which == 109){ //num-
        $v.video.setSpeed($v.video.playbackRate - 0.1);
    }
    else if(event.which == 189){ //-
        $v.video.setSpeed($v.video.playbackRate - 0.1);
    }
    else{
        return true;
    }

    event.preventDefault();
});


window.addEventListener('unload', function(event){
    $v.saveObject("jsplayer", $v.user);
});






//■ screen
$v.screen = $v.player.querySelector(".jsplayer-screen");


$v.screen.showOsd = function(str){
    var osd = document.createElement("span");
    osd.textContent = str;
    osd.className   = "jsplayer-screen-osd";
    osd.style.fontSize = $v.comment.fontSize + "px";

    $v.screen.clearOsd();
    $v.screen.appendChild(osd);
    $v.screen.osdTimer = window.setTimeout($v.screen.clearOsd, 1500);
};


$v.screen.clearOsd = function(){
    var osd = $v.screen.querySelectorAll(".jsplayer-screen-osd");
    for(var i = osd.length-1; i >= 0; i--){
        $v.screen.removeChild(osd[i]);
    }
    if($v.screen.osdTimer){ window.clearTimeout($v.screen.osdTimer); }
};


$v.screen.isFullscreen = function(){
    var element = document.fullscreenElement || document.msFullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement;
    return (element && element.className == $v.screen.className) ? true : false;
};


$v.screen.toggleFullscreen = function(){
    if(!$v.screen.isFullscreen()){
        if     ($v.screen.requestFullscreen)      { $v.screen.requestFullscreen(); }
        else if($v.screen.msRequestFullscreen)    { $v.screen.msRequestFullscreen(); }
        else if($v.screen.webkitRequestFullscreen){ $v.screen.webkitRequestFullscreen(); }
        else if($v.screen.mozRequestFullScreen)   { $v.screen.mozRequestFullScreen(); }
    }
    else{
        if     (document.exitFullscreen)      { document.exitFullscreen(); }
        else if(document.msExitFullscreen)    { document.msExitFullscreen(); }
        else if(document.webkitExitFullscreen){ document.webkitExitFullscreen(); }
        else if(document.mozCancelFullScreen) { document.mozCancelFullScreen(); }
    }
};


$v.screen.fullscreenEvent = function(){
    if($v.screen.isFullscreen()){
        $v.screen.pos = {left:0, top:0, right:screen.width, bottom:screen.height, width:screen.width, height:screen.height}; //IE11で正常に取得できないので手動設定
        $v.screen.addEventListener('click', $v.controller.toggle);
        $v.controller.intoScreen();
    }
    else{
        $v.screen.pos = $v.screen.getBoundingClientRect();
        $v.screen.removeEventListener('click', $v.controller.toggle);
        $v.controller.intoPlayer();
    }
    $v.video.fit();
    $v.comment.setting($v.screen.pos.height);
    $v.comment.clear();
};


document.addEventListener("fullscreenchange",       $v.screen.fullscreenEvent);
document.addEventListener("MSFullscreenChange",     $v.screen.fullscreenEvent);
document.addEventListener("webkitfullscreenchange", $v.screen.fullscreenEvent);
document.addEventListener("mozfullscreenchange",    $v.screen.fullscreenEvent);




//■ video
$v.video = $v.player.querySelector(".jsplayer-video");


$v.video.fit = function(){
    if(!$v.screen.pos.width || !$v.video.videoWidth){ return; }
    var pos = $v.contentFit($v.screen.pos.width, $v.screen.pos.height, $v.video.videoWidth, $v.video.videoHeight);

    $v.video.style.width  = pos.w + "px";
    $v.video.style.height = pos.h + "px";
    $v.video.style.left   = pos.x + "px";
    $v.video.style.top    = pos.y + "px";
};


$v.video.setTime = function(sec){
    if(!$v.video.duration){ return; }
    if(sec < 1){ sec = 0; }
    if(sec > $v.video.duration){ sec = $v.video.duration; }
    $v.video.currentTime = sec;
};


$v.video.setVolume = function(volume){
    volume = volume.toFixed(1);
    if(volume >= 1){ volume = 1; }
    if(volume <= 0){ volume = 0; }
    $v.video.volume = volume;
    $v.video.muted  = false;
};


$v.video.setSpeed = function(speed){
    if(!$v.video.duration){ return; }
    speed = speed.toFixed(1);
    if(speed >= 2){ speed = 2; }
    if(speed <= 0.5){ speed = 0.5; }
    $v.video.playbackRate = speed;
};


$v.video.isSeekable = function(sec){
    for(var i = 0; i < $v.video.seekable.length; i++){
        if(sec >= $v.video.seekable.start(i) && sec <= $v.video.seekable.end(i)){ return true; }
    }
    return false;
};


$v.video.addEventListener('loadedmetadata', function(){
    $v.comment.get();

    $v.controller.setBuffer();
    $v.controller.setTime($v.video.duration, $v.controller.totalTime);
    $v.form.input.disabled  = false;
    $v.form.button.disabled = false;

    $v.video.volume = $v.user.volume;
    $v.video.fit();
});


$v.video.addEventListener('canplaythrough', function(){
    $v.video.play();
});


$v.video.addEventListener('timeupdate', function(){
    var sec = Math.floor($v.video.currentTime);
    if(sec === $v.video.prevSec){ return; }
    $v.video.prevSec = sec;

    if(!$v.controller.timeSeeker.isDragging){
        $v.controller.setSeeker($v.video.currentTime/$v.video.duration, $v.controller.timeSeeker);
        $v.controller.setTime(sec, $v.controller.currentTime);
    }
    if(sec in $v.comment.list && $v.video.paused === false && $v.comment.on !== false){
        $v.comment.release($v.comment.list[sec], $v.comment.laneCheck());
    }
});


$v.video.addEventListener('play', function(){
    $v.comment.run();
    $v.controller.playButton.setAttribute("src", $v.controller.parts.pause);
});


$v.video.addEventListener('pause', function(){
    $v.comment.pause();
    $v.controller.playButton.setAttribute("src", $v.controller.parts.play);
});


$v.video.addEventListener('progress', function(){
    $v.controller.setBuffer();
});


$v.video.addEventListener('seeking', function(){
    $v.comment.clear();
});


$v.video.addEventListener('ended', function(){
    $v.comment.clear();
});


$v.video.addEventListener('volumechange', function(){
    if(!$v.video.volume || $v.video.muted){
        $v.controller.volumeButton.setAttribute("src", $v.controller.parts.mute);
        $v.controller.setSeeker(0, $v.controller.volumeSeeker);
    }
    else{
        $v.controller.volumeButton.setAttribute("src", $v.controller.parts.volume);
        $v.controller.setSeeker($v.video.volume, $v.controller.volumeSeeker);
        $v.user.volume = $v.video.volume;
    }
});


$v.video.addEventListener('ratechange', function(){
    $v.screen.showOsd("x" + $v.video.playbackRate.toFixed(1));
});


$v.video.addEventListener('click', function(event){
    if(!$v.video.currentTime){ $v.video.play(); }
    event.preventDefault();
});


$v.video.addEventListener('dblclick', function(event){
    event.preventDefault();
});


$v.video.addEventListener('error', function(event){
    var error = event.target.error;

    switch(error.code){ // http://www.html5.jp/tag/elements/video.html
        case error.MEDIA_ERR_SRC_NOT_SUPPORTED:
            alert("動画ファイルが存在しません");
            break;
        case error.MEDIA_ERR_DECODE:
            alert("動画ファイルが未対応の形式です");
            break;
        case error.MEDIA_ERR_NETWORK:
            alert("動画ファイルのダウンロードが失敗しました");
            break;
        case error.MEDIA_ERR_ABORTED:
            //alert("動画の再生が中止されました");
            break;
        default:
            alert("未知のエラーが発生しました");
            break;
    }
});



//■ comment
$v.comment = {};


$v.comment.release = function(comments, lane){
    var vdom  = document.createDocumentFragment();
    var index = 0;
    for(var i = 0; i < lane.length; i++){
        if(!(index in comments)){ break; }
        if(lane[i] === false){ continue; }
        vdom.appendChild($v.comment.create(comments[index], i));
        index++;
    }
    if(index){ $v.screen.insertBefore(vdom, $v.screen.firstChild); }
};


$v.comment.create = function(data, laneNumber){
    var comment = document.createElement("span");
    comment.textContent = data[0];
    comment.className = "jsplayer-comment";
    comment.setAttribute("data-lane", laneNumber);

    comment.style.top = laneNumber * $v.comment.laneHeight + $v.comment.marginTop + "px";
    comment.style.fontSize = $v.comment.fontSize + "px";
    comment.style.animationName = $v.screen.isFullscreen() ? $v.player.id+"fulllane" : $v.player.id+"normallane";

    var delay = data[1] - $v.video.currentTime;
    delay = (delay <= 0) ? 0 : delay.toFixed(3)*1000;
    comment.style.animationDelay = delay + "ms";

    return comment;
};


$v.comment.keyframe = function(width){
    var css = "";
    css += "@keyframes " + $v.player.id + "normallane{";
    css += "from{transform:translateX(0);}";
    css += "to{transform:translateX(-" + width*5 + "px);}}";
    document.styleSheets[0].insertRule(css, 0);

    css = "";
    css += "@keyframes " + $v.player.id + "fulllane{";
    css += "from{transform:translateX(0);}";
    css += "to{transform:translateX(-" + screen.width*5 + "px);}}";
    document.styleSheets[0].insertRule(css, 0);
};


$v.comment.setting = function(height){
    if(height >= 360){
        $v.comment.laneCount  = Math.floor((height-360)/180) + 10;
        $v.comment.laneHeight = height / $v.comment.laneCount * 0.8;
        $v.comment.fontSize   = $v.comment.laneHeight / 6 * 5; //22.5px以上必要
        $v.comment.marginTop  = $v.comment.laneHeight / 6;
    }
    else{
        $v.comment.laneCount  = Math.floor(height*0.8/30);
        $v.comment.laneHeight = 30;
        $v.comment.fontSize   = 25;
        $v.comment.marginTop  = 5;
    }
};


$v.comment.laneCheck = function(){
    var lane = Array($v.comment.laneCount);
    for(var i = 0; i < lane.length; i++){ lane[i] = true; }
 
    var comments = $v.screen.querySelectorAll(".jsplayer-comment");
    for(var i = comments.length-1; i >= 0; i--){
        comments[i].pos = comments[i].getBoundingClientRect();
        if(comments[i].pos.right > $v.screen.pos.right-30){ lane[comments[i].getAttribute("data-lane")] = false; }
        if(comments[i].pos.right < $v.screen.pos.left)    { $v.screen.removeChild(comments[i]); }
    }
    return lane;
};


$v.comment.clear = function(){
    var comments = $v.screen.querySelectorAll(".jsplayer-comment");
    for(var i = comments.length-1; i >= 0; i--){
        comments[i].style.opacity = 0; //firefox：フルスクリーン時に画面最上部のコメントが消えないことがある対策
        $v.screen.removeChild(comments[i]);
    }
};


$v.comment.pause = function(){
    var comments = $v.screen.querySelectorAll(".jsplayer-comment");
    for(var i = 0; i < comments.length; i++){
        comments[i].style.animationPlayState = "paused";
    }
};


$v.comment.run = function(){
    var comments = $v.screen.querySelectorAll(".jsplayer-comment");
    for(var i = 0; i < comments.length; i++){
        comments[i].style.animationPlayState = "running";
    }
};


$v.comment.get = function(){
    var sec  = Math.floor($v.video.duration);

    $v.comment.list = Array(sec+1); //動画時間+1の箱を作る [[],[],[],[]...]
    for(var i = 0; i < $v.comment.list.length; i++){ $v.comment.list[i] = []; }

    var url = "?" + $v.param({
        "action"   : "commentget",
        "id"       : $v.form.hiddenId.value,
        "登録時間" : $v.form.hiddenTime.value,
        "件数"     : sec * 4,
        "nocache"  : Date.now()
    });

    $v.get(url, function(xhr){
        try{ var comments = JSON.parse(xhr.responseText); } catch(e){ return; }

        for(var i = 0; i < comments.length; i++){
            if(comments[i].本文 == null){ continue; }
            var index = Math.floor(comments[i].位置/100);
            if(index in $v.comment.list){ $v.comment.list[index].push([comments[i].本文.substring(0,64), comments[i].位置/100]); }
        }
    });
};


$v.comment.post = function(){
    var sec  = $v.video.currentTime;
    var text = $v.form.input.value.trim();

    if(text == "" || text.length > 64){ return; }

    $v.comment.list[Math.floor(sec+1)].unshift([text, sec+1, Math.floor(Date.now()/1000)]);
    $v.form.input.value = "";

    var formdata = new FormData($v.form);
    formdata.append("本文", text);
    formdata.append("位置", sec.toFixed(2)*100);
    $v.post('?action=commentpost', formdata);
};



//■ controller
$v.controller               = $v.player.querySelector(".jsplayer-controller");
$v.controller.timeSeek      = $v.player.querySelector(".jsplayer-controller-time-seek");
$v.controller.timeSeekbar   = $v.player.querySelector(".jsplayer-controller-time-seekbar");
$v.controller.timeSeeker    = $v.player.querySelector(".jsplayer-controller-time-seeker")
$v.controller.volumeSeek    = $v.player.querySelector(".jsplayer-controller-volume-seek")
$v.controller.volumeSeekbar = $v.player.querySelector(".jsplayer-controller-volume-seekbar");
$v.controller.volumeSeeker  = $v.player.querySelector(".jsplayer-controller-volume-seeker");
$v.controller.currentTime   = $v.player.querySelector(".jsplayer-controller-current-time");
$v.controller.totalTime     = $v.player.querySelector(".jsplayer-controller-total-time");
$v.controller.playButton    = $v.player.querySelector(".jsplayer-controller-play-button");
$v.controller.volumeButton  = $v.player.querySelector(".jsplayer-controller-volume-button");
$v.controller.commentButton = $v.player.querySelector(".jsplayer-controller-comment-button");
$v.controller.screenButton  = $v.player.querySelector(".jsplayer-controller-screen-button");

$v.controller.parts = {
    play:       "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xNTc2IDkyN2wtMTMyOCA3MzhxLTIzIDEzLTM5LjUgM3QtMTYuNS0zNnYtMTQ3MnEwLTI2IDE2LjUtMzZ0MzkuNSAzbDEzMjggNzM4cTIzIDEzIDIzIDMxdC0yMyAzMXoiIGZpbGw9IiNmZmYiLz48L3N2Zz4=",
    pause:      "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xNjY0IDE5MnYxNDA4cTAgMjYtMTkgNDV0LTQ1IDE5aC01MTJxLTI2IDAtNDUtMTl0LTE5LTQ1di0xNDA4cTAtMjYgMTktNDV0NDUtMTloNTEycTI2IDAgNDUgMTl0MTkgNDV6bS04OTYgMHYxNDA4cTAgMjYtMTkgNDV0LTQ1IDE5aC01MTJxLTI2IDAtNDUtMTl0LTE5LTQ1di0xNDA4cTAtMjYgMTktNDV0NDUtMTloNTEycTI2IDAgNDUgMTl0MTkgNDV6IiBmaWxsPSIjZmZmIi8+PC9zdmc+",
    volume:     "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik04MzIgMzUydjEwODhxMCAyNi0xOSA0NXQtNDUgMTktNDUtMTlsLTMzMy0zMzNoLTI2MnEtMjYgMC00NS0xOXQtMTktNDV2LTM4NHEwLTI2IDE5LTQ1dDQ1LTE5aDI2MmwzMzMtMzMzcTE5LTE5IDQ1LTE5dDQ1IDE5IDE5IDQ1em0zODQgNTQ0cTAgNzYtNDIuNSAxNDEuNXQtMTEyLjUgOTMuNXEtMTAgNS0yNSA1LTI2IDAtNDUtMTguNXQtMTktNDUuNXEwLTIxIDEyLTM1LjV0MjktMjUgMzQtMjMgMjktMzUuNSAxMi01Ny0xMi01Ny0yOS0zNS41LTM0LTIzLTI5LTI1LTEyLTM1LjVxMC0yNyAxOS00NS41dDQ1LTE4LjVxMTUgMCAyNSA1IDcwIDI3IDExMi41IDkzdDQyLjUgMTQyem0yNTYgMHEwIDE1My04NSAyODIuNXQtMjI1IDE4OC41cS0xMyA1LTI1IDUtMjcgMC00Ni0xOXQtMTktNDVxMC0zOSAzOS01OSA1Ni0yOSA3Ni00NCA3NC01NCAxMTUuNS0xMzUuNXQ0MS41LTE3My41LTQxLjUtMTczLjUtMTE1LjUtMTM1LjVxLTIwLTE1LTc2LTQ0LTM5LTIwLTM5LTU5IDAtMjYgMTktNDV0NDUtMTlxMTMgMCAyNiA1IDE0MCA1OSAyMjUgMTg4LjV0ODUgMjgyLjV6bTI1NiAwcTAgMjMwLTEyNyA0MjIuNXQtMzM4IDI4My41cS0xMyA1LTI2IDUtMjYgMC00NS0xOXQtMTktNDVxMC0zNiAzOS01OSA3LTQgMjIuNS0xMC41dDIyLjUtMTAuNXE0Ni0yNSA4Mi01MSAxMjMtOTEgMTkyLTIyN3Q2OS0yODktNjktMjg5LTE5Mi0yMjdxLTM2LTI2LTgyLTUxLTctNC0yMi41LTEwLjV0LTIyLjUtMTAuNXEtMzktMjMtMzktNTkgMC0yNiAxOS00NXQ0NS0xOXExMyAwIDI2IDUgMjExIDkxIDMzOCAyODMuNXQxMjcgNDIyLjV6IiBmaWxsPSIjZmZmIi8+PC9zdmc+",
    mute:       "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Im04MzIsMzQ4bDAsMTA4OHEwLDI2IC0xOSw0NXQtNDUsMTl0LTQ1LC0xOWwtMzMzLC0zMzNsLTI2MiwwcS0yNiwwIC00NSwtMTl0LTE5LC00NWwwLC0zODRxMCwtMjYgMTksLTQ1dDQ1LC0xOWwyNjIsMGwzMzMsLTMzM3ExOSwtMTkgNDUsLTE5dDQ1LDE5dDE5LDQ1eiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg==",
    commenton:  "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Im02NDAsNzkycTAsLTUzIC0zNy41LC05MC41dC05MC41LC0zNy41dC05MC41LDM3LjV0LTM3LjUsOTAuNXQzNy41LDkwLjV0OTAuNSwzNy41dDkwLjUsLTM3LjV0MzcuNSwtOTAuNXptMzg0LDBxMCwtNTMgLTM3LjUsLTkwLjV0LTkwLjUsLTM3LjV0LTkwLjUsMzcuNXQtMzcuNSw5MC41dDM3LjUsOTAuNXQ5MC41LDM3LjV0OTAuNSwtMzcuNXQzNy41LC05MC41em0zODQsMHEwLC01MyAtMzcuNSwtOTAuNXQtOTAuNSwtMzcuNXQtOTAuNSwzNy41dC0zNy41LDkwLjV0MzcuNSw5MC41dDkwLjUsMzcuNXQ5MC41LC0zNy41dDM3LjUsLTkwLjV6bTM4NCwwcTAsMTc0IC0xMjAsMzIxLjV0LTMyNiwyMzN0LTQ1MCw4NS41cS0xMTAsMCAtMjExLC0xOHEtMTczLDE3MyAtNDM1LDIyOXEtNTIsMTAgLTg2LDEzcS0xMiwxIC0yMiwtNnQtMTMsLTE4cS00LC0xNSAyMCwtMzdxNSwtNSAyMy41LC0yMS41dDI1LjUsLTIzLjV0MjMuNSwtMjUuNXQyNCwtMzEuNXQyMC41LC0zN3QyMCwtNDh0MTQuNSwtNTcuNXQxMi41LC03Mi41cS0xNDYsLTkwIC0yMjkuNSwtMjE2LjV0LTgzLjUsLTI2OS41cTAsLTE3NCAxMjAsLTMyMS41dDMyNiwtMjMzLjAwMDA3NnQ0NTAsLTg1LjUwMDMydDQ1MCw4NS41MDAzMnQzMjYsMjMzLjAwMDA3NnQxMjAsMzIxLjV6IiBmaWxsPSIjZmZmIi8+PC9zdmc+",
    commentoff: "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Im0xNzkyLDc5MnEwLDE3NCAtMTIwLDMyMS41dC0zMjYsMjMzdC00NTAsODUuNXEtNzAsMCAtMTQ1LC04cS0xOTgsMTc1IC00NjAsMjQycS00OSwxNCAtMTE0LDIycS0xNywyIC0zMC41LC05dC0xNy41LC0yOWwwLC0xcS0zLC00IC0wLjUsLTEydDIsLTEwdDQuNSwtOS41bDYsLTlsNywtOC41bDgsLTlxNywtOCAzMSwtMzQuNXQzNC41LC0zOHQzMSwtMzkuNXQzMi41LC01MXQyNywtNTl0MjYsLTc2cS0xNTcsLTg5IC0yNDcuNSwtMjIwdC05MC41LC0yODFxMCwtMTMwIDcxLC0yNDguNXQxOTEsLTIwNC41MDA3OTN0Mjg2LC0xMzYuNDk5Nzg2dDM0OCwtNTAuNDk5ODE3cTI0NCwwIDQ1MCw4NS40OTk2OHQzMjYsMjMzLjAwMDM4MXQxMjAsMzIxLjUwMDMzNnoiIGZpbGw9IiNmZmYiLz48L3N2Zz4=",
    fullscreen: "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik04ODMgMTA1NnEwIDEzLTEwIDIzbC0zMzIgMzMyIDE0NCAxNDRxMTkgMTkgMTkgNDV0LTE5IDQ1LTQ1IDE5aC00NDhxLTI2IDAtNDUtMTl0LTE5LTQ1di00NDhxMC0yNiAxOS00NXQ0NS0xOSA0NSAxOWwxNDQgMTQ0IDMzMi0zMzJxMTAtMTAgMjMtMTB0MjMgMTBsMTE0IDExNHExMCAxMCAxMCAyM3ptNzgxLTg2NHY0NDhxMCAyNi0xOSA0NXQtNDUgMTktNDUtMTlsLTE0NC0xNDQtMzMyIDMzMnEtMTAgMTAtMjMgMTB0LTIzLTEwbC0xMTQtMTE0cS0xMC0xMC0xMC0yM3QxMC0yM2wzMzItMzMyLTE0NC0xNDRxLTE5LTE5LTE5LTQ1dDE5LTQ1IDQ1LTE5aDQ0OHEyNiAwIDQ1IDE5dDE5IDQ1eiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg==",
    setting:    "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xMTUyIDg5NnEwLTEwNi03NS0xODF0LTE4MS03NS0xODEgNzUtNzUgMTgxIDc1IDE4MSAxODEgNzUgMTgxLTc1IDc1LTE4MXptNTEyLTEwOXYyMjJxMCAxMi04IDIzdC0yMCAxM2wtMTg1IDI4cS0xOSA1NC0zOSA5MSAzNSA1MCAxMDcgMTM4IDEwIDEyIDEwIDI1dC05IDIzcS0yNyAzNy05OSAxMDh0LTk0IDcxcS0xMiAwLTI2LTlsLTEzOC0xMDhxLTQ0IDIzLTkxIDM4LTE2IDEzNi0yOSAxODYtNyAyOC0zNiAyOGgtMjIycS0xNCAwLTI0LjUtOC41dC0xMS41LTIxLjVsLTI4LTE4NHEtNDktMTYtOTAtMzdsLTE0MSAxMDdxLTEwIDktMjUgOS0xNCAwLTI1LTExLTEyNi0xMTQtMTY1LTE2OC03LTEwLTctMjMgMC0xMiA4LTIzIDE1LTIxIDUxLTY2LjV0NTQtNzAuNXEtMjctNTAtNDEtOTlsLTE4My0yN3EtMTMtMi0yMS0xMi41dC04LTIzLjV2LTIyMnEwLTEyIDgtMjN0MTktMTNsMTg2LTI4cTE0LTQ2IDM5LTkyLTQwLTU3LTEwNy0xMzgtMTAtMTItMTAtMjQgMC0xMCA5LTIzIDI2LTM2IDk4LjUtMTA3LjV0OTQuNS03MS41cTEzIDAgMjYgMTBsMTM4IDEwN3E0NC0yMyA5MS0zOCAxNi0xMzYgMjktMTg2IDctMjggMzYtMjhoMjIycTE0IDAgMjQuNSA4LjV0MTEuNSAyMS41bDI4IDE4NHE0OSAxNiA5MCAzN2wxNDItMTA3cTktOSAyNC05IDEzIDAgMjUgMTAgMTI5IDExOSAxNjUgMTcwIDcgOCA3IDIyIDAgMTItOCAyMy0xNSAyMS01MSA2Ni41dC01NCA3MC41cTI2IDUwIDQxIDk4bDE4MyAyOHExMyAyIDIxIDEyLjV0OCAyMy41eiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg=="
};


$v.controller.setBuffer = function(){
    var seekbarWidth = $v.controller.timeSeekbar.getBoundingClientRect().width;
    var buffer = $v.video.buffered;

    if(buffer.length){
        $v.controller.timeSeekbar.style.backgroundPosition = buffer.start(0) / $v.video.duration * seekbarWidth + "px";
        $v.controller.timeSeekbar.style.backgroundSize     = buffer.end(buffer.length-1) / $v.video.duration * seekbarWidth + "px";
    }
};


$v.controller.setTime = function(time, where){
    var min = Math.floor(time / 60);
    var sec = Math.floor(time - min * 60);

    if(min < 10){ min = '0' + min; }
    if(sec < 10){ sec = '0' + sec; }

    where.textContent = min + ":" + sec;
};


$v.controller.setSeeker = function(percent, seeker){
    var seekbar = seeker.parentNode;

    seeker.pos  = seeker.getBoundingClientRect();
    seekbar.pos = seekbar.getBoundingClientRect();
    var seekbarWidth = seekbar.pos.width - seeker.pos.width;

    var pos = (percent <= 1) ? seekbarWidth*percent : percent-seekbar.pos.left; //percentは「割合の時(0-1)」or「クリックされた位置の時」の2パターンある

    if(pos < 0){ pos = 0; }
    if(pos > seekbarWidth){ pos = seekbarWidth; }

    seeker.style.left = pos + "px";
    return pos/seekbarWidth;
};


$v.controller.timeSeeker.mousemoveEvent = function(event, seekend){
    var percent = $v.controller.setSeeker(event.clientX, $v.controller.timeSeeker);
    $v.controller.setTime($v.video.duration*percent, $v.controller.currentTime);
    if(seekend){ $v.video.currentTime = $v.video.duration * percent; }
};


$v.controller.volumeSeeker.mousemoveEvent = function(event){
    $v.video.volume = $v.controller.setSeeker(event.clientX, $v.controller.volumeSeeker);
};


$v.controller.toggle = function(event){
    if($v.controller.timeSeeker.isDragging){ return; }

    if($v.controller.style.visibility == "hidden"){
        event.preventDefault();
        $v.controller.style.visibility = "visible";
    }
    else{
        var controller = $v.controller.getBoundingClientRect();
        if(controller.left <= event.clientX && controller.right >= event.clientX){
            if(controller.top <= event.clientY && controller.bottom >= event.clientY){
                return;
            }
        }
        $v.controller.style.visibility = "hidden";
    }
};


$v.controller.intoScreen = function(){
    $v.screen.appendChild($v.controller);
    var controller = $v.controller.getBoundingClientRect();
    $v.controller.style.top  = screen.height - controller.height + "px";
    $v.controller.style.left = (screen.width/2) - (controller.width/2) + "px";
};


$v.controller.intoPlayer = function(){
    $v.player.appendChild($v.controller);
    $v.controller.style.top  = 0;
    $v.controller.style.left = 0;
    $v.controller.style.visibility = "visible";
};


$v.controller.playButton.addEventListener('click', function(){
    $v.video.paused ? $v.video.play() : $v.video.pause();
});


$v.controller.timeSeek.addEventListener('click', function(event){
    if(!$v.video.duration){ return; }
    var percent = $v.controller.setSeeker(event.clientX, $v.controller.timeSeeker);
    $v.video.currentTime = $v.video.duration * percent;

});


$v.controller.timeSeek.addEventListener('wheel', function(event){
    (event.deltaY > 0) ? $v.video.setTime($v.video.currentTime+15) : $v.video.setTime($v.video.currentTime-15);
});


$v.controller.timeSeeker.addEventListener('mousedown', function(event){
    if(!$v.video.duration){ return; }
    $v.controller.timeSeeker.isDragging = true;
    document.addEventListener('mousemove', $v.controller.timeSeeker.mousemoveEvent);
    document.addEventListener('mouseup', function mouseupEvent(event){
        $v.controller.timeSeeker.mousemoveEvent(event, true);
        document.removeEventListener('mousemove', $v.controller.timeSeeker.mousemoveEvent);
        document.removeEventListener('mouseup',  mouseupEvent);
        $v.controller.timeSeeker.isDragging = false;
    });
});


$v.controller.volumeSeek.addEventListener('click', function(event){
    $v.video.muted = false;
    $v.video.volume = $v.controller.setSeeker(event.clientX, $v.controller.volumeSeeker);
});


$v.controller.volumeSeek.addEventListener('wheel', function(event){
    (event.deltaY < 0) ? $v.video.setVolume($v.video.volume+0.1) : $v.video.setVolume($v.video.volume-0.1);
});


$v.controller.volumeSeeker.addEventListener('mousedown', function(event){
    document.addEventListener('mousemove', $v.controller.volumeSeeker.mousemoveEvent);
    document.addEventListener('mouseup', function mouseupEvent(event){
        document.removeEventListener('mousemove', $v.controller.volumeSeeker.mousemoveEvent);
        document.removeEventListener('mouseup', mouseupEvent);
    });
});


$v.controller.volumeButton.addEventListener('click', function(){
    if($v.video.muted){
        $v.video.muted = false;
        $v.video.volume = 0.5;
    }
    else{
        $v.video.volume = $v.video.volume ? 0 : 0.5;
    }
});


$v.controller.commentButton.addEventListener('click', function(){
    if($v.comment.on){
        $v.comment.on = false;
        $v.comment.clear();
        $v.controller.commentButton.setAttribute("src", $v.controller.parts.commentoff);
    }
    else{
        $v.comment.on = true;
        $v.controller.commentButton.setAttribute("src", $v.controller.parts.commenton);
    }
});


$v.controller.screenButton.addEventListener('click', function(){
    $v.screen.toggleFullscreen();
});



//■ form
$v.form            = $v.player.querySelector(".jsplayer-form");
$v.form.input      = $v.player.querySelector(".jsplayer-form-input");
$v.form.button     = $v.player.querySelector(".jsplayer-form-button");
$v.form.hiddenId   = $v.player.querySelector(".jsplayer-form-hidden-id");
$v.form.hiddenTime = $v.player.querySelector(".jsplayer-form-hidden-time");


$v.form.addEventListener('submit', function(event){
    event.preventDefault();
    $v.comment.post();
    $v.video.play();
    $v.screen.focus();
});


$v.form.input.addEventListener('focus', function(event){
    $v.video.pause();
});





//■ function
$v.get = function(url, success){
    $v.ajax(url, {method:"GET", success:success});
};


$v.post = function(url, data, success){
    $v.ajax(url, {method:"POST", data:data, success:success});
};


$v.ajax = function(url, option){ //method, data, timeout, credential, header, success, error, complete
    option = option || {};
    option.method  = option.method  || "GET";
    option.timeout = option.timeout || 60;

    var body = "";
    var xhr  = new XMLHttpRequest();
    xhr.open(option.method, url);

    if(option.timeout >= 0){ xhr.timeout = option.timeout * 1000; }
    if(option.credential){ xhr.withCredentials = true; }
    if(option.method.match(/^POST$/i)){
        if(option.data instanceof FormData){
            body = option.data;
        }
        else{
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            for(var key in option.data){
                body += encodeURIComponent(key) + "=" + encodeURIComponent(option.data[key]) + "&";
            }
        }
    }
    if(option.header){
        for(var key in option.header){
            xhr.setRequestHeader(key, option.header[key]);
        }
    }
    xhr.addEventListener('loadend', function(){
        if(xhr.status >= 200 && xhr.status < 300 || xhr.status == 304){
            if(typeof option.success === "function"){ option.success(xhr); }
        }
        else{
            if(typeof option.error === "function"){ option.error(xhr); }
        }
        if(typeof option.complete === "function"){ option.complete(xhr); }
    });

    xhr.send(body);
};


$v.param = function(param){
    var str = "";
    for(var key in param){
        if(!param.hasOwnProperty(key)){ continue; }
        str += encodeURIComponent(key) + "=" + encodeURIComponent(param[key]) + "&";
    }
    return str;
};


$v.deparam = function(str){
    if(str == null){ return {}; }
    str = String(str);
    str = str.replace(/^\?/, "");
    str = str.replace(/#.*/, "");
    var result = {};
    var namevalue = str.split('&');
    for(var i = 0; i < namevalue.length; i++){
        var name  = namevalue[i].split('=')[0] || "";
        var value = namevalue[i].split('=')[1] || "";
        if(name == ""){ continue; }
        result[decodeURIComponent(name)] = decodeURIComponent(value);
    }
    return result;
};


$v.saveObject = function(name, value){
    try{ window.localStorage.setItem(name, JSON.stringify(value)); } catch(e){}
};


$v.loadObject = function(name){
    try{ return JSON.parse(window.localStorage.getItem(name)); } catch(e){}
};


$v.objectExtend = function(){
    if(!arguments.length){ return; }
    if(arguments.lenth == 1){ return arguments[0]; }
    var destination = Array.prototype.shift.call(arguments);
    for (var i = 0; i < arguments.length; i++) {
        var source = arguments[i];
        for(var property in source){
            if(source[property] && source[property].constructor && source[property].constructor === Object){
                destination[property] = destination[property] || {};
                $v.objectExtend(destination[property], source[property]);
            }
            else{
                destination[property] = source[property];
            }
        }
    }
    return destination;
};


$v.contentFit = function(screenW, screenH, objectW, objectH){
    var scale = (objectW/objectH > screenW/screenH) ? screenW/objectW : screenH/objectH;
    return {
        w: Math.floor(objectW * scale),
        h: Math.floor(objectH * scale),
        x: Math.floor((screenW / 2) - (objectW * scale / 2)),
        y: Math.floor((screenH / 2) - (objectH * scale / 2)),
    };
};


$v.type = function(target){
    return Object.prototype.toString.call(target).replace(/^\[object (.+)\]$/, '$1').toLowerCase();
};


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━;

    return $code . '$v.player.entrypoint("' . $動画['url'] . '", 640, 360);';
};






$css = <<<'━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━'

.jsplayer{
    visibility: hidden;
}

.jsplayer-video{
    position: absolute;
}

.jsplayer-screen{
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

.jsplayer-screen:focus{
    outline: none;
}

.jsplayer-screen-osd{
    font-family: Arial, sans-serif;
    position: absolute;
    right: 2%;
    top: 5%;
    background-color: #000;
    color:#0f0;
    z-index: 3;
}

.jsplayer-screen:-ms-fullscreen{
    position: absolute;
    width: 100% !important;
    height: 100% !important;
	left: 0;
 	top: 0;
}

.jsplayer-screen:-webkit-full-screen{
    position: absolute;
    width: 100% !important;
    height: 100% !important;
	left: 0;
 	top: 0;
}

.jsplayer-screen:-moz-full-screen{
    position: absolute;
    width: 100% !important;
    height: 100% !important;
	left: 0;
 	top: 0;
}

.jsplayer-screen:fullscreen{
    position: absolute;
    width: 100% !important;
    height: 100% !important;
	left: 0;
 	top: 0;
}

.jsplayer-controller{
    display: block;
    width: 640px;
    height: 55px;
    line-height: 1;
    color: white;
    text-decoration: none;
    background: #47494f;
    border-color: #2f3034 #2f3034 #232427;
    background-image: linear-gradient(to bottom, #555, #333 66%, #000);
    cursor: default;
    -ms-user-select: none;
    -moz-user-select: none;
    -webkit-user-select: none;
    user-select: none;
    position:relative;
    visibility:visible;
}

.jsplayer-controller-wrap{
    padding: 0 6px;
    position:absolute;
    top: 5px;
}

.jsplayer-controller-current-time,
.jsplayer-controller-total-time{
    font-family: Arial, sans-serif;
    font-size: 12px;
    vertical-align: 6px;
    padding: 0;
    display: inline-block;
    width: 35px;
}

.jsplayer-controller-current-time{
    text-align: right;
}

.jsplayer-controller-total-time{
    text-align: left;
}

.jsplayer-controller-play-button,
.jsplayer-controller-volume-button,
.jsplayer-controller-comment-button,
.jsplayer-controller-screen-button{
    margin: 0 3px;
    padding: 0;
    border: none;
	cursor: pointer;
}

.jsplayer-controller-time-seek,
.jsplayer-controller-volume-seek{
    display: inline-block;
    margin: 0 5px;
    padding: 0;
    height: 20px;
}

.jsplayer-controller-time-seek{
    width: 333px;
}

.jsplayer-controller-volume-seek{
    width: 100px;
}

.jsplayer-controller-time-seekbar,
.jsplayer-controller-volume-seekbar{
    position: relative;
    height: 5px;
    margin: 0 0 8px 0;
    padding: 0;
    background-color: #fff;
    border-radius: 2px;
    width: 100%;
    top: 7px;
}

.jsplayer-controller-time-seekbar{
    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAAICAIAAABcT7kVAAAAFUlEQVQI12N0WX+DgYGBiYGBgSQKAGI2AdswIf1pAAAAAElFTkSuQmCC");
    background-repeat: no-repeat;
    background-position: 0;
    background-size : 0;
}

.jsplayer-controller-time-seeker,
.jsplayer-controller-volume-seeker{
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

.jsplayer-form{
    width: 100%;
    position: absolute;
    top:29px;
}

.jsplayer-form-input{
    width: 80%;
    height: 26px;
    box-shadow: 3px 3px 3px rgba(200,200,200,0.2) inset;
    border: 1px solid #888888;
    border-radius: 0;
    padding:4px 6px 3px 12px;
    vertical-align:middle;
    ime-mode: active;
    box-sizing: border-box;
}

.jsplayer-form-button{
    width: 20%;
    height: 26px;
    text-decoration: none;
    text-align: center;
    padding: 3px 15px 3px 15px;
    font-size: 14px;
    color: #fff;
    background-color: #5ba825;
    background: linear-gradient(to bottom, #84be5c 0%, #84be5c 50%, #5ba825 50%, #5ba825 100%);
    border: 1px solid #377d00;
    border-radius: 0;
    line-height: 1;
    vertical-align: middle;
    font-family: 'MS PGothic', Meiryo, sans-serif;
	cursor: pointer;
    box-sizing: border-box;
}

.jsplayer-comment{
    font-family: 'MS PGothic', Meiryo, sans-serif;
    position: absolute;
    left: 100%;
    line-height: 1;
    z-index: 2;
    color: #fff;
    text-shadow: -1px -1px #333, 1px -1px #333,	-1px 1px #333, 1px 1px #333;
    animation-fill-mode: forwards;
    animation-timing-function: linear;
    animation-duration: 17s;
    opacity: 0.9;
}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━;
