<?php

class Uploader {

    var $path;
    var $file;
    var $file_lokasi;
    var $file_mime;
    var $vfile_upload;
    var $im_src;
    var $src_width;
    var $src_height;
    var $path_watermark;

    public function __construct($path,$file) {

        $this->path = storage_path('app/public/'.$path.'/');
        $this->file = $file;
        $this->file_lokasi = $this->path.$this->file;
        $this->file_mime = mime_content_type($this->file_lokasi);
    }

    public function save_thumb($isWidth=300,$isHeight=300,$prefix='thumb_')
    {
        $this->identitas_file();

        if($isWidth && $isHeight){
            $thumb_width = $isWidth;
            $thumb_height = $isHeight;
        }
        else{
            if($this->src_width == $this->src_height){
                $thumb_width = $this->src_width;
                $thumb_height = $this->src_height;
            } 
            elseif($this->src_width < $this->src_height) {
                $thumb_width = $this->src_width;
                $thumb_height = $this->src_width;
            } 
            elseif($this->src_width > $this->src_height) {
                $thumb_width = $this->src_height;
                $thumb_height = $this->src_height;
            } 
            else {
                $thumb_width = $isWidth;
                $thumb_height = $isHeight;
            }
        }
        
        $original_aspect = $this->src_width / $this->src_height;
        $thumb_aspect = $thumb_width / $thumb_height;
         
           if ( $original_aspect >= $thumb_aspect ) {
         
              // If image is wider than thumbnail (in aspect ratio sense)
              $new_height = $thumb_height;
              $new_width = $this->src_width / ($this->src_height / $thumb_height);
         
           }
           else {
              // If the thumbnail is wider than the image
              $new_width = $thumb_width;
              $new_height = $this->src_height / ($this->src_width / $thumb_width);
           }
         
           $im = imagecreatetruecolor($thumb_width, $thumb_height);
         
           // Resize and crop
           imagecopyresampled($im,
                $this->im_src,
                  0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
                  0 - ($new_height - $thumb_height) / 2, // Center the image vertically
                  0, 0,
                  $new_width, $new_height,
                  $this->src_width, $this->src_height);
         
            //Simpan gambar
            switch($this->file['tipe']) {
                case "image/pjpeg":
                case "image/jpeg":
                case "image/jpg":
                    imagejpeg($im,$this->path . "/thumb/" . $prefix . $this->file['nama_unik']);
                    break;
                case "image/png":
                case "image/x-png":
                    imagepng($im,$this->path . "/thumb/" . $prefix . $this->file['nama_unik']);
                    break;
            }

            //Hapus gambar di memori komputer
            imagedestroy($this->im_src);
            imagedestroy($im);
    }

    public function save_width($isWidth=300,$prefix='resize_')
    {
        $this->identitas_file();

        //Set ukuran gambar hasil perubahan
        $dst_width2 = $isWidth;
        $dst_height2 = ($dst_width2/$this->src_width)*$this->src_height;

        //proses perubahan ukuran
        $im2 = imagecreatetruecolor($dst_width2,$dst_height2);
        imagecopyresampled($im2, $this->im_src, 0, 0, 0, 0, $dst_width2, $dst_height2, $this->src_width, $this->src_height);

        //Simpan gambar
        switch($this->file_mime) {
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                imagejpeg($im2,$this->path . $prefix . $this->file);
                break;
            case "image/png":
            case "image/x-png":
                imagepng($im2,$this->path . $prefix . $this->file);
                break;
        }

        //Hapus gambar di memori komputer
        imagedestroy($this->im_src);
        imagedestroy($im2);
    }

    private function identitas_file()
    {
        //identitas file asli
        switch($this->file_mime) {
            case "image/gif":
                $this->im_src = @imagecreatefromgif($this->file_lokasi); 
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                $this->im_src = @imagecreatefromjpeg($this->file_lokasi); 
                break;
            case "image/png":
            case "image/x-png":
                $this->im_src = @imagecreatefrompng($this->file_lokasi); 
                break;
        }

        $this->src_width = imageSX($this->im_src);
        $this->src_height = imageSY($this->im_src);
    }

    public function watermark($posisinya='Tengah')
    {
        $this->path_watermark = '../../../assets/img/watermark.png';

        move_uploaded_file($this->file['lokasi'], $this->path.$this->file['nama']);

        $oldimage_name= $this->path.$this->file['nama'];
        $new_image_name = $this->path.$this->file['nama_unik'];
        
        list($owidth,$oheight) = getimagesize($oldimage_name);
        $width = $owidth;
        $height = $oheight;    
        $im = imagecreatetruecolor($width, $height);

        switch($this->file['tipe']) {
            case "image/gif":
                $img_src = imagecreatefromgif($oldimage_name); 
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                $img_src = imagecreatefromjpeg($oldimage_name); 
                break;
            case "image/png":
            case "image/x-png":
                $img_src = imagecreatefrompng($oldimage_name); 
                break;
        }

        imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);

        $watermark = imagecreatefrompng($this->path_watermark);
        
        list($w_width, $w_height) = getimagesize($this->path_watermark);    
            
        $pos_x = $width - $w_width; 
        $pos_y = $height - $w_height;

        // pengecekan untuk posisi watermak yang dipilih
        if ($posisinya == "Pojok Kiri Atas") {
            imagecopy($im, $watermark, 0, 0, 0, 0, $w_width, $w_height);
        } 
        else if ($posisinya == "Pojok Kanan Atas") {
            imagecopy($im, $watermark, $pos_x, 0, 0, 0, $w_width, $w_height);
        } 
        else if ($posisinya == "Pojok Kanan Bawah") {
            imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
        } 
        else if ($posisinya == "Tengah") {
            $pos_x = $width/2 - $w_width/2; 
            $pos_y = $height/2 - $w_height/2;
            imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
        } 

        switch($this->file['tipe']) {
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                imagejpeg($im, $new_image_name, 100);
                break;
            case "image/png":
            case "image/x-png":
                imagepng($im, $new_image_name, 100);
                break;
        }

        imagedestroy($im);
        unlink($oldimage_name);
        return $new_image_name;
    }   

    public function delete_real()
    {
        unlink($this->file_lokasi);
    }
}