<?php
//error_reporting(0);
if (!session_id()) session_start();
include_once '../config.php';
include 'function.php';
include 'FileManger.php';
include_once 'DB.php';
include 'Response.php';
include 'log.php';
use Qiniu\Auth; #鉴权类

//login
if (isset($_POST['username'])) {
	$logger = new Log("login.php");
	
	$pwd = md5($_POST['password'].'xxrs!./');
	$user = $_POST['username'];

	if ($pwd==$data[0]['user_pwd']&&$user==$data[0]['user_name']) {
		$_SESSION['user'] = "user";

		$logger->write("登录成功");

		exit($Response->success(1));
	}else{

		$logger->write("尝试以 ".$_POST['username']."/".$_POST['password']." 的用户名登录，登录失败");

		exit($Response->fail("账号或密码错误"));
	}
}else{
	islogin();
}


#修改文件名或目录名

if (isset($_GET['id'])&&isset($_GET['newname'])) {
	$id = $_GET['id'];
	$newname = $_GET['newname'];
	if ($_GET['dir']=='0') {
		$sql_update_name = "UPDATE files SET file_name = '$newname' WHERE id = $id";
	}else{
		$sql_update_name = "UPDATE dirs SET dir_name = '$newname' WHERE dir_key = $id";
	}

	if ($DB->update($sql_update_name)) {
		exit($Response->success(1));
	}else{
        exit($Response->fail($sql_update_name));
	}
}

