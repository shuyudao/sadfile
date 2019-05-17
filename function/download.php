<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<?php
// error_reporting(0);
   //下载路径
echo "获取资源中...";
include "FileManger.php";
include '../config.php';
include 'policyManger.php';

if ($_GET['file_id']!='') {
	#通过get方式下载需要判断当前是否为登录用户

	islogin();
	$file_id = $_GET['file_id'];

	$sql_file = "SELECT * FROM files WHERE id = ".$file_id;
	$end = mysqli_fetch_assoc(mysqli_query($conn,$sql_file));
	$for_policy_id = $end['for_policy_id'];
	
	$policy_data = PolicyManger::getPolicydataBypolicyId($for_policy_id,$conn);

	//不同策略之间的无缝切换下载

	$auto = new FileManger($end['qiniu_name'],$policy_data);
	$downloadUrl = $auto->download($end['file_name']);

	echo "<script>window.location.href = '".$downloadUrl."';setTimeout(function(){window.close();},2000)</script>";
}else{


	$key = $_GET['share_key'];
	$pwd = $_GET['share_pwd'];
	if ($key!='') {
		# code...
		$sql_check = "SELECT * FROM share WHERE share_key = '$key' AND share_pwd = '$pwd'";
		$share = mysqli_fetch_assoc(mysqli_query($conn,$sql_check));
		if ($share==NULL) {
			die;
		}
		$temp_file_id = $share['file_id'];

		$sql_select_file = "SELECT * FROM files WHERE id = $temp_file_id";
		$result_temp = mysqli_fetch_assoc(mysqli_query($conn,$sql_select_file));
		
		$for_policy_id = $result_temp['for_policy_id'];

		$policy_data = PolicyManger::getPolicydataBypolicyId($for_policy_id,$conn);

		$auto = new FileManger($result_temp['qiniu_name'],$policy_data);
		$downloadUrl = $auto->download($result_temp['file_name']);

		$count_temp =  (int)$share['download_cont']+1;
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
