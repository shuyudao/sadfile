<?php
error_reporting(0);
ini_set('date.timezone','Asia/Shanghai');
include 'config.php';
include 'function.php';
$key = $_GET['key'];
$sql_check = "SELECT * FROM share WHERE share_key = ".$key;
$share = array();
$share[]  = mysql_fetch_assoc(mysql_query($sql_check));
$file_id = $share[0]['file_id'];
$sql_select_file = "SELECT * FROM files WHERE id = $file_id";
$result_temp = mysql_fetch_assoc(mysql_query($sql_select_file));
$share[0]['file_name'] = $result_temp['file_name'];
$share[0]['file_size'] = $result_temp['file_size'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title><?php echo $data[0]['site_name']?>- 分享文件</title>
	<!-- icon样式 -->
	<link rel="stylesheet" type="text/css" href="font/iconfont.css">
	<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="static/css/amazeui.css">
	<script type="text/javascript" src="md5.js"></script>
</head>
<style>
	*{
		margin: 0;
		padding: 0;
	}
	html,body{
		height: 100%;
		overflow: hidden;
	}
	#main{
		width: 100%;
		height: 100%;
	}
	#panel{
		width: 600px;
		min-height: 300px;
		margin: 300px auto;
		position: relative;
	}
	h1{
		text-align: center;
		color: #fff;
		font-weight: 100;
		font-size: 40px;
		position: absolute;
		top: -100px;
	}
	#title{
		overflow: hidden;
	}
	#title i{
		font-size: 30px;
		margin-right: 6px;
	}
	#title em{
		font-size: 30px;
		font-style: normal;
	}
	.am-panel-bd em{
		font-style: normal;
		font-weight: 200;
	}
	#footer{
		position: absolute;
		bottom: 4px;
		left: 50%;
		color: #fff;
		margin-left: -200px;
	}
	#pwd{
		width: 100%;
		height: 100%;
		position: absolute;
		z-index: 999;
		background-color: #fff;
		top: 0;
	}
	#input{
		width: 300px;
		height: 100px;
		margin: 300px auto;
	}
	@media screen and (max-width:630px ) {
		h1{
			font-size: 32px;
		}
		*{
			font-size: 16px;
		}
		#title i{
			font-size: 22px;
		}
		#title em{
			font-size: 22px;
		}
		#panel{
			width: 92%;
			margin: 150px auto;
		}
		button{
			width: 70%!important;
		}
		#footer{
			font-size: 12px;
			margin-left: -150px;
		}
		#input{
			margin: 150px auto;
		}
	}

</style>
<script type="text/javascript">
	var pwd = "<?php echo md5($share[0]['share_pwd'].'xxsrr')?>";
	var pwd2 = "<?php echo md5($data[0]['share_pwd'].'xxsrr')?>";
