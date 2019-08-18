<?php
include 'DB.php';
if (!session_id()) session_start();
#基本表数据
$sql_select = "SELECT * from policy INNER JOIN base ON policy.id = base.policy_id";
$data = $DB->query($sql_select);
if ($data==NULL) {
  $sql_select = "SELECT * from base";
  $data=$DB->query($sql_select);
}
#登录判断
function islogin() {
  global $data;
  $user = $_SESSION['user'];
  if ($user==null) {
  	header('Location:'.$data[0]['site_url'].'/login.html');
  }
}


function trans_byte($byte)

{

    $KB = 1024;

    $MB = 1024 * $KB;

    $GB = 1024 * $MB;

    $TB = 1024 * $GB;

    if ($byte < $KB) {

        return $byte . "B";

    } elseif ($byte < $MB) {

        return round($byte / $KB, 2) . "KB";

    } elseif ($byte < $GB) {

        return round($byte / $MB, 2) . "MB";

    } elseif ($byte < $TB) {

        return round($byte / $GB, 2) . "GB";

    } else {

        return round($byte / $TB, 2) . "TB";

    }
}

function getIp(){

    $ip='未知IP';

    if(!empty($_SERVER['HTTP_CLIENT_IP'])){

        return is_ip($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:$ip;

    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){

        return is_ip($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$ip;

    }else{

        return is_ip($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:$ip;

    }

}

function is_ip($str){

    $ip=explode('.',$str);

    for($i=0;$i<count($ip);$i++){ 

        if($ip[$i]>255){ 

            return false; 

        } 

    } 

    return preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/',$str); 

}

?>