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
><video id="video" class="video" src="{$video['動画URL']}" loop></video
></div
><div id="video-controller" class="video-controller"
><div class="controller-wrap"
><img id="controller-play-toggle" class="controller-img" width="20" height="20" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xNTc2IDkyN2wtMTMyOCA3MzhxLTIzIDEzLTM5LjUgM3QtMTYuNS0zNnYtMTQ3MnEwLTI2IDE2LjUtMzZ0MzkuNSAzbDEzMjggNzM4cTIzIDEzIDIzIDMxdC0yMyAzMXoiIGZpbGw9IiNmZmYiLz48L3N2Zz4="
><span id="controller-time-current" class="controller-time controller-time-current">00:00</span
><div id="controller-time-seek" class="controller-seek controller-time-seek"><div id="controller-time-seekbar" class="controller-seekbar controller-time-seekbar"><span id="controller-time-seeker" class="controller-seeker"></span></div></div
><span id="controller-time-total" class="controller-time controller-time-total">00:00</span
><img id="controller-volume-toggle" class="controller-img" width="20" height="20" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik04MzIgMzUydjEwODhxMCAyNi0xOSA0NXQtNDUgMTktNDUtMTlsLTMzMy0zMzNoLTI2MnEtMjYgMC00NS0xOXQtMTktNDV2LTM4NHEwLTI2IDE5LTQ1dDQ1LTE5aDI2MmwzMzMtMzMzcTE5LTE5IDQ1LTE5dDQ1IDE5IDE5IDQ1em0zODQgNTQ0cTAgNzYtNDIuNSAxNDEuNXQtMTEyLjUgOTMuNXEtMTAgNS0yNSA1LTI2IDAtNDUtMTguNXQtMTktNDUuNXEwLTIxIDEyLTM1LjV0MjktMjUgMzQtMjMgMjktMzUuNSAxMi01Ny0xMi01Ny0yOS0zNS41LTM0LTIzLTI5LTI1LTEyLTM1LjVxMC0yNyAxOS00NS41dDQ1LTE4LjVxMTUgMCAyNSA1IDcwIDI3IDExMi41IDkzdDQyLjUgMTQyem0yNTYgMHEwIDE1My04NSAyODIuNXQtMjI1IDE4OC41cS0xMyA1LTI1IDUtMjcgMC00Ni0xOXQtMTktNDVxMC0zOSAzOS01OSA1Ni0yOSA3Ni00NCA3NC01NCAxMTUuNS0xMzUuNXQ0MS41LTE3My41LTQxLjUtMTczLjUtMTE1LjUtMTM1LjVxLTIwLTE1LTc2LTQ0LTM5LTIwLTM5LTU5IDAtMjYgMTktNDV0NDUtMTlxMTMgMCAyNiA1IDE0MCA1OSAyMjUgMTg4LjV0ODUgMjgyLjV6bTI1NiAwcTAgMjMwLTEyNyA0MjIuNXQtMzM4IDI4My41cS0xMyA1LTI2IDUtMjYgMC00NS0xOXQtMTktNDVxMC0zNiAzOS01OSA3LTQgMjIuNS0xMC41dDIyLjUtMTAuNXE0Ni0yNSA4Mi01MSAxMjMtOTEgMTkyLTIyN3Q2OS0yODktNjktMjg5LTE5Mi0yMjdxLTM2LTI2LTgyLTUxLTctNC0yMi41LTEwLjV0LTIyLjUtMTAuNXEtMzktMjMtMzktNTkgMC0yNiAxOS00NXQ0NS0xOXExMyAwIDI2IDUgMjExIDkxIDMzOCAyODMuNXQxMjcgNDIyLjV6IiBmaWxsPSIjZmZmIi8+PC9zdmc+"
><div id="controller-volume-seek" class="controller-seek controller-volume-seek"><div id="controller-volume-seekbar" class="controller-seekbar controller-volume-seekbar"><span id="controller-volume-seeker" class="controller-seeker"></span></div></div
><img id="controller-comment-toggle" class="controller-img" width="20" height="20" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Im02NDAsNzkycTAsLTUzIC0zNy41LC05MC41dC05MC41LC0zNy41dC05MC41LDM3LjV0LTM3LjUsOTAuNXQzNy41LDkwLjV0OTAuNSwzNy41dDkwLjUsLTM3LjV0MzcuNSwtOTAuNXptMzg0LDBxMCwtNTMgLTM3LjUsLTkwLjV0LTkwLjUsLTM3LjV0LTkwLjUsMzcuNXQtMzcuNSw5MC41dDM3LjUsOTAuNXQ5MC41LDM3LjV0OTAuNSwtMzcuNXQzNy41LC05MC41em0zODQsMHEwLC01MyAtMzcuNSwtOTAuNXQtOTAuNSwtMzcuNXQtOTAuNSwzNy41dC0zNy41LDkwLjV0MzcuNSw5MC41dDkwLjUsMzcuNXQ5MC41LC0zNy41dDM3LjUsLTkwLjV6bTM4NCwwcTAsMTc0IC0xMjAsMzIxLjV0LTMyNiwyMzN0LTQ1MCw4NS41cS0xMTAsMCAtMjExLC0xOHEtMTczLDE3MyAtNDM1LDIyOXEtNTIsMTAgLTg2LDEzcS0xMiwxIC0yMiwtNnQtMTMsLTE4cS00LC0xNSAyMCwtMzdxNSwtNSAyMy41LC0yMS41dDI1LjUsLTIzLjV0MjMuNSwtMjUuNXQyNCwtMzEuNXQyMC41LC0zN3QyMCwtNDh0MTQuNSwtNTcuNXQxMi41LC03Mi41cS0xNDYsLTkwIC0yMjkuNSwtMjE2LjV0LTgzLjUsLTI2OS41cTAsLTE3NCAxMjAsLTMyMS41dDMyNiwtMjMzLjAwMDA3NnQ0NTAsLTg1LjUwMDMydDQ1MCw4NS41MDAzMnQzMjYsMjMzLjAwMDA3NnQxMjAsMzIxLjV6IiBmaWxsPSIjZmZmIi8+PC9zdmc+"
><img id="controller-screen-toggle" class="controller-img" width="20" height="20" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik04ODMgMTA1NnEwIDEzLTEwIDIzbC0zMzIgMzMyIDE0NCAxNDRxMTkgMTkgMTkgNDV0LTE5IDQ1LTQ1IDE5aC00NDhxLTI2IDAtNDUtMTl0LTE5LTQ1di00NDhxMC0yNiAxOS00NXQ0NS0xOSA0NSAxOWwxNDQgMTQ0IDMzMi0zMzJxMTAtMTAgMjMtMTB0MjMgMTBsMTE0IDExNHExMCAxMCAxMCAyM3ptNzgxLTg2NHY0NDhxMCAyNi0xOSA0NXQtNDUgMTktNDUtMTlsLTE0NC0xNDQtMzMyIDMzMnEtMTAgMTAtMjMgMTB0LTIzLTEwbC0xMTQtMTE0cS0xMC0xMC0xMC0yM3QxMC0yM2wzMzItMzMyLTE0NC0xNDRxLTE5LTE5LTE5LTQ1dDE5LTQ1IDQ1LTE5aDQ0OHEyNiAwIDQ1IDE5dDE5IDQ1eiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg=="
></div
><form id="comment-form" class="comment-form" action="javascript:void 0"
><input id="comment-form-input" class="comment-form-input" type="text" name="comment" value="" autocomplete="off" spellcheck="false" maxlength="60" disabled
><input id="comment-form-submit" class="comment-form-submit" type="submit" value="コメントする" disabled
><input id="comment-form-id" type="hidden" name="id" value="{$video['動画ID']}"
><input id="comment-form-path" type="hidden" name="path" value="{$video['投稿時間']}"
></form
></div
></div>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━;
}



