<?php
include '../../config.php';
include '../function.php';
islogin();
$open = fopen("./login.php", "r") or die("Unable to open file!");
fgets($open);
fgets($open);
$arr = null;
$i = 0;
echo "<h2>登录日志</h2>";
while($log = fgets($open)){
	$arr[$i] = $log."<br>";
	$i++;
}

for($i = count($arr)-1;$i>=0;$i--){
	echo $arr[$i];
}

?>