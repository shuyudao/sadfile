<?php
/**
 * Policy上传策略管理
 */
include '../config.php';
include 'function.php';

class PolicyManger
{
	
	function __construct()
	{
		# code...
	}

	public static function getPolicydataByfileId($file_id,$conn){

		$sql_file = "SELECT * FROM files WHERE id = ".$file_id;
		$end = mysqli_fetch_assoc(mysqli_query($conn,$sql_file));
		$for_policy_id = $end['for_policy_id'];
		$sql_get_policy = "SELECT * FROM policy WHERE id = '$for_policy_id'";
		$policy_data = mysqli_fetch_assoc(mysqli_query($conn,$sql_get_policy));

		return $policy_data;
	}

	public static function getPolicydataBypolicyId($policy_id,$conn){
		$sql_get_policy = "SELECT * FROM policy WHERE id = '$policy_id'";
		$policy_data = mysqli_fetch_assoc(mysqli_query($conn,$sql_get_policy));

		return $policy_data;
	}
}

?>