$js=<<<'━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━'

var $v = $v || {};

document.addEventListener('DOMContentLoaded', function(){


$v.video  = document.getElementById("video");

$v.player = document.getElementById("video-player");

$v.screen     = document.getElementById("video-screen");
$v.screen.pos = $v.screen.getBoundingClientRect();

$v.controller               = document.getElementById("video-controller");
$v.controller.timeSeekbar   = document.getElementById("controller-time-seekbar");
$v.controller.timeSeeker    = document.getElementById("controller-time-seeker");
$v.controller.volumeSeekbar = document.getElementById("controller-volume-seekbar");
$v.controller.volumeSeeker  = document.getElementById("controller-volume-seeker");
$v.controller.timeCurrent   = document.getElementById("controller-time-current");
$v.controller.timeTotal     = document.getElementById("controller-time-total");
$v.controller.parts = {
    play:       "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xNTc2IDkyN2wtMTMyOCA3MzhxLTIzIDEzLTM5LjUgM3QtMTYuNS0zNnYtMTQ3MnEwLTI2IDE2LjUtMzZ0MzkuNSAzbDEzMjggNzM4cTIzIDEzIDIzIDMxdC0yMyAzMXoiIGZpbGw9IiNmZmYiLz48L3N2Zz4=",
    pause:      "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xNjY0IDE5MnYxNDA4cTAgMjYtMTkgNDV0LTQ1IDE5aC01MTJxLTI2IDAtNDUtMTl0LTE5LTQ1di0xNDA4cTAtMjYgMTktNDV0NDUtMTloNTEycTI2IDAgNDUgMTl0MTkgNDV6bS04OTYgMHYxNDA4cTAgMjYtMTkgNDV0LTQ1IDE5aC01MTJxLTI2IDAtNDUtMTl0LTE5LTQ1di0xNDA4cTAtMjYgMTktNDV0NDUtMTloNTEycTI2IDAgNDUgMTl0MTkgNDV6IiBmaWxsPSIjZmZmIi8+PC9zdmc+",
    volume:     "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik04MzIgMzUydjEwODhxMCAyNi0xOSA0NXQtNDUgMTktNDUtMTlsLTMzMy0zMzNoLTI2MnEtMjYgMC00NS0xOXQtMTktNDV2LTM4NHEwLTI2IDE5LTQ1dDQ1LTE5aDI2MmwzMzMtMzMzcTE5LTE5IDQ1LTE5dDQ1IDE5IDE5IDQ1em0zODQgNTQ0cTAgNzYtNDIuNSAxNDEuNXQtMTEyLjUgOTMuNXEtMTAgNS0yNSA1LTI2IDAtNDUtMTguNXQtMTktNDUuNXEwLTIxIDEyLTM1LjV0MjktMjUgMzQtMjMgMjktMzUuNSAxMi01Ny0xMi01Ny0yOS0zNS41LTM0LTIzLTI5LTI1LTEyLTM1LjVxMC0yNyAxOS00NS41dDQ1LTE4LjVxMTUgMCAyNSA1IDcwIDI3IDExMi41IDkzdDQyLjUgMTQyem0yNTYgMHEwIDE1My04NSAyODIuNXQtMjI1IDE4OC41cS0xMyA1LTI1IDUtMjcgMC00Ni0xOXQtMTktNDVxMC0zOSAzOS01OSA1Ni0yOSA3Ni00NCA3NC01NCAxMTUuNS0xMzUuNXQ0MS41LTE3My41LTQxLjUtMTczLjUtMTE1LjUtMTM1LjVxLTIwLTE1LTc2LTQ0LTM5LTIwLTM5LTU5IDAtMjYgMTktNDV0NDUtMTlxMTMgMCAyNiA1IDE0MCA1OSAyMjUgMTg4LjV0ODUgMjgyLjV6bTI1NiAwcTAgMjMwLTEyNyA0MjIuNXQtMzM4IDI4My41cS0xMyA1LTI2IDUtMjYgMC00NS0xOXQtMTktNDVxMC0zNiAzOS01OSA3LTQgMjIuNS0xMC41dDIyLjUtMTAuNXE0Ni0yNSA4Mi01MSAxMjMtOTEgMTkyLTIyN3Q2OS0yODktNjktMjg5LTE5Mi0yMjdxLTM2LTI2LTgyLTUxLTctNC0yMi41LTEwLjV0LTIyLjUtMTAuNXEtMzktMjMtMzktNTkgMC0yNiAxOS00NXQ0NS0xOXExMyAwIDI2IDUgMjExIDkxIDMzOCAyODMuNXQxMjcgNDIyLjV6IiBmaWxsPSIjZmZmIi8+PC9zdmc+",
    mute:       "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Im04MzIsMzQ4bDAsMTA4OHEwLDI2IC0xOSw0NXQtNDUsMTl0LTQ1LC0xOWwtMzMzLC0zMzNsLTI2MiwwcS0yNiwwIC00NSwtMTl0LTE5LC00NWwwLC0zODRxMCwtMjYgMTksLTQ1dDQ1LC0xOWwyNjIsMGwzMzMsLTMzM3ExOSwtMTkgNDUsLTE5dDQ1LDE5dDE5LDQ1eiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg==",
    commenton:  "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Im02NDAsNzkycTAsLTUzIC0zNy41LC05MC41dC05MC41LC0zNy41dC05MC41LDM3LjV0LTM3LjUsOTAuNXQzNy41LDkwLjV0OTAuNSwzNy41dDkwLjUsLTM3LjV0MzcuNSwtOTAuNXptMzg0LDBxMCwtNTMgLTM3LjUsLTkwLjV0LTkwLjUsLTM3LjV0LTkwLjUsMzcuNXQtMzcuNSw5MC41dDM3LjUsOTAuNXQ5MC41LDM3LjV0OTAuNSwtMzcuNXQzNy41LC05MC41em0zODQsMHEwLC01MyAtMzcuNSwtOTAuNXQtOTAuNSwtMzcuNXQtOTAuNSwzNy41dC0zNy41LDkwLjV0MzcuNSw5MC41dDkwLjUsMzcuNXQ5MC41LC0zNy41dDM3LjUsLTkwLjV6bTM4NCwwcTAsMTc0IC0xMjAsMzIxLjV0LTMyNiwyMzN0LTQ1MCw4NS41cS0xMTAsMCAtMjExLC0xOHEtMTczLDE3MyAtNDM1LDIyOXEtNTIsMTAgLTg2LDEzcS0xMiwxIC0yMiwtNnQtMTMsLTE4cS00LC0xNSAyMCwtMzdxNSwtNSAyMy41LC0yMS41dDI1LjUsLTIzLjV0MjMuNSwtMjUuNXQyNCwtMzEuNXQyMC41LC0zN3QyMCwtNDh0MTQuNSwtNTcuNXQxMi41LC03Mi41cS0xNDYsLTkwIC0yMjkuNSwtMjE2LjV0LTgzLjUsLTI2OS41cTAsLTE3NCAxMjAsLTMyMS41dDMyNiwtMjMzLjAwMDA3NnQ0NTAsLTg1LjUwMDMydDQ1MCw4NS41MDAzMnQzMjYsMjMzLjAwMDA3NnQxMjAsMzIxLjV6IiBmaWxsPSIjZmZmIi8+PC9zdmc+",
    commentoff: "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Im0xNzkyLDc5MnEwLDE3NCAtMTIwLDMyMS41dC0zMjYsMjMzdC00NTAsODUuNXEtNzAsMCAtMTQ1LC04cS0xOTgsMTc1IC00NjAsMjQycS00OSwxNCAtMTE0LDIycS0xNywyIC0zMC41LC05dC0xNy41LC0yOWwwLC0xcS0zLC00IC0wLjUsLTEydDIsLTEwdDQuNSwtOS41bDYsLTlsNywtOC41bDgsLTlxNywtOCAzMSwtMzQuNXQzNC41LC0zOHQzMSwtMzkuNXQzMi41LC01MXQyNywtNTl0MjYsLTc2cS0xNTcsLTg5IC0yNDcuNSwtMjIwdC05MC41LC0yODFxMCwtMTMwIDcxLC0yNDguNXQxOTEsLTIwNC41MDA3OTN0Mjg2LC0xMzYuNDk5Nzg2dDM0OCwtNTAuNDk5ODE3cTI0NCwwIDQ1MCw4NS40OTk2OHQzMjYsMjMzLjAwMDM4MXQxMjAsMzIxLjUwMDMzNnoiIGZpbGw9IiNmZmYiLz48L3N2Zz4=",
    fullscreen: "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTc5MiIgaGVpZ2h0PSIxNzkyIiB2aWV3Qm94PSIwIDAgMTc5MiAxNzkyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik04ODMgMTA1NnEwIDEzLTEwIDIzbC0zMzIgMzMyIDE0NCAxNDRxMTkgMTkgMTkgNDV0LTE5IDQ1LTQ1IDE5aC00NDhxLTI2IDAtNDUtMTl0LTE5LTQ1di00NDhxMC0yNiAxOS00NXQ0NS0xOSA0NSAxOWwxNDQgMTQ0IDMzMi0zMzJxMTAtMTAgMjMtMTB0MjMgMTBsMTE0IDExNHExMCAxMCAxMCAyM3ptNzgxLTg2NHY0NDhxMCAyNi0xOSA0NXQtNDUgMTktNDUtMTlsLTE0NC0xNDQtMzMyIDMzMnEtMTAgMTAtMjMgMTB0LTIzLTEwbC0xMTQtMTE0cS0xMC0xMC0xMC0yM3QxMC0yM2wzMzItMzMyLTE0NC0xNDRxLTE5LTE5LTE5LTQ1dDE5LTQ1IDQ1LTE5aDQ0OHEyNiAwIDQ1IDE5dDE5IDQ1eiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg=="
};
$v.controller.timeSeeker.isMoving   = false;
$v.controller.volumeSeeker.isMoving = false;


$v.comment = {};
$v.comment.list = [];
$v.comment.on = true;
$v.comment.laneNormalHeight = 25;
$v.comment.laneFullHeight   = Math.floor(screen.height * 0.8 / 12);


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
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    xhr.timeout = 20000;

    if(param instanceof FormData){
        var body = param;
    }
    else{
        var body = "";
        for(var key in param){
            if(!param.hasOwnProperty(key)){ continue; }
            body += encodeURIComponent(key) + "=" + encodeURIComponent(param[key]) + "&";
        }
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    }
    xhr.send(body);
};


$v.objectFit = function(screenW, screenH, objectW, objectH){
    var result  = {};
    var screenR = screenW / screenH;
    var objectR = objectW / objectH;

    if(screenR > 1){
        var scale = (objectR < screenR) ? screenH/objectH : screenW/objectW;
    }
    else{
        var scale = (objectR > screenR) ? screenW/objectW : screenH/objectH;
    }
    result.w = Math.floor(objectW * scale);
    result.h = Math.floor(objectH * scale);
    result.x = Math.floor((screenW / 2) - (result.w / 2));
    result.y = Math.floor((screenH / 2) - (result.h / 2));

    return result;
};


$v.video.fit = function(screenW, screenH, objectW, objectH){
    var pos = $v.objectFit(screenW, screenH, objectW, objectH);

    $v.video.setAttribute("width",  pos.w);
    $v.video.setAttribute("height", pos.h);
    $v.video.style.left = pos.x + "px";
    $v.video.style.top  = pos.y + "px";
};


$v.comment.release = function(comments, lane){
    var time = $v.video.currentTime;
    for(var i = 0; i < comments.length; i++){
        for(var j = 0; j < lane.length; j++){
            if(lane[j] === false){ continue; }

            var comment = document.createElement("span");
            comment.textContent = comments[i][0];
            comment.classList.add('comment');
            comment.setAttribute("data-lane", j);
            if($v.screen.isFullscreen()){
                comment.style.animationName = "fulllane";
                comment.style.top = $v.comment.laneFullHeight*j+$v.comment.laneFullHeight/6 + "px";
                comment.style.fontSize = $v.comment.laneFullHeight*0.9 + "px";
            }
            else{
                comment.style.animationName = "normallane";
                comment.style.top = $v.comment.laneNormalHeight*j+$v.comment.laneNormalHeight/6 + "px";
                comment.style.fontSize = $v.comment.laneNormalHeight*0.9 + "px";
            }
            //ディレイ計算
            var delay = comments[i][1] - time;
            delay = (delay <= 0) ? 0 : delay.toFixed(3)*1000; //ms変換
            comment.style.animationDelay = delay + "ms";

            $v.screen.insertBefore(comment, $v.screen.firstChild);
            lane[j] = false;
            break;
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
    var css = "";
    css += "@keyframes normallane{";
    css += "from{transform:translateX(0);}";
    css += "to{transform:translateX(-3200px);}}";
    document.styleSheets[0].insertRule(css, 0);

    css = "";
    css += "@keyframes fulllane{";
    css += "from{transform:translateX(0);}";
    css += "to{transform:translateX(-" + screen.width*5 + "px);}}";
    document.styleSheets[0].insertRule(css, 0);
};

$v.comment.laneCheck = function(){
    var comments = $v.screen.querySelectorAll(".comment");
    var lane = [true,true,true,true,true,true,true,true,true,true,true,true];

    for(var i = comments.length-1; i >= 0; i--){
        var comment_pos = comments[i].getBoundingClientRect();

        if(comment_pos.right+100 > $v.screen.pos.right){
            lane[comments[i].getAttribute("data-lane")] = false;
        }
        if(comment_pos.right < $v.screen.pos.left){
            $v.screen.removeChild(comments[i]);
        }
    }
    return lane;
};


$v.comment.get = function(){
    var id   = document.getElementById("comment-form-id").value;
    var path = document.getElementById("comment-form-path").value;
    var time = Math.floor($v.video.duration);
    var num  = time * 6; //コメント取得件数(num)

    //動画時間＋1の箱を作成 [[], [], [], ...]
    $v.comment.list = []; 
    for(var i = 0; i < time+1; i++){
        $v.comment.list.push([]);
    }

    var url = "?action=commentget" + "&id=" + id + "&path=" + path + "&num=" + num + "&nocache=" + Date.now();
    $v.get(url, function(xhr){
        try{ var comments = JSON.parse(xhr.responseText); } catch(e){ return; }

        for(var i = 0; i < comments.length; i++){
            var index = Math.floor(comments[i][1]/100);
            if(index >= 0 && index <= time){
                $v.comment.list[index].push(comments[i]);
            }
        }
    });
};

$v.comment.post = function(){
    var input = document.getElementById("comment-form-input");
    var text  = input.value.trim();
    var time  = $v.video.currentTime;

    if(text == "" || text.length > 64){ return; }
    
    var formdata = new FormData(document.getElementById("comment-form"));
    formdata.append("time", time.toFixed(2)*100);
    $v.post('?action=commentpost', formdata);

    if(Math.floor(time+1) in $v.comment.list){
        $v.comment.list[Math.floor(time+1)].unshift([text, time+1, Math.floor(Date.now()/1000)]);
    }

    input.value = "";
};


$v.controller.setSeeker = function(seekbar, seeker, percent){
    seekbar.pos = seekbar.getBoundingClientRect();
    seeker.pos  = seeker.getBoundingClientRect();
    var seekbarWidth = seekbar.pos.width - seeker.pos.width;

    //percentは「割合の時(0-1)」or「クリックされた位置の時」の2パターンある
    var pos = (percent <= 1) ? seekbarWidth*percent : percent-seekbar.pos.left;

    if(pos < 0){ pos = 0; }
    if(pos > seekbarWidth){ pos = seekbarWidth; }

    seeker.style.left = pos + "px";
    return pos/seekbarWidth;
};


$v.controller.setBuffer = function(){
    var seekbarWidth = $v.controller.timeSeekbar.getBoundingClientRect().width;
    var buffer = $v.video.buffered;

    if(buffer.length){
        var startPos = buffer.start(0) / $v.video.duration * seekbarWidth;
        var endPos   = buffer.end(buffer.length-1)  / $v.video.duration * seekbarWidth;
        
        $v.controller.timeSeekbar.style.backgroundPosition = startPos + "px";
        $v.controller.timeSeekbar.style.backgroundSize     = endPos + "px";
    }
};


$v.controller.setTime = function(time, element){
    var min = Math.floor(time / 60);
    var sec = Math.floor(time - min * 60);

    if(min < 10){ min = '0' + min; }
    if(sec < 10){ sec = '0' + sec; }

    element.textContent = min + ":" + sec;
};

$v.controller.timeSeeker.mousemoveEvent = function(event, seek){
    if(!$v.controller.timeSeeker.isMoving){ return; }
    var percent = $v.controller.setSeeker($v.controller.timeSeekbar, $v.controller.timeSeeker, event.clientX);
    if(seek){ $v.video.currentTime = $v.video.duration * percent; }
};

$v.controller.volumeSeeker.mousemoveEvent = function(event){
    if(!$v.controller.volumeSeeker.isMoving){ return; }
    $v.video.volume = $v.controller.setSeeker($v.controller.volumeSeekbar, $v.controller.volumeSeeker, event.clientX);
};


$v.screen.isFullscreen = function(){
    var element = document.fullscreenElement || document.msFullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement;
    return element ? true : false;
};

$v.screen.getFullscreenId = function(){
    var element = document.fullscreenElement || document.msFullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement;
    return element.id;
};

$v.screen.fullscreenEvent = function(){
    $v.screen.pos = $v.screen.getBoundingClientRect();

    if($v.screen.isFullscreen()){
        if($v.screen.getFullscreenId() != "video-screen"){ return; }
        $v.video.fit(screen.width, screen.height, $v.video.videoWidth, $v.video.videoHeight);
 
        $v.screen.appendChild($v.controller);
        var controller = $v.controller.getBoundingClientRect();
        $v.controller.style.top  = screen.height - controller.height + "px";
        $v.controller.style.left = (screen.width/2) - (controller.width/2)+ "px";
    }
    else{
        $v.video.fit($v.screen.pos.width, $v.screen.pos.height, $v.video.videoWidth, $v.video.videoHeight);

        $v.player.appendChild($v.controller);
        $v.controller.style.top  = 0;
        $v.controller.style.left = 0;
    }
    $v.comment.clear();
};




$v.video.addEventListener('loadedmetadata', function(){
    if(!$v.isNotPosted){
        $v.comment.get();
        $v.comment.laneBuild();
        document.getElementById("comment-form-input").disabled  = false;
        document.getElementById("comment-form-submit").disabled = false;
    }
    
    $v.controller.setBuffer();
    $v.controller.setSeeker($v.controller.volumeSeekbar, $v.controller.volumeSeeker, $v.video.volume);
    $v.controller.setTime($v.video.duration, $v.controller.timeTotal);

    $v.video.fit($v.screen.pos.width, $v.screen.pos.height, $v.video.videoWidth, $v.video.videoHeight);
});


$v.video.addEventListener('canplaythrough', function(){
    $v.video.play();
});


$v.video.addEventListener('timeupdate', function(){
    var sec_now = Math.floor($v.video.currentTime);
    if(sec_now !== $v.video.beforeTime){
        $v.controller.setTime(sec_now, $v.controller.timeCurrent);
        if(!$v.controller.timeSeeker.isMoving){
            $v.controller.setSeeker($v.controller.timeSeekbar, $v.controller.timeSeeker, sec_now/$v.video.duration);
        }
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
});

$v.video.addEventListener('dblclick', function(event){
    event.preventDefault();
});

$v.video.addEventListener('error', function(event){

    switch(event.target.error.code){ // http://www.html5.jp/tag/elements/video.html
        case MEDIA_ERR_SRC_NOT_SUPPORTED:
            alert("動画ファイルが存在しないか、未サポートの形式です");
            break;
        case MEDIA_ERR_DECODE:
            alert("動画ファイルが壊れているか、未サポートの形式です");
            break;
        case MEDIA_ERR_NETWORK:
            alert("動画のダウンロードが途中で失敗しました");
            break;
        case MEDIA_ERR_ABORTED:
            //alert("動画の再生が中止されました");
            break;
        default:
            alert("未知のエラーが発生しました");
            break;
    }
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

$v.controller.timeSeeker.addEventListener('mousedown', function(event){
    $v.controller.timeSeeker.isMoving = true;
    document.addEventListener('mousemove', $v.controller.timeSeeker.mousemoveEvent);
});

document.addEventListener('mouseup', function(event){
    $v.controller.timeSeeker.mousemoveEvent(event, true);
    $v.controller.timeSeeker.isMoving = false;
    document.removeEventListener('mousemove', $v.controller.timeSeeker.mousemoveEvent);
});


document.getElementById("controller-volume-seek").addEventListener('click', function(event){
    $v.video.muted = false;
    var percent = $v.controller.setSeeker($v.controller.volumeSeekbar, $v.controller.volumeSeeker, event.clientX);
    $v.video.volume = percent;
});


$v.controller.volumeSeeker.addEventListener('mousedown', function(event){
    $v.controller.volumeSeeker.isMoving = true;
    document.addEventListener('mousemove', $v.controller.volumeSeeker.mousemoveEvent);
});

document.addEventListener('mouseup', function(event){
    $v.controller.volumeSeeker.mousemoveEvent(event);
    $v.controller.volumeSeeker.isMoving = false;
    document.removeEventListener('mousemove', $v.controller.volumeSeeker.mousemoveEvent);
});


document.getElementById("controller-screen-toggle").addEventListener('click', function(){
    if(!$v.screen.isFullscreen()){
        if     ($v.screen.requestFullscreen)      { $v.screen.requestFullscreen(); }
        else if($v.screen.msRequestFullscreen)    { $v.screen.msRequestFullscreen(); }
        else if($v.screen.webkitRequestFullscreen){ $v.screen.webkitRequestFullscreen(); }
        else if($v.screen.mozRequestFullScreen)   { $v.screen.mozRequestFullScreen(); }
    }
    else{
        if     (document.exitFullscreen)        { document.exitFullscreen(); }
        else if(document.msExitFullscreen)      { document.msExitFullscreen(); }
        else if(document.webkitCancelFullScreen){ document.webkitCancelFullScreen(); }
        else if(document.mozCancelFullScreen)   { document.mozCancelFullScreen(); }
    }
});

document.addEventListener("fullscreenchange",      function(){ $v.screen.fullscreenEvent(); });
document.addEventListener("MSFullscreenChange",    function(){ $v.screen.fullscreenEvent(); });
document.addEventListener("webkitfullscreenchange",function(){ $v.screen.fullscreenEvent(); });
document.addEventListener("mozfullscreenchange",   function(){ $v.screen.fullscreenEvent(); });


document.getElementById("comment-form").addEventListener('submit', function(event){
    event.preventDefault();
    $v.video.play();
    $v.comment.post();
});

document.getElementById("comment-form-input").addEventListener('focus', function(event){
    $v.video.pause();
});



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
    border-radius: 0;
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
    border-radius: 0;
    line-height: 1;
    vertical-align: middle;
    font-family: 'MS PGothic', Meiryo, sans-serif;
}



.comment{
    font-family: 'MS PGothic', Meiryo, sans-serif;
    position: absolute;
    left: 100%;
    line-height: 1;
    z-index: 20;
    color: #fff;
    text-shadow: -1px -1px #333, 1px -1px #333,	-1px 1px #333, 1px 1px #333;
    animation-fill-mode: forwards;
    animation-timing-function: linear;
    animation-duration: 15s;
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
.video-screen:-moz-full-screen{
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
