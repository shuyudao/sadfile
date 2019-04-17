<meta charset="utf-8">
<?php
include 'config.php';
$user = $_POST['user'];
$pwd = md5($_POST['pwd'].'xxrs!./');

// $islogin = $_COOKIE['logined'];\
$islogin = md5($user.$pwd);
$conn = mysqli_connect($server,$dbuser,$dbpwd,$dbname);

$sql_select = "SELECT user_name,user_pwd FROM base;";
$sql = mysqli_query($conn,$sql_select);
$data = array();
$data[]  = mysqli_fetch_assoc($sql);
$mysql_login = md5($data[0]['user_name'].$data[0]['user_pwd']);
if ($islogin==$mysql_login) {
	SetCookie("islogin", $islogin ,time()+36000,'/');
	SetCookie("islogin", $islogin ,time()+36000,'/page');
	$ip = $_SERVER['REMOTE_ADDR'].'';
	$sql_login = "UPDATE base SET login_time = NOW(),login_ip = '$ip';";
	if (mysqli_query($conn,$sql_login)) {
		header('Location:'.$data[0]['site_url'].'/page/main.php');
	}
	
}else{
	header('Location:'.$data[0]['site_url'].'index.html');
}


?>