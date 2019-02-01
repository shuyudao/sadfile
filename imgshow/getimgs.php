<?php
error_reporting(0);
ini_set('date.timezone','Asia/Shanghai');
include '../config.php';
include '../function.php';
require '../qiniu/autoload.php'; #引入七牛
use Qiniu\Auth;  #鉴权类
if ($data[0]['tulang_state']!='0') { #如果图廊没有关闭
  if ($data[0]['tulang_open']=='0') { #是否公开
    islogin(); #判断是否登录
  }
}else{ #图廊关闭
  header('Location: ../');
  exit;
}
// islogin(); #判断是否登录

#七牛云资源下载签名
function qiniu_img($file_name,$ak,$sk,$url){
    $accessKey = $ak;
    $secretKey = $sk;
    // 构建Auth对象
    $auth = new Auth($accessKey, $secretKey);
    // 私有空间中的外链 http://<domain>/<file_key>
    $baseUrl = $url.'/'.$file_name;
    // 对链接进行签名
    $signedUrl = $auth->privateDownloadUrl($baseUrl);
    return $signedUrl;
}


if($_GET['start']!=''){
  $start = $_GET['start'];
  $key = $data[0]['tulang_dir'];
  $SQL = "SELECT * FROM files WHERE (file_name like '%jpg' OR file_name like '%png' OR file_name like '%jpeg' OR file_name like '%gif') AND partent_dir_key = $key LIMIT $start,12";
  $res = mysql_query($SQL);
  echo mysql_error();
  $end = array();
  while ($row = mysql_fetch_assoc($res)) {
    $row['link'] = qiniu_img($row['qiniu_name'],$data[0]['qiniu_AK'],$data[0]['qiniu_SK'],$data[0]['qiniu_url']);
    $end[] = $row;
  }
  $b = array('result' => $end);
  $json = json_encode($b);
  exit($json);
}

if ($_GET['count']!='') {
  $key = $data[0]['tulang_dir'];
  $SQL = "SELECT count(*) AS nums FROM files WHERE (file_name like '%jpg' OR file_name like '%png' OR file_name like '%jpeg' OR file_name like '%gif') AND partent_dir_key = $key";
  $res = mysql_query($SQL);

  echo mysql_fetch_assoc($res)['nums'];
}



?>
