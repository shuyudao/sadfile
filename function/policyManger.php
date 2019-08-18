<?php
/**
 * Policy上传策略管理
 */
include_once '../config.php';
include_once 'function.php';
class PolicyManger
{
	
	function __construct()
	{
		# code...
	}

	public static function getPolicydataByfileId($file_id,$DB){

		$sql_file = "SELECT * FROM files WHERE id = ".$file_id;
		$end = $DB->query($sql_file);
		$for_policy_id = $end['for_policy_id'];
		$sql_get_policy = "SELECT * FROM policy WHERE id = '$for_policy_id'";
		$policy_data = $DB->query($sql_get_policy);

		return $policy_data;
	}

	public static function getPolicydataBypolicyId($policy_id,$DB){
		$sql_get_policy = "SELECT * FROM policy WHERE id = '$policy_id'";
		$policy_data = $DB->query($sql_get_policy);

		return $policy_data;
	}
}

?>