<?php
error_reporting(0);
ini_set('date.timezone','Asia/Shanghai');
include 'config.php';
include 'function.php';
islogin();
require 'qiniu/autoload.php'; #引入七牛
use Qiniu\Auth;  #鉴权类
use Qiniu\Storage\BucketManager;
// 引入上传类
use Qiniu\Storage\UploadManager;

if($_GET['file_name']!=''){
  $filename = $_GET['file_name']; #用户识别文件名
  $filesize = trans_byte($_GET['fszie']); #文件大小
  $partent_dir_key = $_COOKIE['dir_key']; #父级目录名 通过key来寻找准确的目录
  $qiniu_name = $_GET['qiniu_name']; #存储与七牛的文件名
  $file_size_num = $_GET['fszie'];
  $sql_insert_info = "INSERT into files (file_name,file_size,file_time,partent_dir_key,qiniu_name,file_size_num) VALUES ('$filename','$filesize',CURDATE(),'$partent_dir_key','$qiniu_name','$file_size_num');";

  if (mysql_query($sql_insert_info)) {
      echo "success";
  }else{
      echo mysql_error();
  }
}else{
  // 用于签名的公钥和私钥
  $accessKey = $data[0]['qiniu_AK']; #公'WMOuej-P_84LO4bKrsuNSstudJ0BOMBOCbAFLtmz'
  $secretKey = $data[0]['qiniu_SK']; #私'hfAtmg3TPm4cf1qJfql8Qa7f_1u3dB78HsSjmpgM';
  //空间名
  $bucket = $data[0]['qiniu_name'];
  // 初始化签权对象
  $auth = new Auth($accessKey, $secretKey);
  //token过期时间
  $expires = 3600; #1小时
  //自定义上传回复的凭证 返回的数据
  $returnBody = '{"key":"$(key)","hash":"$(etag)","fsize":$(fsize),"name":"$(x:name)"}';
  $policy = array(
      'returnBody' => $returnBody,
      'callbackBodyType' => 'application/json'
  );

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
  $str = randomkeys(4);

  // 生成上传Token
  $UPtoken = $auth->uploadToken($bucket, null, $expires, $policy, true);

  $res = array('token' => $UPtoken, 'domain' => $data[0]['qiniu_url']);
  $json = json_encode($res);
  exit($json);
}


?>
