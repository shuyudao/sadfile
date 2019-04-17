<?php
error_reporting(0); 
include '../config.php';
include 'function.php';
islogin();
#连接数据库
$conn = mysqli_connect($server,$dbuser,$dbpwd,$dbname);

$file_id = $_POST['file_id']; //文件ID
if ($file_id!='') {
    $sql_search = "SELECT * FROM files WHERE id = '$file_id';";
    $v = mysqli_fetch_assoc(mysqli_query($conn,$sql_search));
    $key = time().''; //生成该文件独一的分享key
}

function randomkeys($length)   
{   
   $pattern = '1234567890abcdefghijklmnopqrstuvwxyz   
               ABCDEFGHIJKLOMNOPQRSTUVWXYZ';  
    for($i=0;$i<$length;$i++)   
    {   
        $key .= $pattern{mt_rand(0,35)};    //生成php随机数   
    }   
    return $key;   
}   
$share_pwd = randomkeys(4);

$sql_in = "INSERT into share (share_key,file_id,share_time,download_cont,share_pwd) VALUES ('$key','$file_id',CURDATE(),0,'$share_pwd');";
if (mysqli_query($conn,$sql_in)) {
	echo $data[0]['site_url'].'/page/fx.php?key='.$key."^^".$share_pwd;
}else{
	echo "分享失败";
}

?>