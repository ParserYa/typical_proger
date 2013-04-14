<?php

function xss($var){
    if(is_array($var)) {
        foreach($var as $k=>$v)
            $new[$k] = xss($v);
        return $new;
    }
    return htmlspecialchars(strip_tags($var));
}

function display_msg($type, $type_div, $msg) {
	 $output = '<div class="alert alert-block '.$type_div.' fade in"> <button type="button" class="close" data-dismiss="alert">&times;</button> <h4 class="alert-heading">'.$type.'!</h4> <p>'.$msg.'!</p>';
	return $output;
}

function display_msg2($type, $type_div, $msg) {
	 $output = '<div class="alert alert-block '.$type_div.' fade in"> <h4 class="alert-heading">'.$type.'!</h4> <p>'.$msg.'!</p>';
	return $output;
}

/* проверим, жив ли сервер */
function ping_db($connect) {
	if (mysqli_ping($connect)) {
		echo "Соединение в порядке!\n";
		} 
		else {
			echo "Ошибка: %s\n", mysql_error();
	}
}

# Функция для определения параметров изображения 
# Возвращает массив параметров если файл изображение и FALSE при ошибке 

function get_image_info($file = NULL) 
{ 
if(!is_file($file)) return false; 

if(!$data = getimagesize($file) or !$filesize = filesize($file)) return false; 

$extensions = array(1 => 'gif', 2 => 'jpg', 
3 => 'png', 4 => 'bmp'); 

$result = array('width'	 =>	$data[0], 
'height'	=>	$data[1], 
'extension'	=>	$extensions[$data[2]], 
'size'	 =>	$filesize, 
'mime'	 =>	$data['mime']); 

return $result; 
} 

function check_login() {
	if (@$_SESSION['access']!='allowed' or @$_SESSION['user']['ip']!=$_SERVER['REMOTE_ADDR']) {
		$a = false;
	}
	else {
		$a = true;
	}
	return $a;
}
    
		

?>
