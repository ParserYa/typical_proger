<?php
require_once "./bootstrap.php";

include(CLASS_UPLOAD);

//ping_db($connect);

// Нажата ли кнопка
if(!isset($_POST['upload'])) {
	//include_file(THEME_ADD);
	defined('_JEXEC') or die('Restricted access');
}
else {
	define( '_JEXEC', 1 );
}

     if(isset($_POST['text'])) {
		 // описание
         //$text = htmlspecialchars(stripslashes($_POST['desc']));
         // удаление html из тегов
         //$hashtag = htmlspecialchars($_POST['desc']);
         // выборка хештегов из текста
         //$hasgtag = preg_match_all('/#[^\s]+/', $_POST['desc'], $output);      
         $text = preg_replace("/[^\\w\\x7F-\\xFF\\s]+/s", "", $_POST['text']);
         $text = xss($text);
	 }
	 
	 @$size_file = $_FILES['filename']['size'];
	 
if ($size_file == 0) {
    echo display_msg("Ошибка", "alert-error", "Файл не выбран");
    exit;
}

//if(isset($_SESSION['moder'])) { $moderation = '1'; } else { $moderation = '0'; } // Если модер, то пост автоматически чекается

$file = $_FILES['filename']['name'];
// Вырезаем расширение картинки
@$ex = strtolower(array_pop(explode(".", $file)));
// Присваиваем картинке уникальное имя
$new_img_name = uniqid() . '.' . $ex;
$img_name = $_FILES['filename']['name'];
// Проверяем разрешение файла
if (isset($valid_extensions)) {
	$allowed = 0;
	foreach ($valid_extensions as $ext) {
		if(substr($img_name, (0 - (strlen($ext) + 1))) == ".".$ext) 
			$allowed = 1;
		}
		if ($allowed == 0) {
            echo display_msg('Ошибка', 'alert-error', 'Неверный формат изображения');
            exit;
		}
	}
// Определение путей для записи в базу
$file_up = UPLOAD_DIR.$new_img_name;
$file_thumb_up = UPLOAD_DIR_THUMB.$new_img_name;
//$file_adm = UPLOAD_DIR_ADM.$new_img_name;
// Защита от загрузки других файлов
if(!$image_info = get_image_info($_FILES['filename']['tmp_name']) or !in_array($image_info['extension'], $valid_extensions)) { 
	echo display_msg('Ошибка', 'alert-error', 'Это не картинка!');
    exit;
} 
else { 
    // Загрузка...
	if (move_uploaded_file($_FILES['filename']['tmp_name'], $file_up)) {
// Получаем пропорции картинки 
list($width, $height) = getimagesize($file_up); 
// Если ширина больше $thumb_size уменьшаем с пропорциями
if($width > $thumb_size) {
	$image = new SimpleImage();
    $image->load($file_up);
    $image->resizeToHeight($thumb_size);
    $image->save($file_thumb_up);
// Если высота больше $thumb_size уменьшаем с пропорциями    
    /*if($height > $thumb_size) {
		$image = new SimpleImage();
        $image->load($file_up);
        $image->resizeToHeight($thumb_size);
        $image->save($file_thumb_up);
	}*/
}
else {
// Если картинка подходит, не трогаем ее и присваиваем переменной $file_thumb_up обычную картинку
	$file_thumb_up = $file_up;
}
// Если картинка меньше $min_size по ширине или высоте, выдаем ошибку
if($width < $min_size || $height < $min_size) {
	echo display_msg('Ошибка', 'alert-error', 'Пикча маловата');
    exit;
}
//Уменьшаем картинку для показа в админке	
	/*$image = new SimpleImage();
    $image->load($file_up);
    $image->resizeToWidth(200);
    $image->resizeToHeight(200);
    $image->save($file_adm);*/
// установил кодировку, заебали неведомые знаки!11
mysql_set_charset('utf8_general_ci');
$insert = mysql_query("INSERT INTO post (id_post, text_post, img_large, img_mini, date_post) VALUE ('', '$text', '$file_up', '$file_thumb_up', NOW())") or die("duck! ".mysql_error()); // Запись в бд
$last_id = mysql_insert_id(); // последний id записаный в бд
/*foreach ($output[0] as $tag) { // из массива с тегами переносим в единое все и пишем через цикл все теги
$tag1 = preg_replace("/[^\\w\\x7F-\\xFF\\s]+/s", "", $tag);
$insert_tag = mysql_query("INSERT INTO hashtags (id_data, tags, check_tag) VALUE ('$last_id', '#$tag1', '$moderation')") or die("duck! ".mysql_error()); // Запись в бд
}*/
//$count_post = mysql_query("UPDATE mydaks SET check_post_num = check_post_num+1"); 
echo display_msg('Сообщение', 'alert-info', 'Сообщение добавлено на проверку');
	}
	else {
		echo display_msg('Ошибка', 'alert-error', 'При загрузке возникли какие-то неполадки');
		exit;
	}
}



?>


