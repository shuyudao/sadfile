<?php 
include '../config.php';
include 'FileManger.php';
include 'policyManger.php';
islogin();

if ($_POST['method']=='site_setting') {
	$site_name = $_POST['site_name'];
	$site_url = $_POST['site_url'];
	$site_des = $_POST['site_des'];
	$sql = "UPDATE base SET site_name = '$site_name',site_url = '$site_url',site_des = '$site_des'";
	if (mysqli_query($conn,$sql)) {
		echo "success";
	}
}else if ($_POST['method']=='add_policy') {
	$policy_name = $_POST['policy_name'];
	$policy_ak = $_POST['policy_ak'];
	$policy_sk = $_POST['policy_sk'];
	$policy_bucket = $_POST['policy_bucket'];
	$policy_domain = $_POST['policy_domain'];
	$policy_type = $_POST['policy_type'];
	$sql = "INSERT into policy VALUES (null,'$policy_name','$policy_ak','$policy_sk','$policy_domain',1,'$policy_bucket','$policy_type')";
	if (mysqli_query($conn,$sql)) {
		echo "success";
	}
}else if ($_POST['method']=='edit_policy') {
	$policy_name = $_POST['policy_name'];
	$policy_ak = $_POST['policy_ak'];
	$policy_sk = $_POST['policy_sk'];
	$policy_bucket = $_POST['policy_bucket'];
	$policy_domain = $_POST['policy_domain'];
	$policy_type = $_POST['policy_type'];
	$policy_id = $_POST['policy_id'];
	$sql = "UPDATE policy SET name = '$policy_name',ak = '$policy_ak',sk = '$policy_sk',domain = '$policy_domain',bucket = '$policy_bucket',type = '$policy_type' WHERE id = '$policy_id'";
	if (mysqli_query($conn,$sql)) {
		echo "success";
	}
}else if ($_POST['method']=='del_policy') {
	$id = $_POST['policy_id'];
	$sql = "UPDATE policy SET status = 0 WHERE id = '$id'";
	$sql_2 = "UPDATE base SET policy_id = 1";
	if (mysqli_query($conn,$sql)) {
		mysqli_query($conn,$sql_2);
		echo "success";
	}else{
		echo mysqli_error($conn);
	}
}else if ($_POST['method']=='use_policy') {
	$id = $_POST['policy_id'];
	$sql = "UPDATE base SET policy_id = '$id'";
	if (mysqli_query($conn,$sql)) {
		echo "success";
	}else{
		echo mysqli_error($conn);
	}
}else if ($_POST['method']=='edit_share_pwd') {
	$pwd = $_POST['pwd'];
	$sql = "UPDATE base SET share_pwd = '$pwd'";
	if (mysqli_query($conn,$sql)) {
		echo "success";
	}else{
		echo mysqli_error($conn);
	}
}else if ($_POST['method']=='save_other'){
	$update_max_size = $_POST['update_max_size'];
	$tulang_state = $_POST['tulang_state'];
	$tulang_open = $_POST['tulang_open'];
	$tulang_dir = $_POST['tulang_dir'];
	$sql = "UPDATE base SET tulang_state = '$tulang_state',tulang_open = '$tulang_open',tulang_dir = '$tulang_dir',max_upload = '$update_max_size'";
	if (mysqli_query($conn,$sql)) {
		echo "success";
	}else{
		echo mysqli_error($conn);
	}
}

 ?>