</script>
<body style="background-image: url(https://i.loli.net/2018/12/28/5c25fdf50bb8d.jpg);">

	<div id="main">
		<div id="pwd">
			<div id="input">
				<p style="font-size: 22px;">请输入提取码：</p>
				<p><input id="pwwd" type="text" class="am-form-field" placeholder="输入提取码"/></p>
				<button id="getpwd" style="width: 50%;margin:20px auto;display: block;" class="am-btn am-btn-primary">
				  下载文件
				</button>
			</div>
		</div>
	<?php
	if ($_GET['key']!=''&&$share[0]!=null&&$data[0]['isshare']!=1) {

	?>
		<div id="panel" class="am-panel am-panel-default">
			<h1><?php echo $data[0]['site_name']?> 文件分享</h1>
		    <div class="am-panel-bd">
		    	<p id="title"><i class="iconfont icon-yasuobao"></i><em><?php echo $share[0]['file_name']?></em></p>
		    	<p>分享时间：<em><?php echo $share[0]['share_time']?></em>&nbsp;&nbsp; 文件大小：<em><?php echo $share[0]['file_size']?></em> </p>
		    	<button id="start_download" style="width: 50%;margin:20px auto;display: block;" class="am-btn am-btn-danger">
				  下载资源
				  <i class="am-icon-cloud-download"></i>
				</button>
				<button disabled style="width: 50%;margin:20px auto;display: block;" class="am-btn am-btn-primary">
				  预览资源
				  <i class="am-icon-eye"></i>
				</button>
				<p style="font-size: 14px;text-align: center;color: #6f6f6f;">预览仅支持部分视频/图片等</p>
		    </div>
		</div>
		<!-- js -->
		<script type="text/javascript">
			function setCookie(name,value){
				var Days = 30;
				var exp = new Date();
				exp.setTime(exp.getTime() + Days*24*60*60*30);
				document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
			}
			function getCookie(name){
				var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
				if(arr=document.cookie.match(reg))
				return unescape(arr[2]);
				else
				return null;
			}
			window.onload = function(){
				//提取码校验
				var getpwd = document.getElementById('getpwd');
				getpwd.onclick = function(){
					var temp_pwd = document.getElementById('pwwd');
					if (temp_pwd.value=="") {
						alert("提取码为空")
					}else{
						var is_pwd = hex_md5(temp_pwd.value+"xxsrr");
						if (is_pwd==pwd||is_pwd==pwd2) {
							var pwd_panle = document.getElementById('pwd');
							pwd_panle.style.display = "none";
							setCookie("pwd",is_pwd);
						}else{
							temp_pwd.value = "";
							temp_pwd.placeholder = "提取码错误";
						}
					}
				}

				//下载再次校验提取码
				var download = document.getElementById('start_download');
				download.onclick = function(){
					var cookie_pwd = getCookie('pwd');
					if (cookie_pwd==""||cookie_pwd!=pwd&&cookie_pwd!=pwd2) {
						window.location.reload();
					}else{
						if (cookie_pwd==pwd2||cookie_pwd==pwd) {
							window.open("./download.php?share_key="+<?php echo $share[0]['share_key']?>);
						}else{
							window.location.reload();
						}

					}
				}

				//标签处理
				var files = document.getElementById('title').getElementsByTagName('i')[0];
				var filename = document.getElementById('title').getElementsByTagName('em')[0];
				if (filename.innerHTML.lastIndexOf('.zip')!=-1) {
					files.className = "iconfont icon-yasuobao";
				}else if(filename.innerHTML.lastIndexOf('.mp4')!=-1){
					files.className = "iconfont icon-shipin";
				}else if (filename.innerHTML.lastIndexOf('.docx')!=-1||filename.innerHTML.lastIndexOf('.doc')!=-1) {
					files.className = "iconfont icon-wendang";
				}else if (filename.innerHTML.lastIndexOf('.php')!=-1||filename.innerHTML.lastIndexOf('.html')!=-1|filename.innerHTML.lastIndexOf('.java')!=-1) {
					files.className = "iconfont icon-html";
				}else if (filename.innerHTML.lastIndexOf('.')==-1) {
					files.className = "iconfont icon-wenjian";
				}else if (filename.innerHTML.lastIndexOf('.jepg')!=-1||filename.innerHTML.lastIndexOf('.png')!=-1||filename.innerHTML.lastIndexOf('.gif')!=-1||filename.innerHTML.lastIndexOf('.jpg')!=-1) {
					files.className = "iconfont icon-picture_icon";
				}else{
					files.className = "iconfont icon-wenjian1";
				}
			}
		</script>
	<?php } else {?>
		<script type="text/javascript">
			var pwd_panle = document.getElementById('pwd');
			pwd_panle.style.display = "none";
		</script>
		<div id="panel" class="am-panel am-panel-default" style="position: relative;">
			<i style="font-size: 80px;position: absolute;left: 50%;margin-left: -34px;color: #2196f3" class="am-icon-frown-o"></i>
			<p style="font-size: 18px;text-align: center;line-height: 100px;color: red;margin-top: 90px;font-weight: 600">文件 不存在/已取消 分享或其他</p>
		</div>
	<?php };?>
	</div>
	<p id="footer">CopyRight©2018 SadFile All Rights Reserved. MIT license</p>
</body>
</html>
