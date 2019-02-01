<meta charset="utf-8">
<?php
include 'config.php';
$user = $_POST['user'];
$pwd = md5($_POST['pwd'].'xxrs!./');

// $islogin = $_COOKIE['logined'];\
$islogin = md5($user.$pwd);
$conn = mysql_connect($server,$dbuser,$dbpwd);
$sql_use = "use ".$dbname;
mysql_query($sql_use);
$sql_select = "SELECT user_name,user_pwd FROM base;";
$sql = mysql_query($sql_select);
$data = array();
$data[]  = mysql_fetch_assoc($sql);
$mysql_login = md5($data[0]['user_name'].$data[0]['user_pwd']);
if ($islogin==$mysql_login) {
	SetCookie("islogin", $islogin ,time()+36000);
		$ip = $_SERVER['REMOTE_ADDR'].'';
	$sql_login = "UPDATE base SET login_time = NOW(),login_ip = '$ip';";
	if (mysql_query($sql_login)) {
		header('Location:'.$data[0]['site_url'].'main.php');
	}
	
}else{
	header('Location:'.$data[0]['site_url'].'index.html');
}


?>