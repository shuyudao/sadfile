<?php
error_reporting(0);
ini_set('date.timezone','Asia/Shanghai');
include '../config.php';
include 'function.php';
include 'FileManger.php';
islogin();

if($_GET['file_name']!=''){
  $filename = $_GET['file_name']; #用户识别文件名
  $filesize = trans_byte($_GET['fszie']); #文件大小
  $partent_dir_key = $_COOKIE['dir_key']; #父级目录名 通过key来寻找准确的目录
  $qiniu_name = $_GET['qiniu_name']; #存储与七牛的文件名
  $file_size_num = $_GET['fszie'];
  $policy_id = $data[0]['policy_id'];
  $sql_insert_info = "INSERT into files (file_name,file_size,file_time,partent_dir_key,qiniu_name,file_size_num,for_policy_id) VALUES ('$filename','$filesize',CURDATE(),'$partent_dir_key','$qiniu_name','$file_size_num','$policy_id');";

  if (mysqli_query($conn,$sql_insert_info)) {
      echo "success";
  }else{
      echo mysqli_error();
  }

}else{

  $res = "";
  $sql_get_info = "SELECT * from policy INNER JOIN base ON policy.id = base.policy_id";
  $row = mysqli_fetch_assoc(mysqli_query($conn,$sql_get_info));

  $auto = new FileManger("",$row);
  $res = $auto->getUploadToken();
 
  $json = json_encode($res);
  exit($json);
}


?>