#获取新的目录
if (isset($_GET['new_dir_key'])) {
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
if (isset($_GET['file_id_arr'])||isset($_GET['dir_id_arr'])) {

		$partent_dir_key = $_GET['partent_dir_key'];

		if ($_GET['file_id_arr']){
            $file_id_arr = $_GET['file_id_arr'];
            $sql_change_file = "UPDATE files SET partent_dir_key = $partent_dir_key WHERE id in (";

            for($i = 0 ; $i < count($file_id_arr) ; $i++){
                if ($i==count($file_id_arr)-1){
                    $sql_change_file.=$file_id_arr[$i].")";
                }else{
                    $sql_change_file.= $file_id_arr[$i].",";
                }
            }

            $DB->update($sql_change_file);
		}

		if (isset($_GET['dir_id_arr'])){
        	$dir_id_arr = $_GET['dir_id_arr'];
            $sql_change_dir = "UPDATE dirs SET partent_dir_key = $partent_dir_key WHERE dir_key in (";

            for($i = 0 ; $i < count($dir_id_arr) ; $i++){
                if ($i==count($dir_id_arr)-1){
                    $sql_change_dir.=$dir_id_arr[$i].")";
                }else{
                    $sql_change_dir.= $dir_id_arr[$i].",";
                }
            }
            $DB->update($sql_change_dir);
		}

		exit($Response->success(1));

}

//文件列表
if (isset($_GET['get_list'])) {
	$dirs_arr = [];
	if (isset($_GET['type'])) {
		$page = (int)$_GET['page']*12;
		switch ($_GET['type']) {
			case 'doc':
				$sql_get_files_temp = "SELECT * FROM files WHERE file_name regexp 'txt$|doc$|docx$|xls$|xlsx$|ppt$|pptx$' LIMIT $page,12";
				break;
			case 'music':
				$sql_get_files_temp = "SELECT * FROM files WHERE file_name regexp 'mp3$|m4a$|wav$|flac$|aac$' LIMIT $page,12";
				break;
			case 'img':
				$sql_get_files_temp = "SELECT * FROM files WHERE file_name regexp 'jpg$|png$|gif$|jpeg$|bmp$' LIMIT $page,12";
				break;
			case 'video':
				$sql_get_files_temp = "SELECT * FROM files WHERE file_name regexp 'mp4$|avi$|flv$|mkv$|wmv$' LIMIT $page,12";
				break;
			default:
				$sql_get_files_temp = "SELECT * FROM files WHERE file_name regexp 'txt$|doc$|docx$|xls$|xlsx$|ppt$|pptx$' LIMIT $page,12";
				break;
		}
		
	}else{
		$sql_get_dirs_temp = "SELECT * FROM dirs WHERE partent_dir_key = ".$_GET['get_list'];
		$sql_get_files_temp = "SELECT * FROM files WHERE partent_dir_key = ".$_GET['get_list'];
		$dirs_arr = $DB->query($sql_get_dirs_temp);
	}
	
	$files_arr = $DB->query($sql_get_files_temp);
	$arr[0] = $dirs_arr;
	$arr[1] = $files_arr;
	exit($Response->success($arr));
}
//预览
if (isset($_GET['preview'])) {
	$filename = $_GET["preview"];
	#预览
	function preview($file_name,$data){
        $auto = new FileManger($file_name,$data);
        return $auto->preview();
	}
	//不同策略之间自由切换
		//qiniu_name 文件原名
	$sql_get_file_policy_id = "SELECT for_policy_id FROM files WHERE qiniu_name = '$filename'";
	$type = $DB->query($sql_get_file_policy_id);
	$policy_id = $type[0]['for_policy_id'];
	$sql_get_policy = "SELECT * FROM policy WHERE id = '$policy_id'";
	$data = $DB->query($sql_get_policy);

	exit($Response->success(preview($filename,$data[0])));
}

//创建目录

if (isset($_GET['mkdir_name'])) {
    $mkdir_name = $_GET['mkdir_name'];
	$partent_dir_key = $_GET['dir_key'];
	$this_dir_key = time()."";
	$mkdir_name = urldecode($mkdir_name);
	$sql_add_dir = "INSERT into dirs (dir_name,dir_time,dir_key,partent_dir_key) VALUES ('$mkdir_name',NOW(),$this_dir_key,'$partent_dir_key');";
	if($temp = $DB->update($sql_add_dir)){
		$sql_id = "SELECT max(dir_key) as id FROM dirs";
		$data = $DB->query($sql_id);
        exit($Response->success($data[0]['id']));
	}else{
		exit($Response->fail("fail"));
	}
}

//文件搜索

if (isset($_GET['search_word'])) {
    $search_word = $_GET['search_word'];
	$sql_get_file = "SELECT * FROM files WHERE file_name like '%".$search_word."%'";
	$result = $DB->query($sql_get_file);

	exit($Response->success($result));

}

//获取基本信息
if (isset($_GET['info'])) {
	$sql = 'select * from base left join policy on base.policy_id = policy.id';
	$arr = $DB->query($sql);
	unset($arr[0]['user_name']);
	unset($arr[0]['user_pwd']);
	unset($arr[0]['share_pwd']);
	unset($arr[0]['login_ip']);
	unset($arr[0]['ak']);
	unset($arr[0]['sk']);
	unset($arr[0]['domain']);

	exit($Response->success($arr));
}
//更新当前使用的策略
if (isset($_GET['policy_id'])){
	$sql = "update base SET policy_id = ".$_GET['policy_id'];
	$arr = $DB->update($sql);
	exit($Response->success(1));
}

//获取分享列表
if (isset($_GET['share_list'])) {
	$page = (int)$_GET['page']*12;
	$sql = "select * from share left join files on share.file_id = files.id ORDER BY share_time DESC LIMIT $page,12";
	$arr = $DB->query($sql);
	exit($Response->success($arr));
}

//修改提取码
if (isset($_GET['change_share_pwd'])) {
	$newpwd = $_GET['newpwd'];
	$share_key = $_GET['share_key'];
	$sql = "UPDATE share SET share_pwd = '$newpwd' WHERE share_key = '$share_key'";
	$DB->update($sql);
	exit($Response->success(1));
}

//取消分享
if (isset($_GET['del_share'])) {
	$sk = $_GET['del_share'];
	$sql = "delete from share WHERE share_key = '$sk'";
	$DB->update($sql);
	exit($Response->success(1));
}

//退出登录
if (isset($_GET['logout'])) {
	$_SESSION['user'] = null;
	header('Location:'.$data[0]['site_url'].'/login.html');
}

//setting
if (isset($_GET['setting'])) {
	exit($Response->success($data));
}

//获取所有策略信息
if (isset($_GET['getPolicy'])) {
	$sql = "SELECT id,name from policy WHERE status is null OR status = 1";
	$arr = $DB->query($sql);
	exit($Response->success($arr));
}
//获取详细信息
if (isset($_GET['getPolicyInfo'])) {
	$sql = "SELECT * from policy WHERE id = ".$_GET['getPolicyInfo'];
	$arr = $DB->query($sql);
	exit($Response->success($arr));
}

//更新策略
if (isset($_POST['name'])&&isset($_POST['ak'])) {
	$name = $_POST['name'];
	$ak = $_POST['ak'];
	$sk = isset($_POST['sk'])?$_POST['sk']:"";
	$domain = $_POST['domain'];
	$bucket = isset($_POST['bucket'])?$_POST['bucket']:"";
	$type = $_POST['type'];
	if (!isset($_POST['id'])) {
		$sql = "INSERT into policy (name,ak,sk,domain,bucket,type,status) VALUES ('$name','$ak','$sk','$domain','$bucket','$type',1)";
	}else{
		$id = $_POST['id'];
		$sql = "UPDATE policy SET name = '$name' , ak = '$ak' , sk = '$sk' , domain = '$domain' , bucket = '$bucket' WHERE id = '$id'";
	}
	
	$DB->update($sql);
	exit($Response->success(1));
}

//删除策略
if (isset($_GET['del_id'])) {
	$id = $_GET['del_id'];
	$sql = "UPDATE policy SET status = 0 WHERE id = ".$id;
	$DB->update($sql);
	exit($Response->success(1));
}

//更新部分参数
if (isset($_POST['field_name'])) {
	$field_name = $_POST['field_name'];
	$field_value = $_POST['field_value'];
	if ($field_value=="on"&&$field_name=="isshare") {
		$field_value = 1;
	}else if($field_name=="isshare"){
		$field_value = 0;
	}
	$sql = "UPDATE base SET $field_name = '$field_value'";
	$DB->update($sql);
	exit($Response->success(1));

}


exit();
?>
