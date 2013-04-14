<?php
if(!empty($_POST) && !isset($_POST['id'])) { die("faggot!"); }

include "../config.php";
require_once "../func.php";

$connect = mysql_connect(HOST, USER_DB, PASS_DB) or die("fuck ==> ".mysql_error());

if($connect) {
mysql_select_db(DB, $connect) or die("fuck ==> ".mysql_error());
}

$ip = $_SERVER['REMOTE_ADDR'];

$id = $_POST['id'];

xss($id);

$get_rate = mysql_query("SELECT ip_add FROM image_IP WHERE img_id_fk = '$id' and ip_add = '$ip'");

$count = mysql_num_rows($get_rate);

if($count == 0) {
	 $update_plus = mysql_query("UPDATE post SET like_post = like_post + 1 WHERE id_post = '$id'");
     $paste_rate = mysql_query("INSERT INTO image_IP (ip_add, img_id_fk) VALUES ('$ip', '$id')");
     $result_plus = mysql_query("SELECT like_post FROM post WHERE id_post='$id'");
     $view_rate_plus = mysql_fetch_assoc($result_plus);
     ?>
     <i class="icon-heart"></i><span align="left"><?=$view_rate_plus['like_post']?></span>
     <?php
 }
 else {
     $update_minus = mysql_query("UPDATE post SET like_post = like_post - 1 WHERE id_post = '$id'");
     $result_minus = mysql_query("SELECT like_post FROM post WHERE id_post = '$id'");
     $get_like = mysql_fetch_assoc($result_minus);
     $love = $get_like['like_post'];
     mysql_query("DELETE FROM image_IP WHERE img_id_fk = '$id'");
     ?>
     <i class="icon-heart"></i><span align="left"><?php echo $love; ?></span>
     <?php
 }
?>
