<!--
	开发人员:术与道
	时间： 2018-12-28

	1.2 /2019-1-5
	1.3.1 /2019-3-16
	1.3.5 / 2019-4-17
 -->
<meta charset="utf-8">
<?php
$server = $_POST['server'];
$dbuser = $_POST['dbuser'];
$dbpwd = $_POST['dbpwd'];
$dbname = $_POST['dbname'];

$admin_user = $_POST['admin_user'];
$admin_pwd = md5($_POST['admin_pwd'].'xxrs!./'); #密码加密
$admin_nico = $_POST['admin_nico'];

$siteurl = $_POST['siteurl'];
if ($server==''||$dbuser==''||$dbpwd==''||$dbname=='') {
	echo "<script>
	alert('请填写完整')
	</script>";
}else{
	$is_install = fopen("install.lock", "x+");
	if (!$is_install) {
		echo "<h1 align='center'>已安装过，如需重复安装请删除install.lock文件</h1>";
	}else{
		$config_txt = '<?php
$server="'.$server.'";
$dbuser="'.$dbuser.'";
$dbpwd="'.$dbpwd.'";
$dbname="'.$dbname.'";
?>';
		$wirte_config = fopen("config.php", "w");
		$conn = mysqli_connect($server,$dbuser,$dbpwd,$dbname);
		if ($conn) {
			if (mysqli_query($conn,"set names 'utf8'")) {
				#基本信息表
				$sql_create_base = "CREATE table base (
				site_url varchar(50),   #站点域名
				site_name varchar(50) default 'SadFile',	#站点名称
				site_des varchar(255),	#站点描述
				site_img varchar(100),	#站点背景图
				qiniu_url varchar(50),  #七牛域名
				qiniu_AK varchar(100),	#七牛accessKey公钥
				qiniu_SK varchar(100),	#七牛secretKey私钥
				qiniu_name varchar(20), #七牛空间名称
				share_pwd varchar(5) default '0000',	#万能提取密码
				isshare int,			#分享功能是否开启 1为开启 其他关闭
				clearsharepwd int,		#是否修改所有的为公开 1为全部公开 其他关闭
				user_nico varchar(20),	#用户昵称
				user_name varchar(20),	#用户账户
				user_pwd varchar(50),	#用户密码
				login_time datetime,	#登录时间
				max_upload varchar(50) default '100mb', #最大上传大小
				tulang_state varchar(5) default '1',
				tulang_open varchar(5) default '0',
				tulang_dir varchar(60) default '0',
				login_ip varchar(36));";	#登录IP

				#文件表
				$sql_create_files = "CREATE table files (
				id int(11) primary key auto_increment unique NOT NULL,  #文件的id主键
				file_name varchar(100) NOT NULL, #文件名
				file_size varchar(20) NOT NULL, #文件大小 （计算转为字符串后存储）
				file_size_num varchar(50) NOT NULL,		#分享的文件大小 转意后
				file_time datetime, #文件的创建时间
				partent_dir_key varchar(100), #所属父级目录key 用来保证唯一性
				qiniu_name varchar(100), #云端文件名 （于七牛云存储的文件名）
				md5key varchar(50) #文件的MD5key值，用于校验是否为非法下载  只有当id与MD5key一一对应才给予下载（用于分享的游客下载）
				)ENGINE=InnoDB DEFAULT CHARSET=utf8;";

				#分享表
				$sql_create_share = "CREATE table share (
				share_key varchar(50) primary key unique NOT NULL,  #分享识别的KEY
				file_id int(11),
				share_time varchar(20) NOT NULL,	#文件分享时间
				download_cont int NOT NULL default 0,#文件的下载次数
				share_pwd varchar(5)#文件的提取密码
				)ENGINE=InnoDB DEFAULT CHARSET=utf8;";

				#目录表
				$sql_create_dir = "CREATE table dirs (
				id int primary key auto_increment unique NOT NULL, #目录ID 主键
				dir_name varchar(100), #目录名
				dir_time datetime, #目录创建时间
				dir_key varchar(60), #目录key
				partent_dir_key varchar(60) #父级目录key
				);";

				$sql_cascade = "ALTER table share add constraint File_ids foreign key (file_id) references files (id) ON UPDATE CASCADE ON DELETE CASCADE;";
				if(!mysqli_query($conn,$sql_create_base)||!mysqli_query($conn,$sql_create_share)||!mysqli_query($conn,$sql_create_files)||!mysqli_query($conn,$sql_create_dir)){
					echo mysqli_error();
					echo "<h1 align='center'>创建表失败</h1>";
				}else{
					fwrite($wirte_config,$config_txt);//写入配置文件
					fclose($wirte_config );
					mysqli_query($conn,$sql_cascade);
					$sql_insert = "INSERT into base (site_url,user_name,user_pwd,user_nico) VALUES ('$siteurl','$admin_user','$admin_pwd','$admin_nico');";
					mysqli_query($conn,$sql_insert);
					echo "<h1 align='center'>安装成功</h1><p align='center'><a href='index.html'>点击返回首页<a></p>";

				}

			}else{
				echo "<h1 align='center'>数据库不存在，请输入准确的数据库或手动创建该数据库</h1>";
			}
		}else{
			echo "<h1 align='center'>数据库连接失败</h1>";
		}

	}
}


?>
