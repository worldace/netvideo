<?php

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>動画登録</title>
</head>
<body>
<video id="video" width="640" height="360" loop controls></video>
<form action="?action=videopost" method="POST">
<table>
<tr><td>動画URL</td><td><input type="text" name="url" id="url"></td></tr>
<tr><td>サムネイル</td><td><button type="button" onClick="capture()" id="capture-button" disabled>キャプチャ</button><br>
<canvas id="canvas" width="640" height="360"></canvas></td></tr>
<tr><td>タイトル</td><td><input type="text" name="title" id="title"></td></tr>
<tr><td>コメント</td><td><textarea name="comment" id="comment"></textarea></td></tr>
<tr><td colspan="2"><input type="submit" value="動画を登録する" id="submit"></td></tr>
</table>
<input type="hidden" name="img" id="img" value="">
<input type="hidden" name="width" id="width" value="">
<input type="hidden" name="height" id="height" value="">
<input type="hidden" name="duration" id="duration" value="">
</form>

<script>
$v = {};
document.addEventListener('DOMContentLoaded', function(){

$v.video  = document.getElementById("video");
$v.canvas = document.getElementById("canvas");
$v.input  = document.getElementById("img");
$v.url    = document.getElementById("url");

$v.url.addEventListener('input', function(e){
    document.getElementById("img").value      = "";
    document.getElementById("width").value    = "";
    document.getElementById("height").value   = "";
    document.getElementById("duration").value = "";
    document.getElementById("capture-button").disabled = true;
    $v.canvas.getContext("2d").clearRect(0, 0, 640, 360);

    var url = this.value.trim();
    if(!url.match(/^https*:\/\//i)){
        $v.video.src = "";
        return false;
    }

    $v.video.src = "?action=proxy&url=" + url;
});

$v.video.addEventListener('canplaythrough', function(){
    document.getElementById("width").value    = $v.video.videoWidth;
    document.getElementById("height").value   = $v.video.videoHeight;
    document.getElementById("duration").value = $v.video.duration;
    document.getElementById("capture-button").disabled = false;
/*
    var pos = objectPosition($v.player.width, $v.screen.height, $v.video.videoWidth, $v.video.videoHeight);
    $v.video.style.width   = pos.w + "px";
    $v.video.style.height  = pos.h + "px";
    $v.video.style.left    = pos.x + "px";
    $v.video.style.top     = pos.y + "px";
*/
    $v.video.play();
});


});
function capture(){
    $v.canvas.getContext("2d").drawImage($v.video, 0, 0, 640, 360);
    var str = $v.canvas.toDataURL('image/png');//成功時「data:image/png」 失敗時「data:,」
    if(str.indexOf("data:image/png") === -1){
        alert("キャプチャに失敗しました");
        return false;
    }

    $v.input.value = str.replace(/^.*,/, '');
}
</script>


</body>
</html>