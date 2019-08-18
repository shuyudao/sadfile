<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<?php
// error_reporting(0);
   //下载路径
echo "获取资源中...";
include_once "../config.php";
include "FileManger.php";
include 'policyManger.php';
include_once 'DB.php';

if (isset($_GET['file_id'])&&!isset($_GET['key'])) {
	#站内下载

	islogin();
	$file_id = $_GET['file_id'];

	$sql_file = "SELECT * FROM files WHERE id = ".$file_id;
	$end = $DB->query($sql_file);
	$for_policy_id = $end[0]['for_policy_id'];
	
	$policy_data = PolicyManger::getPolicydataBypolicyId($for_policy_id,$DB);

	//不同策略之间的无缝切换下载

	$auto = new FileManger($end[0]['qiniu_name'],$policy_data[0]);
	$downloadUrl = $auto->download($end[0]['file_name']);

	echo "<script>window.location.href = '".$downloadUrl."';setTimeout(function(){window.close();},5000)</script>";
}else{

	$key = $_GET['key'];
	$pwd = $_GET['pwd'];
	$file_id = $_GET['file_id'];
	if ($key!='') {
		# code...
		$sql_check = "SELECT * FROM share WHERE share_key = '$key' AND share_pwd = '$pwd' AND file_id = '$file_id'";
		$share = $DB->query($sql_check);
		if ($share==NULL) {
			die;
		}
		$temp_file_id = $file_id;

		$sql_select_file = "SELECT * FROM files WHERE id = $temp_file_id";
		$result_temp = $DB->query($sql_select_file);
		
		$for_policy_id = $result_temp[0]['for_policy_id'];

		$policy_data = PolicyManger::getPolicydataBypolicyId($for_policy_id,$DB);

		$auto = new FileManger($result_temp[0]['qiniu_name'],$policy_data[0]);
		$downloadUrl = $auto->download($result_temp[0]['file_name']);

		$count_temp =  (int)$share[0]['download_cont']+1;

		$sql_update = "UPDATE share SET download_cont = '$count_temp' WHERE file_id = ".$file_id;
		if ($DB->update($sql_update)) {
			echo "<script>window.location.href = '".$downloadUrl."';setTimeout(function(){window.close();},5000)</script>";
		}else{
			echo "下载失败";
		}
	}else{
		echo "参数为空";
	}

}

?>
