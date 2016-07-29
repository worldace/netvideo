<?php



$html = function($url){
    return <<<"━━━"
<p>投稿を受け付けました。<br><a href="$url">画面を切り替える</a>までしばらくお待ち下さい</a></p>
━━━;
};


$js = function($url){
    return <<<"━━━"
window.onload = function(){ setTimeout(function(){ location.href = "$url"; }, 0); };
━━━;
};
