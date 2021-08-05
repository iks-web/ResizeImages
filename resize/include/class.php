<?php
/*
* Name: Script for resizing images by link.
* Description: The script will quickly change the size of any image formats from external sites and save photos to your site.
* Author: Sergey Knyazew
* Author site: http://iksweb.ru/
* Author E-mail: info@iksweb.ru
*/

class ResizeImages{

    /* var */
    var $image;
    var $image_type;

    /* private */
    public $arParams = [];

    /* Init Class  */
    public function __construct()
    {

        // default params
        $this->arParams = array(
            'ROOT'			=> $_SERVER['DOCUMENT_ROOT'], // Site ROOT DIR
            'PAGE'			=> $this->GetCurPage(), // Page URL
            'QUERU' 		=> $this->GetCurPageParam(), // Page URL Query
            'SOURCE'		=> $this->GetCurSource(),// The source image
            'IMAGES_PARAMS' => $this->GetResizeParams(),
        );

    }

    /*
    Clearing the page URL

    @param true/false show get params
    @return string $url
    */
    private function GetCurPage($no_get=false)
    {

        $url = preg_replace('/[^\/\?\&\=\-\:а-яА-Яa-zA-Z0-9\.\w]+/iu', '', $_SERVER['REQUEST_URI']);

        if($no_get==true)
            $url = explode("?", $url, 2)[0];

        return $url;
    }

    /*
    Clearing the GET page parameters

    @param N/A
    @return string $url
    */
    private function GetCurPageParam()
    {
        $url  =  $this->GetCurPage();
        $urlParts  =  explode("?", $url, 2);
        if(count($urlParts) == 2 && $urlParts[1] <> '')
        {
            $arParams = explode("&", $urlParts[1]);
            foreach($arParams as $i => $value)
            {
                $name_dir = explode("=", $value, 2);
                $arResult[trim($name_dir[0])] = trim($value);
            }
            return "?".implode("&", $arResult);
        }
        return $url;
    }

    /*
    Creating an array with the resize settings

    @param N/A
    @return array $arResult
   */
    private function GetResizeParams()
    {
        global $arParams;

        $url  =  $this->GetCurPage(true);

        $arPageSettigs = array_diff(explode('/', $url), array(''));

        // Clearing the array of unnecessary data
        unset($arPageSettigs[array_search('resize',$arPageSettigs)]);
        unset($arPageSettigs[array_search('https:',$arPageSettigs)]);
        unset($arPageSettigs[array_search('http:',$arPageSettigs)]);

        foreach($arPageSettigs as $params){

            // filename
            if(mb_strpos($params, '.png')!==false || mb_strpos($params, '.jpg')!==false || mb_strpos($params, '.jpeg')!==false || mb_strpos($params, '.bmp')!==false){

                $pathinfo = pathinfo($params);

                $arResult['FILE'] = $pathinfo['basename'];
                $arResult['FILE_TYPE'] = $pathinfo['extension'];
                $arResult['FILE_NAME'] = $pathinfo['filename'];
            }

            // size
            if(preg_match("/[0-9][x][0-9]/",$params)){
                $size = explode('x', $params);
                $arResult['SIZE'] = $params;
                $arResult['WIDTH'] = intval($size[0]);
                $arResult['HEIGHT'] = intval($size[1]);
            }

        }

        $arResult['FILE_RESIZE_NAME'] = md5($arResult['FILE_NAME'].$arResult['SIZE']).'.'.$arResult['FILE_TYPE'];

        return $arResult;
    }

    /*
    Determining the source of the photo

    @param N/A
    @return array $matches
   */
    private function GetCurSource()
    {
        $url  =  $this->GetCurPage();

        // check ssl
        if(mb_strpos($url, 'https:')!==false){
            $ssl = 'https://';
        }else{
            $ssl = 'http://';
        }

        preg_match_all('/(http:\/\/|https:\/\/)?(www)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w\.\--\?\%\&]*)*\/?/i', $url, $matches);

        if(isset($matches[0][0]))
            return $matches[0][0];

        return false;
    }

    /*
    Show Images page
   */
    public function ShowImages($filename=false)
    {
        $file = !empty($filename)? $filename : $this->arParams['FILE_DIR'];
        $this->load($file);
        $this->resize($this->arParams['IMAGES_PARAMS']['WIDTH'], $this->arParams['IMAGES_PARAMS']['HEIGHT']);
        $this->output();
    }

    /*
    Save Images site
   */
    public function SaveImages()
    {

        if(!isset($this->arParams['IMAGES_PARAMS']['FILE_TYPE']))
            return false;

        $this->save($this->arParams['SOURCE'],$this->arParams['FILE_DIR']);
    }

    /*
    Uploading an image to the class

    @param string $filename - File name
    @return N/A
   */
    private function load($filename)
    {

        $image_info = getimagesize($filename);
        $this->image_type = !empty($image_info)? $image_info[2] : false;

        if(empty($this->image_type) && $this->arParams['SHOW_STUB']=='Y'){

            $filename = $this->arParams['ROOT'].'/resize/include/no-image.png';

            $image_info = getimagesize($filename);
            $this->image_type = $image_info[2];
        }

        if( $this->image_type == IMAGETYPE_JPEG ) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif( $this->image_type == IMAGETYPE_GIF ) {
            $this->image = imagecreatefromgif($filename);
        } elseif( $this->image_type == IMAGETYPE_PNG ) {
            $this->image = imagecreatefrompng($filename);
        }

    }

    /*
     Save the specified image

     @param string $link - Link to download the image
     @param string $save - The directory for saving the image
     @return N/A
    */
    private function save($link,$save)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch,CURLOPT_URL,$link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result=curl_exec($ch);
        curl_close($ch);
        $savefile = fopen($save, 'w');
        fwrite($savefile, $result);
        fclose($savefile);
    }

    /*
    We display an image on the screen

    @param string $image_type - Type images
    @return N/A
   */
    private function output($image_type=IMAGETYPE_JPEG)
    {
        if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image);
        } elseif( $image_type == IMAGETYPE_GIF ) {
            imagegif($this->image);
        } elseif( $image_type == IMAGETYPE_PNG ) {
            imagepng($this->image);
        }
    }

    /*
     Getting the size of the image

     @param N/A
     @return string - width images
    */
    private function getWidth()
    {
        return imagesx($this->image);
    }

    /*
     Getting the size of the image

     @param N/A
     @return string - height images
    */
    private function getHeight()
    {
        return imagesy($this->image);
    }

    /*
     Change the size of the photo

     @param string - height images
     @return N/A
    */
    private function resizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width,$height);
    }

    /*
    Change the size of the photo

    @param string - width images
    @return N/A
   */
    private function resizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width,$height);
    }

    /*
    Change the size of the photo

    @param string - width images
    @param string - height images
    @return N/A
   */
    private function resize($width,$height)
    {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }
}
?>
