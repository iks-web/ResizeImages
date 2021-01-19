<?php
/*
* Name: Script for resizing images by link
* Description: The script will quickly change the size of any image formats from external sites and save photos to your site.
* Author: Sergey Knyazew
* Author site: http://iksweb.ru/
* Author E-mail: info@iksweb.ru
*/

header("Content-type: image/png; text/html; charset=utf-8;");
require __DIR__ . '/include/class.php';

/*
== Using == 

https://yoursite.com/[dir]]/[size]]/[link]

https://yoursite.com/resize/800x800/https://yoursite.com/images.png


== Settings ==

You can change the user settings for the individual operation of the script

*/
$arParams = array(
	// default
	'ROOT'	=> __DIR__, // Site ROOT DIR
	'PAGE'	=> $APPLICATION->GetCurPage(), // Page URL
	'QUERU' => $APPLICATION->GetCurPageParam(), // Page URL Query
	'SOURCE'=> $APPLICATION->GetCurSource(),// The source image
	
	
	// User Settings
	'SAVE_DIR' => '/images/', // Folder to save image resizes to (Relative to the main folder)
	'SHOW_STUB' => 'Y', // Y / N - Display the stub when there is no image
	);
	
$arParams['IMAGES_PARAMS'] = $APPLICATION->GetResizeParams();

$arParams['FILE_DIR'] = $arParams['ROOT'].$arParams['SAVE_DIR'].$arParams['IMAGES_PARAMS']['FILE_RESIZE_NAME'];

// Show images
if (file_exists($arParams['FILE_DIR'])) {
	
	// Set 200
	http_response_code(200);
	
	$APPLICATION->load($arParams['FILE_DIR']);
	$APPLICATION->resize($arParams['IMAGES_PARAMS']['WIDTH'], $arParams['IMAGES_PARAMS']['HEIGHT']);
	$APPLICATION->output();
	
}else{
	
	// Set 404
	http_response_code(404);
	
	$APPLICATION->save( $arParams['SOURCE'] , $arParams['FILE_DIR'] );
	
	// We output the absence of an image
	if($arParams['SHOW_STUB']=='Y'){
		$APPLICATION->load($arParams['ROOT'].'/include/no-image.png');
		$APPLICATION->resize($arParams['IMAGES_PARAMS']['WIDTH'], $arParams['IMAGES_PARAMS']['HEIGHT']);
	    $APPLICATION->output();
	}
	
}
?>