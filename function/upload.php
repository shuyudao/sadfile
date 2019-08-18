<?php
//error_reporting(0);
ini_set('date.timezone','Asia/Shanghai');
include_once '../config.php';
include 'function.php';
include 'FileManger.php';
include_once 'DB.php';
if (isset($_GET['key'])){
  $arr = explode("^^",base64_decode($_GET['key']));
  $sql = "select ak from policy WHERE domain like '%".$arr[0]."%'";
  $data_e = $DB->query($sql);
  if (md5($data_e[0]['ak'])!=$arr[1]){
      echo "非法访问";
      die;
  }
}else{
  islogin();
}

if(isset($_GET['file_name'])){

  $filename = $_GET['file_name']; #用户识别文件名
  $filesize = trans_byte($_GET['fszie']); #文件大小
  $partent_dir_key = $_GET['parent_dir_key']; #父级目录名 通过key来寻找准确的目录
  $qiniu_name = $_GET['qiniu_name']; #存储与七牛的文件名
  $file_size_num = $_GET['fszie'];
  $policy_id = $data[0]['policy_id'];
  $sql_insert_info = "INSERT into files (file_name,file_size,file_time,partent_dir_key,qiniu_name,file_size_num,for_policy_id) VALUES ('$filename','$filesize',NOW(),'$partent_dir_key','$qiniu_name','$file_size_num','$policy_id');";

  if ($DB->update($sql_insert_info)) {
      echo "success";
  }else{
      echo "error";
  }

}else{

  $res = "";
  $sql_get_info = "SELECT * from policy INNER JOIN base ON policy.id = base.policy_id";
  $row = $DB->query($sql_get_info);
  $auto = new FileManger("",$row[0]);
  $res = $auto->getUploadToken();
 
  $json = json_encode($res);
  exit($json);
}


?>
