<?php //if (!defined('ACCESS')) exit('Deny access!');
/*
** Файл: obj_user.php
** Описание: Управление пользователями
** Зависимость: _database.php
** Версия: 2.4 (public)
** Создано: 14.08.2012
** Автор: Soroka Andrei
** Э/почта: unick@live.com
**
** Copyright by AndreiSoroka.com
** All rights reserved.
*/

class unuser
{
 // база
 private $db;
 
 // Окончание работы класса
 function __destruct() {
    if ($this->db)
	{ 
		mysql_close();
	}
 }
 
 // Соединение с базой данных
 protected function db_connect(){
    if (!$this->db)
	{
		require_once '_database.php';
		MySql::GetConnection();
		$this->db=true;
	}
 }

 // Хэш пароля (после sha1 из js)
 protected function hash_pass($text){
  $text=md5(sha1($text)."solo".md5(sha1($text).$text{1}));
  $text=md5($text{7}.$text.$text{0});
  return($text); 
 }
 
 // Проверка и подготовка логина
 protected function modify_login($login){
  	if (!empty($login)){
	$login=trim($login);
	if (preg_match("%^([_\.\-]?[a-zA-Z0-9])+$%", $login))
	 {
	  if (strlen($login)>=3 and  strlen($login)<=40)
	  {
	   return ($login);
	  }
	 }
	} 
	unset($login);
	return (false);
 }
 
 // Проверка и подготовка пароля
 protected function modify_password($pass){
  	if (!empty($pass)){
	if (preg_match("/[a-zA-Z0-9]/", $pass))
		{
			if (strlen($pass)==40)
			{
				$pass=$this->hash_pass($pass);
				return ($pass);
			}
		}
	} 
	unset($pass);
	return (false);
 }
 
 // Добавление блокировки
  protected function ban_user_add(){
	$this->db_connect();
	$time=time()+15*60; // время блокировки
	$ban_query = mysql_query("SELECT * FROM `ban` WHERE `ip` = '{$_SERVER['REMOTE_ADDR']}'");
	$ban_array = mysql_fetch_array($ban_query);
	$mysql_num_rows=mysql_num_rows($ban_query);
	// ранее был разблокирован
	if ($mysql_num_rows !=0 and $ban_array['time']<time()) 
	{
		mysql_query("UPDATE `ban` SET `time`='$time', `number` = 1 WHERE `ip` ='{$_SERVER['REMOTE_ADDR']}';");
	}
	// ранее был заблокирован
	elseif ($mysql_num_rows !=0) 
	{
		$number=$ban_array['number']+1;
		mysql_query("UPDATE `ban` SET `time`='$time', `number` = '$number' WHERE `ip` ='{$_SERVER['REMOTE_ADDR']}';");
	}
	// первая блокировка
	else
	{
		mysql_query("INSERT INTO `ban` (`ip` ,`time`, `number`, `comment`) VALUES ('{$_SERVER['REMOTE_ADDR']}', '{$time}', 1, 'Блокировка при авторизации');");
		unset($time);
	}
  }
  
  // Проверка ip на блокировку
 public function ban_user(){
	$this->db_connect();
	$ban_query = mysql_query("SELECT `time` FROM `ban` WHERE `ip` = '{$_SERVER['REMOTE_ADDR']}' AND `time` > '".time()."'  AND `number`>4");
  	if (mysql_num_rows($ban_query)<1){
		unset($ban_query);
		return (false);
	}
	$ban_query=mysql_fetch_array($ban_query);
	return ($ban_query['time']);
 }
 
 public function num_point() {
	 $this->db_connect();
	 $num_point = mysql_query("SELECT `number` FROM `ban` WHERE `ip` = '{$_SERVER['REMOTE_ADDR']}'");
	 if(mysql_num_rows($num_point) < 1) {
		 unset($num_point);
		 return (false);
	 }
	 $num_point = mysql_fetch_assoc($num_point);
	 $num_point_reverse =  5 - $num_point['number'];
	 return ($num_point_reverse);
 }
 
 // Авторизация
 public function login($user, $pass){
	$user=$this->modify_login($user);
	$pass=$this->hash_pass($pass);
	if (empty($user) or empty($pass)) { $this->ban_user_add(); return(false); }
	$this->db_connect();
	$user_query = mysql_query("SELECT * FROM `users` WHERE `login` = '$user' AND `password` = '$pass'");
	if (mysql_num_rows($user_query) == 1) // Авторизировался
	{
		$dbData=mysql_fetch_array($user_query);
		mysql_query ("DELETE FROM `ban` WHERE `ip` = '{$_SERVER['REMOTE_ADDR']}'");	
		return ($dbData['id']);
	}
	else
	{
		$this->ban_user_add();
	}
	return(false);	
 }

}

?>
