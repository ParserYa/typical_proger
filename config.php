<?php
//session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);

$db = "typical_proger";
$host = "localhost";
$user = "root";
$pass = "Nhfvfljk3000";

$charset = 'utf-8';

$title = 'Типичный программист';

$thumb_size = '450';

$min_size = 100;

$valid_extensions = array('gif', 'jpg', 'png', 'GIF', 'JPG', 'PNG'); 

//////////////////////////////////////////////////////////////////////////////
if(!defined('DB')) define('DB', $db);
if(!defined('HOST')) define('HOST', $host);
if(!defined('USER_DB')) define('USER_DB', $user);
if(!defined('PASS_DB')) define('PASS_DB', $pass);



//DB//
/*$connect = mysql_connect(HOST, USER_DB, PASS_DB) or die("fuck ==> ".mysql_error());

if($connect) {
mysql_select_db(DB, $connect) or die("fuck ==> ".mysql_error());
}*/
//DB\\
/*mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER SET 'utf8'");*/

header('Content-Type: text/html; charset='.$charset.'');
?>
