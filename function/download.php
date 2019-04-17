<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<?php
error_reporting(0);
   //下载路径
echo "获取资源中...";
require '../qiniu/autoload.php'; #引入七牛
use Qiniu\Auth; #鉴权类

if ($_GET['file_id']!='') {
	#通过get方式下载需要判断当前是否为登录用户
	include '../config.php';
	include 'function.php';
	islogin();
	$file_id = $_GET['file_id'];

	#本次查询目的：获取数据库中的七牛端的文件名称
	$sql_file = "SELECT * FROM files WHERE id = ".$file_id;
	$end = mysqli_fetch_assoc(mysqli_query($conn,$sql_file));
	// var_dump($end);

	// 用于签名的公钥和私钥
	$accessKey = $data[0]['qiniu_AK']; #公
	$secretKey = $data[0]['qiniu_SK']; #私
	//空间名
	$bucket = $data[0]['qiniu_name'];
	// 初始化签权对象
	$auth = new Auth($accessKey, $secretKey);
	//token过期时间
	$expires = 3600; #1小时
	// 私有空间中的外链 http://<domain>/<file_key>
		#对其处理attname
	$baseUrl = $data[0]['qiniu_url'].'/'.$end['qiniu_name']."?attname=".urlencode($end['file_name']);
	// 对链接进行签名
	$downloadUrl = $auth->privateDownloadUrl($baseUrl); #签名意味着授权该下载连接
	echo "<script>window.location.href = '".$downloadUrl."';setTimeout(function(){window.close();},2000)</script>";
}else{
	#游客下载
	include '../config.php';
	include 'function.php';

	$key = $_GET['share_key'];
	$pwd = $_GET['share_pwd'];
	if ($key!='') {
		# code...
		$sql_check = "SELECT * FROM share WHERE share_key = $key AND share_pwd = $pwd";
		$share = array();
		$share[]  = mysqli_fetch_assoc(mysqli_query($conn,$sql_check));
		$temp_file_id = $share[0]['file_id'];
		$sql_select_file = "SELECT * FROM files WHERE id = $temp_file_id";
		$result_temp = mysqli_fetch_assoc(mysqli_query($conn,$sql_select_file));
		$share[0]['file_path'] = $result_temp['qiniu_name'];
		$file_name = $result_temp['file_name'];
		$qiniu_name = $share[0]['file_path'];
			// 用于签名的公钥和私钥
		$accessKey = $data[0]['qiniu_AK']; #公
		$secretKey = $data[0]['qiniu_SK']; #私
		//空间名
		$bucket = $data[0]['qiniu_name'];
		// 初始化签权对象
		$auth = new Auth($accessKey, $secretKey);
		//token过期时间
		$expires = 3600; #1小时
		// 私有空间中的外链 http://<domain>/<file_key>
			#对其处理attname
		$baseUrl = $data[0]['qiniu_url'].'/'.$qiniu_name."?attname=".urlencode($file_name);
		// 对链接进行签名
		$downloadUrl = $auth->privateDownloadUrl($baseUrl); #签名意味着授权该下载连接
		$count_temp =  (int)$share[0]['download_cont']+1;
		$sql_update = "UPDATE share SET download_cont = '$count_temp' WHERE share_key = ".$key;
		if (mysqli_query($conn,$sql_update)) {
			echo "<script>window.location.href = '".$downloadUrl."';setTimeout(function(){window.close();},5000)</script>";
		}else{
			echo "下载失败";
		}
	}else{
		echo "参数为空";
	}

}

?>
