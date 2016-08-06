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
<button type="button" onClick="$v.capture()" id="撮影ボタン" disabled>サムネイル撮影</button>

<form action="?action=videopost" method="POST" id="form">
<table>
<tr><td>動画ファイルのURL</td><td><input type="text" name="url" id="url" autocomplete="off" spellcheck="false"></td></tr>

<tr><td>サムネイル</td><td><canvas id="canvas" width="256" height="144"></canvas></td></tr>
<tr><td>動画情報</td><td id="動画情報"></td></tr>
<tr><td>タイトル</td><td><input type="text" name="タイトル" id="タイトル" spellcheck="false"></td></tr>
<tr><td>コメント</td><td><textarea name="本文" id="本文" spellcheck="false"></textarea></td></tr>
<tr><td colspan="2"><input type="submit" value="     この動画を登録する     " id="submit"></td></tr>
</table>
<input type="hidden" name="ユーザid" id="ユーザid" value="https://me.yahoo.co.jp/a/">
<input type="hidden" name="横幅" id="横幅" value="">
<input type="hidden" name="縦幅" id="縦幅" value="">
<input type="hidden" name="長さ" id="長さ" value="">
<input type="hidden" name="サムネイル" id="サムネイル" value="">
</form>

<script>
$v = {};
document.addEventListener('DOMContentLoaded', function(){

$v.video  = document.getElementById("video");
$v.canvas = document.getElementById("canvas");
$v.form   = document.getElementById("form");
$v.url    = document.getElementById("url");
$v.thumbnail  = document.getElementById("サムネイル");

$v.url.addEventListener('input', function(e){
    document.getElementById("横幅").value    = "";
    document.getElementById("縦幅").value    = "";
    document.getElementById("長さ").value    = "";
    document.getElementById("サムネイル").value  = "";
    document.getElementById("動画情報").textContent = "";
    document.getElementById("撮影ボタン").disabled = true;
    $v.canvas.getContext("2d").clearRect(0, 0, $v.canvas.clientWidth, $v.canvas.clientHeight);

    var url = this.value.trim();
    if(!url.match(/^https*:\/\//i)){
        $v.video.src = "";
        return false;
    }
    $v.video.src = "?action=proxy&url=" + encodeURIComponent(url);
});

$v.video.addEventListener('canplaythrough', function(){
    document.getElementById("横幅").value = $v.video.videoWidth;
    document.getElementById("縦幅").value = $v.video.videoHeight;
    document.getElementById("長さ").value = $v.video.duration;
    document.getElementById("動画情報").textContent = $v.infostr($v.video.videoWidth, $v.video.videoHeight, $v.video.duration);
    document.getElementById("撮影ボタン").disabled = false;

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