<?php
/*
* Name: Script for resizing images by link.
* Description: The script will quickly change the size of any image formats from external sites and save photos to your site.
* Author: Sergey Knyazew
* Author site: http://iksweb.ru/
* Author E-mail: info@iksweb.ru
*/
?>
<html>
	<head>
		<title>Зазгрузка фото по ссылке</title>
	</head>
	<body>
		<div class="content">
			<h1>Зазгрузка фото по ссылке</h1>
			<p>Для загрузки необходимо к текущему адресу добавить размеры и адрес картинки:</p>
			<code>http://<?=$_SERVER['SERVER_NAME'];?>/resize/[size]/[url]</code>
			<br>
			<p><b>[size]</b> - Обязательный парметр - размер картинки</p>
			<p><b>[url]</b> - Обязательный парметр - ссылка на картинку</p>
			<br>
			<p>Пример:</p>
			<code>http://<?=$_SERVER['SERVER_NAME'];?>/resize/800x800/https://iksweb.ru/upload/home/site.png</code>
			<p>Для закачки изображений из соц. сетей:</p>
			<code>https://<?=$_SERVER['SERVER_NAME'];?>/resize/333x332/https://sun9-65.userapi.com/impg/c858120/v858120967/1d9654/8AnHY7CBXZk.jpg?size=245x226&quality=96&sign=0d04aa7ccc0b862eeec30e62dd0f770a&type=album</code>
			<br><br>
			<hr>
			<p>Присылайте ваши вопросы на info@iksweb.ru</p>
		</div>
		
		<style>
			*{box-sizing: border-box; }
			body,html{padding:0;margin:0;    font-family: sans-serif;}
			.content { background: #f9fafb; width: 100%; padding: 30px; max-width: 1000px; margin: 50px auto 0; }
			code{ background: #dedede; padding: 5px 10px; line-height: 22px;}
			p{font-size: 14px;}
		</style>
	</body>
</html>