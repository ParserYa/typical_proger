<?php
session_start();

//include('config.php');

define("ROOT", realpath(dirname(__FILE__)));

require_once ROOT . "/class/_database.php";
MySql::GetConnection();

//require_once ROOT . "/config.php";
require_once ROOT . "/func.php";

define("STYLE", ROOT . "/style");
define("UPLOAD_DIR", 'up/');

define("UPLOAD_DIR_THUMB", UPLOAD_DIR.'thumb/');
define("CLASS_UPLOAD", ROOT.'/class/classSimpleImage.php');

if(isset($_GET['v']) && $_GET['v'] != '') {
	$v = $_GET['v'];
	if(is_array($v))die('Хакер?');
	$v = preg_replace("/[^\\w\\x7F-\\xFF\\s]+/s", "", $v);
	xss($v);
	$query = mysql_query("SELECT * FROM post WHERE id_post = '" . mysql_real_escape_string($v) . "'");
	$view = mysql_fetch_assoc($query);
	require_once STYLE . "/header.html";
	require_once STYLE . "/view.html";
	require_once STYLE . "/footer.html";
	exit;
}

if(isset($_GET['about'])) {
	$v = $_GET['about'];
	if(is_array($v))die('Хакер?');
	require_once STYLE . "/header.html";
	require_once STYLE . "/about.html";
	require_once STYLE . "/footer.html";
	exit;
}

if(empty($_GET) && empty($_POST)) {
	require_once STYLE . "/header.html";
	$query = mysql_query("SELECT * FROM post ORDER BY id_post DESC");
	require_once STYLE . "/main.html";
	require_once STYLE . "/footer.html";
}

if(isset($_GET['exit'])) {
	session_start();
    session_unset();
    session_destroy();
    setcookie('PHPSESSID','delete', time()-1);
    header("location:index.php");
    exit();
}

?>
