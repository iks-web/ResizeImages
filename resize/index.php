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
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
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
			<h2>Пример:</h2>
			<code>http://<?=$_SERVER['SERVER_NAME'];?>/resize/800x800/https://iksweb.ru/upload/home/site.png</code>
			<p>Для закачки изображений из соц. сетей:</p>
			<code>https://<?=$_SERVER['SERVER_NAME'];?>/resize/333x332/https://sun9-65.userapi.com/impg/c858120/v858120967/1d9654/8AnHY7CBXZk.jpg?size=245x226&quality=96&sign=0d04aa7ccc0b862eeec30e62dd0f770a&type=album</code>
			<br><br>
			
			<h2>Форма с применением класса:</h2>
			
			<form action="#" method="post" id="data">
				<input type="text" name="url" placeholder="Ссылка на URL" value="https://iksweb.ru/upload/home/site.png"/>
				<input type="text" name="wight" placeholder="Ширина" value="200" style="width: 90px; text-align: center;"/>
				<input type="text" name="height" placeholder="Высота" value="200" style="width: 90px; text-align: center;"/>
				<button type="submit" id="play" class="btn btn-start">Сгенерировать</button>
			</form>
			<script>
			function getFileNameFromUrl(url) {
			  return decodeURI(url.split('/').pop());
			}
			jQuery(document).on('click','#play',function() {
				
				var url = $('#data input[name="url"]').val();
				var wight = $('#data input[name="wight"]').val();
				var height = $('#data input[name="height"]').val();
				var size = wight+'x'+height;
				
				var host = location.protocol+"//"+location.host;
				var get_url_img = host+'/resize/images/'+size+'/'+url;

				$.ajax({
			        url: get_url_img,
			        type: "POST",
			        data: $(data).serialize(),
			        success: function() {
			        	console.log('ОК');
			        	var img = '<img src="'+get_url_img+'">';
			        	var img_url = '<p>Ссылка на URL</p><input type="text" value="'+get_url_img+'" >';
			        	var download = '<a style="text-decoration: none; color: #4e6982; margin: 20px 0; display: block;" href="'+get_url_img+'" download>Скачать</a>';
			        	
			        	$('.result').html(img + img_url + download);
			        	
			        },
			        error: function() {
			            console.log('Error');
			        }
			    });
			    
			    return false;
			});	
			</script>
			
			<div class="result"></div>
			<hr>
			<p>Присылайте ваши вопросы на info@iksweb.ru</p>
		</div>
		<br><br>
		<style>
			*{box-sizing: border-box; }
			body,html{padding:0;margin:0;    font-family: sans-serif;}
			.content { background: #f9fafb; width: 100%; padding: 30px; max-width: 1000px; margin: 20px auto 0; }
			code{ background: #dedede; padding: 5px 10px; line-height: 22px;}
			p{font-size: 14px;}
			input[type=text], input[type=tel], input[type=password], input[type=email], textarea { text-align: left; border: 1px solid #c6d7d9; border-radius: 3px; width: 492px;background-color: #fff; font-size: 14px; font-weight: 500; font-family: 'Exo 2',sans-serif; height: 35px; padding: 0 11px; }
			.btn-start { color: #fff!important; background: #4e6982; }
			.btn { box-sizing: border-box; transition: color 0.2s ease,background 0.2s ease; -webkit-transition: color 0.2s ease,background 0.2s ease; -moz-transition: color 0.2s ease,background 0.2s ease; -o-transition: color 0.2s ease,background 0.2s ease; padding: 0 15px; font-weight: 300; text-transform: inherit; text-decoration: none!important; text-align: center; border: none; outline: none; border-radius: 3px; height: 34px; display: inline-block; transition: all 0.2s; cursor: pointer; }
		
		</style>
	</body>
</html>
