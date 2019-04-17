<!--
	编写：@术与道
	时间：2018 - 12 -27
 -->
<?php
// error_reporting(0);
include '../function/function.php';
include '../config.php';
islogin();

// var_dump($data);
if($data[0]['qiniu_url']==''){
	echo "<script>alert('请前往系统设置配置七牛云设置')</script>";
}

function getAllFileSize(){
	global $conn;
	$sql = "SELECT * FROM files;";
	$res = mysqli_query($conn,$sql);
	$end = 0;
	while ($row = mysqli_fetch_assoc($res)) {
		$end = $end+(int)$row['file_size_num'];
	}
	return $end;
}
function getFileCounts(){
	global $conn;
	$sql = "SELECT * FROM files;";
	$res = mysqli_query($conn,$sql);
	$end = 0;
	while ($row = mysqli_fetch_assoc($res)) {
		$end++;
	}
	return $end;
}
function getShareCounts(){
	global $conn;
	$sql = "SELECT * FROM share;";
	$res = mysqli_query($conn,$sql);
	$end = 0;
	while ($row = mysqli_fetch_assoc($res)) {
		$end++;
	}
	return $end;
}
function getShareDownloadCounts(){
	global $conn;
	$sql = "SELECT * FROM share;";
	$res = mysqli_query($conn,$sql);
	$end = 0;
	while ($row = mysqli_fetch_assoc($res)) {
		$end += (int)$row['download_cont'];
	}
	return $end;
}

?>
<!DOCTYPE html>
<html lang="ch">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title><?php echo $data[0]['site_name']?>- 主页</title>
	<!-- 框架样式 -->
	<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../static/css/amazeui.css">
	<script type="text/javascript" src="../static/js/amazeui.js"></script>
	<!-- 主样式 -->
	<link rel="stylesheet" href="../static/css/base.css">
	<link rel="stylesheet" href="../static/css/main.css">
	<style type="text/css">
		body{
			overflow-x: hidden;
		}
	</style>
</head>
<body>
	<div class="main">
		<div class="nav">
			<ul class="am-nav am-nav-pills">
			  <li class="am-active"><a href="#">主页</a></li>
			  <li><a href="files.php">文件管理</a></li>
			  <li><a href="share.php">分享管理</a></li>
				<li><a href="user.php">账户资料</a></li>
			  <li><a href="./imgshow/index.php" target="_blank">图廊</a></li>
			  <li><a href="setting.php">系统设置</a></li>
			  <li><a href="out.php">退出登录</a></li>
			</ul>
		</div>

		<div class="conti">
			<div class="am-g">
				<div class="am-u-md-4">
					<div class="am-panel am-panel-primary">
						<div class="am-panel-hd">登录信息</div>
						<div class="content">
							<p><span class="am-badge am-badge-success">欢迎你:</span><?php echo $data[0]['user_nico']?></p>
							<p><span class="am-badge am-badge-secondary">本次登陆IP:</span><?php echo $data[0]['login_ip']?></p>
							<p><span class="am-badge am-badge-danger">登录时间:</span><?php echo $data[0]['login_time']?></p>
							<p><span class="am-badge am-badge-warning">服务器操作系统:</span><?php echo PHP_OS;?></p>
							<p><span class="am-badge am-badge">浏览器:</span><?php echo getBrowser()?></p>
						</div>
					</div>
				</div>
				<div class="am-u-md-8">
					<div class="am-panel am-panel-primary">
						<div class="am-panel-hd">基础信息</div>
						<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" >
					    <h2 class="am-titlebar-title ">
					        服务器信息
					    </h2>
						</div>
						<div class="content">
							<ul class="am-list am-list-static am-list-border">
								<li><b>服务器IP：</b><?php echo $_SERVER['SERVER_NAME'];?></li>
							 	<li><b>服务器最大上传：</b><?php echo $data[0]['max_upload']?></li>
							 	
							</ul>
						</div>

						<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" >
					    <h2 class="am-titlebar-title ">
					        文件信息
					    </h2>
						</div>
						<div class="content">
							<ul class="am-list am-list-static am-list-border">
								<li><b>文件存储大小：</b><?php echo trans_byte(getAllFileSize()); ?></li>
								<li><b>外链总下载数：</b><?php echo getShareDownloadCounts(); ?><b>&nbsp;&nbsp;&nbsp;&nbsp;分享文件数：</b><?php echo getShareCounts(); ?><b>&nbsp;&nbsp;&nbsp;&nbsp;总文件数：</b><?php echo getFileCounts(); ?></li>
								<li>空位待置</li>
								<li>空位待置</li>
							</ul>
						</div>

					</div>
				</div>

			</div>
		</div>

		<div id="main_footer">
			<footer id="footer" data-am-widget="footer" class="am-footer am-footer-default" data-am-footer="{  }">
			    <div class="am-footer-switch">
			    <span class="am-footer-ysp" data-rel="mobile">
			          SadFile
			    </span>
			      <span class="am-footer-divider"> | </span>
			      <a id="godesktop" data-rel="desktop" class="am-footer-desktop" href="http://www.imwj.top">
			          术与道
			      </a>
			    </div>
			    <div class="am-footer-miscs ">

			          <p>版权所有者： <a href="http://www.imwj.top/" title="诺亚方舟"
			                                                target="_blank" class="">术与道</a></p>
			        <p>CopyRight©2018 All Rights Reserved.</p>
			        <p>MIT license</p>
			    </div>
		  </footer>
		</div>

	</div>
</body>
</html>
