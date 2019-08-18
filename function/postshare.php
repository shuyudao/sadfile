<?php
// error_reporting(0);
include_once '../config.php';
include 'Response.php';
include 'function.php';
include_once 'DB.php';
islogin();


if (!isset($_POST['file_id'])) {
    die;
}

$file_id = $_POST['file_id']; //文件ID
$key = substr(md5(time()."xs"),8,16); //生成该文件独一的分享key

function randomkeys($length)
{
   $key = "";
   $pattern = '1234567890abcdefghijklmnopqrstuvwxyz   
               ABCDEFGHIJKLOMNOPQRSTUVWXYZ';
   for($i=0;$i<$length;$i++)
   {
       $key .= $pattern{mt_rand(0,35)};    //生成php随机数
   }
   return $key;
}   

$share_pwd = randomkeys(4);

for($i = 0 ;$i < count($file_id) ; $i++){
  $t_file_id = $file_id[$i];
  $sql_in = "INSERT into share (share_key,file_id,share_time,download_cont,share_pwd) VALUES ('$key','$t_file_id',NOW(),0,'$share_pwd');";
  $DB->update($sql_in);
}

exit($Response->success($data[0]['site_url'].'/fx.php?key='.$key."^^".$share_pwd));

?>