<?php
error_reporting(0);
include '../config.php';
include 'function.php';
require '../qiniu/autoload.php'; #引入七牛
use Qiniu\Auth;  #鉴权类
islogin();
#修改提取密码
if ($_POST['key']!='') {
	if ($_POST['newpwd']!='') {
		$key = $_POST['key'].'';
		$pwd = $_POST['newpwd'].'';
		$sql_change = "UPDATE share SET share_pwd = '$pwd' WHERE share_key = ".$key;
		if (mysqli_query($conn,$sql_change)) {
			echo "修改成功";
		}else{
			echo $_POST['newpwd'];
			echo mysqli_error();
		}
	}else{
		$key = $_POST['key'].'';
		$sql_delete = "DELETE FROM share WHERE share_key = ".$key;

		if (mysqli_query($conn,$sql_delete)) {
			echo "取消成功";
		}else{
			echo "取消失败";
		}
	}
}else if ($_POST['nico']!='') { #修改用户密码
	$nico = $_POST['nico'];
	$user = $_POST['user'];
	$pwd = md5($_POST['pwd'].'xxrs!./');
	$sql_update_user = "UPDATE base SET user_nico = '$nico',user_name = '$user',user_pwd = '$pwd';";
	if (mysqli_query($conn,$sql_update_user)) {
		echo "更新成功";
	}else{
		echo "更新失败";
	}
}else if($_POST['site_url']!=''){  #系统设置
	$site_url = $_POST['site_url'];
	$site_name = $_POST['site_name'];
	$site_des = $_POST['site_des'];
	$qiniu_url = $_POST['qiniu_url'];
	$qiniu_ak = $_POST['qiniu_ak'];
	$qiniu_sk = $_POST['qiniu_sk'];
	$qiniu_name = $_POST['qiniu_name'];
	$share_pwd = $_POST['share_pwd'];
	$update_max_size = $_POST['update_max_size'];
	$tulang_state = $_POST['tulang_state'];
	$tulang_open = $_POST['tulang_open'];
	$tulang_dir = $_POST['tulang_dir'];
	if ($site_url!=''&&$site_name!='') {
		$sql_setting = "UPDATE base SET tulang_state = '$tulang_state',tulang_open = '$tulang_open',tulang_dir = '$tulang_dir',site_url = '$site_url',site_name = '$site_name',qiniu_url = '$qiniu_url',qiniu_AK = '$qiniu_ak',qiniu_SK = '$qiniu_sk',qiniu_name = '$qiniu_name',site_des = '$site_des',share_pwd = '$share_pwd',max_upload = '$update_max_size';";
		if (mysqli_query($conn,$sql_setting)) {
			echo "修改成功";
		}else{
			echo "修改失败";
		}
	}
}else if ($_POST['isshare']!='') {  #分享状态设置
	$temp_share = $data[0]['isshare'];
	$as = 0;

	if ($temp_share!=1) {
		$as = 1;
	}else{
		$as = 0;
	}
	$sql_share = "UPDATE base SET isshare = '$as';";
	if (mysqli_query($conn,$sql_share)) {
		echo "修改成功";
	}else{
		echo "修改失败";
	}
}

#修改文件名或目录名

if ($_GET['id']!=''&&$_GET['newname']!='') {
	$id = $_GET['id'];
	$newname = $_GET['newname'];
	if ($_GET['dir']=='1') {
		$sql_update_name = "UPDATE files SET file_name = '$newname' WHERE id = '$id'";
	}else{
		$sql_update_name = "UPDATE dirs SET dir_name = '$newname' WHERE id = '$id'";
	}

	if (mysqli_query($conn,$sql_update_name)) {
		echo "success";
	}else{
		echo "filed";
	}
}

#获取新的目录
if ($_GET['new_dir_key']!='') {
	$sql_get_dirs_temp = "SELECT * FROM dirs WHERE partent_dir_key = ".$_GET['new_dir_key'];
	$sql_get_dirs_temp_arr = mysqli_query($conn,$sql_get_dirs_temp);
	$arr = array();
	while ($row = mysqli_fetch_assoc($sql_get_dirs_temp_arr)) {
		$arr[] = $row;
	}
	$end = array(
		'result' => $arr
	);
	$json = json_encode($end);
	exit($json);
}

//移动文件
if ($_GET['myid']!='') {
		$file_id = $_GET['myid'];
		$target = $_GET['target'];
		$attr = $_GET['attr'];
		$sql_change_dir;
		if ($attr == '1') {
			$sql_change_dir = "UPDATE dirs SET partent_dir_key = $target WHERE id = ".$file_id;
		}else{
			$sql_change_dir = "UPDATE files SET partent_dir_key = $target WHERE id = ".$file_id;
		}

		if (mysqli_query($conn,$sql_change_dir)) {
			echo "success";
			// echo $sql_change_dir;
		}else{
			echo mysqli_error();
		}

}

//文件列表
if ($_GET['get_list']!='') {
	$sql_get_dirs_temp = "SELECT * FROM dirs WHERE partent_dir_key = ".$_GET['get_list'];
	$sql_get_files_temp = "SELECT * FROM files WHERE partent_dir_key = ".$_GET['get_list'];
	$sql_get_dirs_temp_arr = mysqli_query($conn,$sql_get_dirs_temp);
	$sql_get_files_temp_arr = mysqli_query($conn,$sql_get_files_temp);
	$dirs_arr = array();
	$files_arr = array();
	while ($row = mysqli_fetch_assoc($sql_get_dirs_temp_arr)) {
		$dirs_arr[] = $row;
	}
	while ($row = mysqli_fetch_assoc($sql_get_files_temp_arr)) {
		$files_arr[] = $row;
	}
	$end = array(
		'dirs' => $dirs_arr,
		'files' => $files_arr
	);
	$json = json_encode($end);
	exit($json);
}

if ($_GET['qiniu_name_qm']!='') {

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

	echo qiniu_img($_GET['qiniu_name_qm'],$data[0]['qiniu_AK'],$data[0]['qiniu_SK'],$data[0]['qiniu_url']);
}

//创建目录
$mkdir_name = $_GET['mkdir_name'];
if ($mkdir_name!='') {
	$partent_dir_key = $_COOKIE['dir_key'];
	$this_dir_key = time()."";
	$mkdir_name = urldecode($mkdir_name);
	$sql_add_dir = "INSERT into dirs (dir_name,dir_time,dir_key,partent_dir_key) VALUES ('$mkdir_name',CURDATE(),$this_dir_key,'$partent_dir_key');";
	if(mysqli_query($conn,$sql_add_dir)){
		echo "success";
	}else{
		echo mysqli_error();
	}
}

//文件搜索
$search_word = $_GET['search_word'];
if ($search_word!='') {
	$sql_get_file = "SELECT * FROM files WHERE file_name like '%".$search_word."%'";
	$file_arr = array();

	$end = mysqli_query($conn,$sql_get_file);
	while ($row = mysqli_fetch_assoc($end)) {
		$file_arr[] = $row;
	}
	$json = json_encode($file_arr);
	exit($json);

	echo mysqli_error();
}
exit();
?>
