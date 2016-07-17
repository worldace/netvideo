<?php
//画像::PNGリサイズ(["入力"=>"star.png", "出力"=>"star2.png", "横"=>100, "縦"=>100]);


class 画像{

    public static function PNGリサイズ(array $設定){
        if(preg_match("/^\x89PNG/", $設定['入力'])){ $元画像  = @imagecreatefrompng("data:;base64,". base64_encode($設定['入力'])); }
        else{ $元画像  = @imagecreatefrompng($設定['入力']); }
        if(!$元画像){ return false; }

        $新画像  = imagecreatetruecolor($設定['横'], $設定['縦']);

        imagealphablending($新画像, false); // アルファブレンディングをoffにする
        imagesavealpha($新画像, true);      // 完全なアルファチャネル情報を保存するフラグをonにする

        imagecopyresampled($新画像, $元画像, 0, 0, 0, 0, $設定['横'], $設定['縦'], imagesx($元画像), imagesy($元画像));

        if($設定['出力']){
            imagepng($新画像, $設定['出力']);
            $result = is_file($設定['出力']) ? $設定['出力'] : false;
        }
        else{
            ob_start();
            imagepng($新画像);
            $result = ob_get_contents();
            ob_end_clean();
        }
        imagedestroy($元画像);
        imagedestroy($新画像);

        return $result;
    }

    public static function JPGリサイズ(array $設定){
        if(preg_match("/^\xFF\xD8/", $設定['入力'])){ $元画像  = @imagecreatefromjpeg("data:;base64,". base64_encode($設定['入力'])); }
        else{ $元画像  = @imagecreatefromjpeg($設定['入力']); }
        if(!$元画像){ return false; }

        $新画像  = imagecreatetruecolor($設定['横'], $設定['縦']);

        imagecopyresampled($新画像, $元画像, 0, 0, 0, 0, $設定['横'], $設定['縦'], imagesx($元画像), imagesy($元画像));

        if($設定['出力']){
            imagejpeg($新画像, $設定['出力']);
            $result = is_file($設定['出力']) ? $設定['出力'] : false;
        }
        else{
            ob_start();
            imagejpeg($新画像);
            $result = ob_get_contents();
            ob_end_clean();
        }
        imagedestroy($元画像);
        imagedestroy($新画像);

        return $result;
    }

    public static function JPEGリサイズ(array $設定){
        return JPGリサイズ($設定);
    }

    public static function GIFリサイズ(array $設定){
        if(preg_match("/^GIF8[79]a/", $設定['入力'])){ $元画像  = @imagecreatefromgif("data:;base64,". base64_encode($設定['入力'])); }
        else{ $元画像  = @imagecreatefromgif($設定['入力']); }
        if(!$元画像){ return false; }

        $新画像  = imagecreatetruecolor($設定['横'], $設定['縦']);

        $alpha = imagecolortransparent($元画像); // 元画像から透過色を取得する
        imagefill($新画像, 0, 0, $alpha);        // その色でキャンバスを塗りつぶす
        imagecolortransparent($新画像, $alpha);  // 塗りつぶした色を透過色として指定する

        imagecopyresampled($新画像, $元画像, 0, 0, 0, 0, $設定['横'], $設定['縦'], imagesx($元画像), imagesy($元画像));

        if($設定['出力']){
            imagegif($新画像, $設定['出力']);
            $result = is_file($設定['出力']) ? $設定['出力'] : false;
        }
        else{
            ob_start();
            imagegif($新画像);
            $result = ob_get_contents();
            ob_end_clean();
        }
        imagedestroy($元画像);
        imagedestroy($新画像);

        return $result;
    }
}