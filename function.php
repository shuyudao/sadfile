<?php
include 'config.php';
$conn = mysql_connect($server,$dbuser,$dbpwd);
$sql_use = "use ".$dbname;
mysql_query($sql_use);
mysql_query("set character set 'utf8'");//读库 
mysql_query("set names 'utf8'");//写库
#基本表数据
$sql_select = "SELECT * FROM base;";
$sql = mysql_query($sql_select);
$data = array();
$data[]  = mysql_fetch_assoc($sql);

#登录判断
function islogin() {
  $mysql_login = md5($GLOBALS['data'][0]['user_name'].$GLOBALS['data'][0]['user_pwd']);
  $cookie = $_COOKIE['islogin'];
  if ($mysql_login!=$cookie) {
  	header('Location:'.$data[0]['site_url'].'index.html');
  }
}
function getBrowser(){
  $agent=$_SERVER["HTTP_USER_AGENT"];
  if(strpos($agent,'MSIE')!==false || strpos($agent,'rv:11.0')) //ie11判断
   return "ie";
  else if(strpos($agent,'Firefox')!==false)
   return "firefox";
  else if(strpos($agent,'Chrome')!==false)
   return "chrome";
  else if(strpos($agent,'Opera')!==false)
   return 'opera';
  else if((strpos($agent,'Chrome')==false)&&strpos($agent,'Safari')!==false)
   return 'safari';
  else
   return 'unknown';
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
?>