<?php
// error_reporting(0);
include '../config.php';
include '../function/function.php';
islogin();


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $data[0]['site_name']?> - 用户资料</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<!-- icon样式 -->
	<link rel="stylesheet" type="text/css" href="../static/css/iconfont.css">
	<!-- 框架样式 -->
	<link rel="stylesheet" type="text/css" href="../static/css/amazeui.css">

	<!-- 主样式 -->
	<link rel="stylesheet" href="../static/css/base.css">
	<link rel="stylesheet" href="../static/css/files.css">
	<!-- Jquery -->
	<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<div class="main">
		<div class="nav">
			<ul class="am-nav am-nav-pills">
			  <li><a href="main.php">主页</a></li>
			  <li><a href="files.php">文件管理</a></li>
			  <li><a href="share.php">分享管理</a></li>
			  <li class="am-active"><a href="#">账户资料</a></li>
				<li><a href="./imgshow/index.php" target="_blank">图廊</a></li>
			  <li><a href="setting.php">系统设置</a></li>
			  <li><a href="out.php">退出登录</a></li>
			</ul>
		</div>
		<div class="conti">
			<div class="am-g">
				<div class="am-u-md-12">
					<div class="am-panel am-panel-default">
					  <div class="am-panel-hd">我的资料</div>
					  <div class="am-panel-bd">
					  <fieldset disabled>
						  	<form class="am-form am-form-horizontal">
							  <div class="am-form-group">
							    <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">你的昵称</label>
							    <div class="am-u-sm-10">
							      <input type="text" id="nico" placeholder="请输入您的昵称" value="<?php echo $data[0]['user_nico']?>">
							    </div>
							  </div>
							  <div class="am-form-group">
							    <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">你的账户</label>
							    <div class="am-u-sm-10">
							      <input type="text" id="user" placeholder="请输入您的账户" value="<?php echo $data[0]['user_name']?>">
							    </div>
							  </div>
							  <div class="am-form-group">
							    <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">你的密码</label>
							    <div class="am-u-sm-10">
							      <input type="password" id="pwd" placeholder="请输入您的新密码" value="密码已加密">
							    </div>
							  </div>
							</form>
					</fieldset>
						<div style="width: 20%;margin: 0 auto;">
							<button id="changeme" type="button" class="am-btn am-btn-primary">修改资料</button>
							<button id="getin" type="button" class="am-btn am-btn-secondary">提交修改</button>
						</div>
					  </div>
					</div>
				</div>
			</div>
		</div>
		<div id="main_footer" style="margin-top: 200px;">
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
<script type="text/javascript">
	window.onload = function(){
		var changeme = document.getElementById('changeme');
		var getin = document.getElementById('getin');
		changeme.onclick = function(){
			var fieldset = document.getElementsByTagName('fieldset')[0];
			var pwd = document.getElementById('pwd');
			fieldset.removeAttribute('disabled');
			changeme.disabled = 'disabled';
			pwd.value = "";
		}
		getin.onclick = function(){
			var inputs = document.getElementsByTagName('input');
			var isnull = true;
			for(var i = 0 ; i < inputs.length ; i++){
				if (inputs[i].value=='') {
					isnull = false;
				}
			}
			//非空情况下才给提交
			if (isnull) {
				var nico = document.getElementById('nico').value;
				var user = document.getElementById('user').value;
				var pwd = document.getElementById('pwd').value;

				//ajax提交更新用户资料
				$.ajax({
		           type: "POST",
		           url: "../function/sharefun.php",
		           data: "nico="+nico+"&user="+user+"&pwd="+pwd,
		           success: function(data){
		            	if (data=='更新成功') {
		            		alert(data);
		            		window.location.reload();
		            	}else{
		            		alert("更新失败");
		            	}
		            }
		        });

			}else{
				alert('请确保不为空');
			}
		}

	}
</script>
