<?php

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>動画登録</title>
<style>

</style>
</head>
<body>
<video id="video" width="640" height="360" loop controls></video>
<button type="button" onClick="$v.capture()" id="capture-button" disabled>サムネイル撮影</button>

<form action="?action=videopost" method="POST" id="form">
<table>
<tr><td>動画URL</td><td><input type="text" name="url" id="url" autocomplete="off" spellcheck="false"></td></tr>

<tr><td>サムネイル</td><td><canvas id="canvas" width="192" height="108"></canvas></td></tr>
<tr><td>動画情報</td><td id="info"></td></tr>
<tr><td>タイトル</td><td><input type="text" name="title" id="title" spellcheck="false"></td></tr>
<tr><td>コメント</td><td><textarea name="text" id="text" spellcheck="false"></textarea></td></tr>
<tr><td colspan="2"><input type="submit" value="     この動画を登録する     " id="submit"></td></tr>
</table>
<input type="hidden" name="userid" id="userid" value="https://me.yahoo.co.jp/a/AjmNPG4KI7K6wv__pqr7NKTl0L9UynRa">
<input type="hidden" name="width" id="width" value="">
<input type="hidden" name="height" id="height" value="">
<input type="hidden" name="duration" id="duration" value="">
<input type="hidden" name="thumbnail" id="thumbnail" value="">
</form>

<script>
$v = {};
document.addEventListener('DOMContentLoaded', function(){

$v.video  = document.getElementById("video");
$v.canvas = document.getElementById("canvas");
$v.form   = document.getElementById("form");
$v.url    = document.getElementById("url");
$v.thumbnail  = document.getElementById("thumbnail");

$v.url.addEventListener('input', function(e){
    document.getElementById("width").value      = "";
    document.getElementById("height").value     = "";
    document.getElementById("duration").value   = "";
    document.getElementById("thumbnail").value  = "";
    document.getElementById("info").textContent = "";
    document.getElementById("capture-button").disabled = true;
    $v.canvas.getContext("2d").clearRect(0, 0, $v.canvas.clientWidth, $v.canvas.clientHeight);

    var url = this.value.trim();
    if(!url.match(/^https*:\/\//i)){
        $v.video.src = "";
        return false;
    }

    $v.video.src = "?action=proxy&url=" + url;
});

$v.video.addEventListener('canplaythrough', function(){
    document.getElementById("width").value      = $v.video.videoWidth;
    document.getElementById("height").value     = $v.video.videoHeight;
    document.getElementById("duration").value   = $v.video.duration;
    document.getElementById("info").textContent = $v.infostr($v.video.videoWidth, $v.video.videoHeight, $v.video.duration);
    document.getElementById("capture-button").disabled = false;

    var pos = $v.objectPosition(640, 360, $v.video.videoWidth, $v.video.videoHeight);
    $v.video.style.width   = pos.w + "px";
    $v.video.style.height  = pos.h + "px";
    $v.video.style.left    = pos.x + "px";
    $v.video.style.top     = pos.y + "px";

    $v.video.play();
});

$v.form.addEventListener('submit', function(){

});

$v.video.addEventListener('click', function(event){
    event.preventDefault();
    $v.video.paused ? $v.video.play() : $v.video.pause();
});



$v.capture = function(){
    var pos = $v.objectPosition($v.canvas.clientWidth, $v.canvas.clientHeight, $v.video.videoWidth, $v.video.videoHeight);
    $v.canvas.getContext("2d").drawImage($v.video, pos.x, pos.y, pos.w, pos.h);
    var str = $v.canvas.toDataURL('image/png');//成功時「data:image/png」 失敗時「data:,」
    if(!str.match(/^data:image/)){
        alert("キャプチャに失敗しました");
        return false;
    }

    $v.thumbnail.value = str.replace(/^.*,/, '');
};

$v.objectPosition = function(screenW, screenH, objectW, objectH){
    var result = {};
    var marginW = screenW - objectW;
    var marginH = screenH - objectH;

    //オブジェクトのサイズ決め
    if((marginW >= 0 && marginH >= 0) || (marginW <= 0 && marginH <= 0)){
        if(marginW < marginH){
            result.w = screenW;
            result.h = screenW / objectW * objectH;
        }
        else{
            result.w = screenH / objectH * objectW;
            result.h = screenH;
        }
    }
    else if(marginW <= 0){
        result.w = screenW;
        result.h = screenW / objectW * objectH;
    }
    else if(marginH <= 0){
        result.w = screenH / objectH * objectW;
        result.h = screenH;
    }
    
    //オブジェクトの位置決め
    if(screenW == result.w){
        result.x = 0;
        result.y = (screenH / 2) - (result.h / 2);
    }
    else{
        result.x = (screenW / 2) - (result.w / 2);
        result.y = 0;
    }
    
    result.w = Math.floor(result.w);
    result.h = Math.floor(result.h);
    result.x = Math.floor(result.x);
    result.y = Math.floor(result.y);
    
    return result;
};

$v.infostr = function(width ,height, time){
    var min = Math.floor(time/60);
    if(min == 0){
        var sec = Math.floor(time);
        return sec + "秒 " + width + "×" + height;
    }
    else{
        var sec = Math.floor(time-min*60);
        return min + "分" + sec + "秒 " + width + "×" + height;
    }
};
});
</script>


</body>
</html>