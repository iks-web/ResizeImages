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

$RImages = new ResizeImages; // init class

$RImages->arParams['SAVE_DIR'] = '/resize/images/'; // Дериктория для сохранения картинок
$RImages->arParams['SHOW_STUB'] = 'Y'; // Отображать ли картинку заглушки для отсутсвующих ищображений

// Дериктория для сохранения
$RImages->arParams['FILE_DIR'] = $RImages->arParams['ROOT'].$RImages->arParams['SAVE_DIR'].$RImages->arParams['IMAGES_PARAMS']['FILE_RESIZE_NAME'];

// Show images
if (file_exists($RImages->arParams['FILE_DIR'])) {

    http_response_code(200);  // Set 200

    $RImages->ShowImages(); // Показываем

}else{

    http_response_code(404); // Set 404

    $RImages->SaveImages(); // Сохраняем
    $RImages->ShowImages(); // Показываем

}
?>
