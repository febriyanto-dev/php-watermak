<?php

class Watermark {

    var $watermark_path;
    var $watermark_img;
    var $watermark_file;

    public function __construct() {

        $this->watermark_path = public_path('img/');
        $this->watermark_img = 'watermark.png';

        $this->watermark_file = $this->watermark_path.$this->watermark_img;
    }

    public function create($path,$file,$position,$prefix=null)
    {
        $old_file = public_path('storage/'.$path).$file;

        $arr_file = pathinfo($old_file);

        $new_file = public_path('storage/'.$path).$prefix.$arr_file['filename'];

        $mime_file = mime_content_type($old_file);

        if (file_exists($old_file)) {

            list($owidth,$oheight) = getimagesize($old_file);
            $width = $owidth;
            $height = $oheight;    
            $im = imagecreatetruecolor($width, $height);

            $bgcolor = imagecolorallocate($im, 255, 255, 255);
            imagefill($im, 0, 0, $bgcolor);

            switch($mime_file) {
                case "image/gif":
                    $img_src = @imagecreatefromgif($old_file); 
                    $ext = '.gif';
                    break;
                case "image/pjpeg":
                case "image/jpeg":
                case "image/jpg":
                    $img_src = @imagecreatefromjpeg($old_file); 
                    $ext = '.jpg';
                    break;
                case "image/png":
                case "image/x-png":
                    $img_src = @imagecreatefrompng($old_file); 
                    $ext = '.png';
                    break;
            }

            imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);

            $watermark = @imagecreatefrompng($this->watermark_file);
            
            list($w_width, $w_height) = getimagesize($this->watermark_file);    
                
            $pos_x = $width/2 - $w_width/2; 
            $pos_y = $height/2 - $w_height/2;

            // pengecekan untuk posisi watermak yang dipilih
            if ($position == "Pojok Kiri Atas") {
                imagecopy($im, $watermark, 0, 0, 0, 0, $w_width, $w_height);
            } 
            else if ($position == "Pojok Kanan Atas") {
                imagecopy($im, $watermark, $pos_x, 0, 0, 0, $w_width, $w_height);
            } 
            else if ($position == "Pojok Kanan Bawah") {
                imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
            } 
            else if ($position == "Tengah") {
                imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
            } 

            imagejpeg($im, $new_file . $ext, 100);

            imagedestroy($im);

            unlink($old_file);

            return $new_file;
        }
        
        return false;
    }

}