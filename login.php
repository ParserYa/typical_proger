<?
error_reporting(E_ALL);
session_start();

include("bootstrap.php");

define("ACCESS","Allow"); 
	// уже авторизирован
	if (!empty($_SESSION['access']) and $_SESSION['access']=='allowed' and $_SESSION['user']['ip']==$_SERVER['REMOTE_ADDR']){
		header("Location: index.php");
	}
	// IP не совпадает
	elseif(!empty($_SESSION['access']) and $_SESSION['access']=='allowed'){
		header("Location: exit.php");
	}
	$_SESSION['login']='ok';
	//$message='';
	
	require_once 'class/obj_user.php';
	if (empty($user)) $user=new unuser;
	
	// Блокировка начало //
	$lock=false;
	if (!empty($_SESSION['ban'])){ 
		if ($_SESSION['ban']<time()){
			unset($_SESSION['ban']);
			$lock=false; 
		}
		else $lock=true;
	}
	elseif ($time=$user->ban_user()){ 
		$_SESSION['ban']=$time;
		$lock=true;
	}
	if ($lock){ echo display_msg("Ошибка", "alert-error", "Вы заблокированы!<br />You are blocked!"); }
	// Блокировка конец //
	
	if (!empty($_POST['pass']) and $_SESSION['login']=='ok' and $lock==false){
		if ($id_user=$user->login($_POST['login'],$_POST['pass']))
		{
			$_SESSION['access']='allowed';
			$_SESSION['user']['ip']=$_SERVER['REMOTE_ADDR'];
			$_SESSION['user']['id']=$id_user;
			unset($_SESSION['login']);
			$_SESSION['name_user'] = $_POST['login'];
			echo ("<script>location.reload(true);</script>");
			exit();
		} 
		else 
		echo display_msg("Ошибка", "alert-error", "Вход не осуществлен!<br />Осталось ".$user->num_point() . " попыток"); 
		unset ($id_user);
	}
?>
