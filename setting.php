<?php
error_reporting(0);
ini_set('date.timezone','Asia/Shanghai');
include 'config.php';
include 'function.php';
islogin();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $data[0]['site_name']?> - 系统设置</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<!-- icon样式 -->
	<link rel="stylesheet" type="text/css" href="font/iconfont.css">
	<!-- 框架样式 -->
	<link rel="stylesheet" type="text/css" href="static/css/amazeui.css">
	<link rel="stylesheet" href="static/alert/css/style.css">
	<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript" src="static/js/amazeui.js"></script>
	<script type="text/javascript" src="static/alert/js/ui.js"></script>
	<!-- 主样式 -->
	<link rel="stylesheet" href="static/css/base.css">
	<link rel="stylesheet" href="static/css/files.css">
	<style type="text/css">
		.in_w{
			width: 100%;
		}
	</style>
</head>
<body>
	<div class="main">
		<div class="nav">
			<ul class="am-nav am-nav-pills">
			  <li><a href="main.php">主页</a></li>
			  <li><a href="files.php">文件管理</a></li>
			  <li><a href="share.php">分享管理</a></li>
			  <li><a href="user.php">账户资料</a></li>
				<li><a href="./imgshow/index.php" target="_blank">图廊</a></li>
			  <li class="am-active"><a href="">系统设置</a></li>
			  <li><a href="out.php">退出登录</a></li>
			</ul>
		</div>

		<div class="conti">
			<div class="am-g">
			<fieldset disabled>
				<div class="am-u-md-6">
					<div class="am-panel am-panel-default">
					  	<div class="am-panel-hd">系统设置</div>
					  	<div class="am-panel-bd">
					  		<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" >
							    <h2 class="am-titlebar-title ">
							        站点设置
							    </h2>
							</div>
					  		<div class="in_w">
					  			<form class="am-form am-form-horizontal">
									 <div class="am-form-group">
									    <div class="am-u-sm-10">
									      <b>系统根域名</b><input type="text" id="site_url" placeholder="系统根域名" value="<?php echo $data[0]['site_url']?>">
									    </div>
									 </div>
									 <div class="am-form-group">
									    <div class="am-u-sm-10">
									      <b>站点名称</b><input type="text" id="site_name" placeholder="站点名称" value="<?php echo $data[0]['site_name']?>">
									    </div>
									 </div>
									 <div class="am-form-group">
									    <div class="am-u-sm-10">
									      <b>站点首页描述</b><input type="text" id="site_des" placeholder="首页描述" value="<?php echo $data[0]['site_des']?>">
									    </div>
									 </div>
								</form>
					  		</div>

							<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" >
							    <h2 class="am-titlebar-title ">
							        七牛配置项目
							    </h2>
							</div>

							<div class="in_w">
					  			<form class="am-form am-form-horizontal">
									 <div class="am-form-group">
									    <div class="am-u-sm-10">
									      <b>七牛云域名<span style="font-weight: 100"></span></b><input type="text" id="qiniu_url" placeholder="七牛云域名 以http或https开头，勿要“/”结尾" value="<?php echo $data[0]['qiniu_url']?>">
									    </div>
									 </div>
									 <div class="am-form-group">
									    <div class="am-u-sm-10">
									      <b>空间名</b><input type="text" id="qiniu_name" placeholder="七牛的存储空间名" value="<?php echo $data[0]['qiniu_name']?>">
									    </div>
									 </div>
									 <div class="am-form-group">
									    <div class="am-u-sm-10">
									      <b>AccessKey</b><input type="text" id="qiniu_ak" placeholder="七牛AccessKey" value="<?php echo $data[0]['qiniu_AK']?>">
									    </div>
									 </div>
									 <div class="am-form-group">
									    <div class="am-u-sm-10">
									      <b>SecretKey</b><input type="text" id="qiniu_sk" placeholder="七牛SecretKey" value="<?php echo $data[0]['qiniu_SK']?>">
									    </div>
									 </div>
									 <!-- <div class="am-form-group">
									    <div class="am-u-sm-10">
									      <b>站点首页描述</b><input type="text" id="doc-ipt-3" placeholder="首页描述" value="你好！人类">
									    </div>
									 </div> -->
								</form>
					  		</div>

					  	</div>
					</div>
				</div>

				<div class="am-u-md-6">
					<div class="am-panel am-panel-default">
					  	<div class="am-panel-hd">系统设置</div>
					  	<div class="am-panel-bd">
					  		<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" >
							    <h2 class="am-titlebar-title ">
							        分享设置
							    </h2>
							</div>
					  		<div class="in_w">
					  			<form class="am-form am-form-horizontal">
									 <div class="am-form-group">
									    <div class="am-u-sm-10">
									      <b>万能提取码：</b><input type="text" id="share_pwd" placeholder="万能提取码：" value="<?php echo $data[0]['share_pwd']?>">
									    </div>
									 </div>
									 <div style="margin-left: 18px;">
									 	<span><b>分享功能设置: </b> </span>
									 	<div class="am-btn-group" data-am-button>
										  <label id="type_share" class="am-btn am-btn-primary">
										    <input type="checkbox" name="doc-js-btn" value="关闭所有分享">关闭分享功能
										  </label>
										 <!--  <label class="am-btn am-btn-primary">
										    <input type="checkbox" name="doc-js-btn" value="橘子"> 清除所有提取码
										  </label> -->
										</div>
										<script>
										  $(function() {
										    var $cb = $('[name="doc-js-btn"]');
										    $cb.on('change', function() {
										      var checked = [];
										      $cb.filter(':checked').each(function() {
										        checked.push(this.value);
										      });
										    });
										  });
										</script>
									</div>
								</form>
							</div>

						<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" >
								<h2 class="am-titlebar-title ">
										其他设置
								</h2>
						</div>
							<div class="in_w">
								<form class="am-form am-form-horizontal">
								 <div class="am-form-group">
										<div class="am-u-sm-10">
											<b>最大上传：</b><input type="text" id="update_max_size" placeholder="最大上传：" value="<?php echo $data[0]['max_upload']?>">
										</div>
								 </div>
								 </form>
							</div>

							<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" >
									<h2 class="am-titlebar-title ">
											图廊设置
									</h2>
							</div>
								<div class="in_w">
									<form class="am-form am-form-horizontal">
									 <div class="am-form-group">
											<div class="am-u-sm-10">
												<b>图廊状态</b><input type="text" id="tulang_state" placeholder="1：开启 0：关闭" value="<?php echo $data[0]['tulang_state']?>">
											</div>
									 </div>
									 <div class="am-form-group">
										 <div class="am-u-sm-10">
											 <b>是否公开</b><input type="text" id="tulang_open" placeholder="1：公开 0：关闭" value="<?php echo $data[0]['tulang_open']?>">
										 </div>
								   </div>
									 <div class="am-form-group">
										 <div class="am-u-sm-10">

											 <b>指定图廊目录：</b><input type="hidden" id="tulang_dir" key='<?php echo $data[0]['tulang_dir']?>' placeholder="1：公开 0：关闭" value="<?php echo $data[0]['tulang_dir']?>"><b style="color:#666"><?php
											 	$key = $data[0]['tulang_dir'];
												$SQL  = "SELECT * FROM dirs WHERE dir_key = $key";
												$end = mysql_query($SQL);
												$res = mysql_fetch_assoc($end);
												echo $res['dir_name'];
												if ($res['dir_name']=='') {
													echo "根目录";
												}
											  ?></b>
											 <div id="xuanze" style="width:100px;height:32px;text-align:center;line-height:32px;color:#fff;cursor:pointer" data-am-modal="{target: '#move-file'}" title="tulang" class="am-badge-success am-text-sm move">选择目录</div>
										 </div>
								   </div>
									 </form>
								</div>

						</div>

						</div>
					 </div>
				</div>
		</fieldset>
				<div>
					<div style="margin-left: 26px;">
							<button id="changeme" style="display: inline-block;" type="button" class="am-btn am-btn-primary">修改所有</button>
							<button id="getin" style="display: inline-block;" type="button" class="am-btn am-btn-secondary">提交修改</button>
					</div>
				</div>
			</div>

		</div>

		<div id="main_footer" style="margin-top: 0px;">
			<footer id="footer" data-am-widget="footer" class="am-footer am-footer-default" data-am-footer="{  }">
			    <div class="am-footer-switch">
			    <span class="am-footer-ysp" data-rel="mobile"
			          data-am-modal="{target: '#am-switch-mode'}">
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
	<div style="width:400px;margin-left:-200px;height:350px" class="am-popup" id="move-file">
		<div class="am-popup-inner">
			<div class="am-popup-hd">
				<h4 class="am-popup-title">请选择图廊目录</h4>
				<span id="close_move" data-am-modal-close
							class="am-close">&times;</span>
			</div>
			<div id="move-file-in" class="am-popup-bd" style="min-height:90%">
					<div class="file_dir" dir-key="0"><i class="iconfont icon-wenjian"></i> <em style="font-style: inherit!important;">根目录</em><span class="am-badge in-turn am-badge-success am-radius" style="float:right;margin-top:6px;">打开</span></div>
			</div>
			<button id='my-move-target' class="am-btn am-btn-primary am-btn-xs" style="position:absolute;left:50%;margin-left:-37px;margin-top:5px;">请点击选择目录</button>
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
			fieldset.removeAttribute('disabled');
			changeme.disabled = 'disabled';
		}

		getin.onclick = function(){
			// 获取数据
			var site_name = document.getElementById('site_name').value;
			var site_url = document.getElementById('site_url').value;
			var site_des = document.getElementById('site_des').value;
			var qiniu_url = document.getElementById('qiniu_url').value;
			var qiniu_name = document.getElementById('qiniu_name').value;
			var qiniu_ak = document.getElementById('qiniu_ak').value;
			var qiniu_sk = document.getElementById('qiniu_sk').value;
			var share_pwd = document.getElementById('share_pwd').value;
			var update_max_size = document.getElementById('update_max_size').value;
			var tulang_state = document.getElementById('tulang_state').value;
			var tulang_open = document.getElementById('tulang_open').value;
			var tulang_dir = document.getElementById('tulang_dir').getAttribute('key');
			if (site_name==''||site_url==''||qiniu_ak=='') {
				alert("请至少保证必填项完整");
			}else{
				//2019-3-16  新增 规范用户对七牛云url的规范填写
				if (qiniu_url.indexOf('http://')==-1&&qiniu_url.indexOf('https://')==-1) {
					mizhu.toast('七牛云域名请使用http://或https://', 3000);
					return false;
				}else if(qiniu_url.charAt(qiniu_url.length-1)=="/"){
					mizhu.toast('七牛云域名请不要以‘/’结尾', 3000);
					return false;
				}

				$.ajax({
		           type: "POST",
		           url: "sharefun.php",
		           data: "site_name="+site_name+"&tulang_state="+tulang_state+"&tulang_open="+tulang_open+"&tulang_dir="+tulang_dir+"&update_max_size="+update_max_size+"&site_url="+site_url+"&site_des="+site_des+"&qiniu_url="+qiniu_url+"&qiniu_name="+qiniu_name+"&qiniu_ak="+qiniu_ak+"&qiniu_sk="+qiniu_sk+"&share_pwd="+share_pwd,
		           success: function(data){
		            	if (data=='修改成功') {
		            		window.location.reload();
		            	}else{
		            		mizhu.toast('修改失败', 3000);
		            	}
		            }
		        });
			}

		}
		try{
			var isshare = <?php echo $data[0]['isshare'];?>0;
		}catch(err){
		    var isshare = 0;
		}


		if (isshare==10) {
			document.getElementById('type_share').className = "am-btn am-btn-primary am-active";
		}else{
			document.getElementById('type_share').className = "am-btn am-btn-primary";
		}

		document.getElementById('type_share').onclick = function(){
			$.ajax({
		           type: "POST",
		           url: "sharefun.php",
		           data: "isshare="+isshare,
		           success: function(data){
		            	if (data=='修改成功') {
		            		alert('修改分享状态成功')
		            		window.location.reload();
		            	}else{
		            		mizhu.toast('修改失败', 3000);
		            	}
		            }
		    });
		}


	}
	function movefile(){
	//开始移动_按钮_ajax
	document.getElementById('my-move-target').onclick = function () {
			if (this.getAttribute('target')=='') {
				return false;
			}else{
				$("#tulang_dir").attr('key',this.getAttribute('key'));
				$("#close_move").click();
				$("#xuanze").html($(this).attr('name'));
			}
		}

	//移动文件 打开目录_ajax获取目录
	function clickfile(){
		//决定目录
			var dirs = $('.file_dir');
			for(var i = 0 ; i < dirs.length ; i++){
				dirs[i].onclick = function () {
					if (this.getAttribute('fi_id')==$("#my-move-target").attr('mykey')&&$("#my-move-target").attr('fileattr')=='1') {
						mizhu.toast('不能移动到自己的下面哦！！！', 3000);
						return false;
					}
					for (var j = 0; j < dirs.length; j++) {
						dirs[j].style.background = '';
					}
					this.style.background = "#03A9F4";
					this.style.color = '#fff'
					$("#my-move-target").attr('key', this.getAttribute('dir-key'));
					$("#my-move-target").html('选择此目录');
					$("#my-move-target").attr('name', this.getElementsByTagName('em')[0].innerHTML);
				}
			}

		var files_dir = $('.in-turn');
		for(var i = 0 ; i < files_dir.length ; i++){
			files_dir[i].onclick = function () {
				if (this.parentNode.getAttribute('fi_id')==$("#my-move-target").attr('mykey')&&$("#my-move-target").attr('fileattr')=='1') {
					mizhu.toast('不能移动到自己的下面哦！！！', 3000);
					return false;
				}
				var key = this.parentNode.getAttribute('dir-key');
				$.ajax({
								type: "GET",
								url: "sharefun.php",
								data: "new_dir_key="+key,
							 success: function(data){
								 var obj = JSON.parse(data);
								 if (obj.result.length<1) {
									 mizhu.toast('到底了', 3000);
									 return false;
								 }
								 document.getElementById('move-file-in').innerHTML = '';
								 for(var i = 0 ; i < obj.result.length ; i++){
									 document.getElementById('move-file-in').innerHTML+="<div fi_id="+obj.result[i]['id']+" class='file_dir' dir-key='"+obj.result[i]['dir_key']+"'><i class='iconfont icon-wenjian'></i> <em style='font-style: inherit!important;'>"+obj.result[i]['dir_name']+"</em><span class='am-badge in-turn am-badge-success am-radius' style='float:right;margin-top:6px;'>打开</span></div>"
								 }
								 clickfile();
								 	$("#my-move-target").html('请选择目录');
									$("#my-move-target").attr('key', '');
							}
				});
			}
		}
		document.getElementById('close_move').onclick = function () {
			$("#move-file-in").html('<div class="file_dir" dir-key="0"><i class="iconfont icon-wenjian"></i> <em style="font-style: inherit!important;">根目录</em><span class="am-badge in-turn am-badge-success am-radius" style="float:right;margin-top:6px;">打开</span></div>')
			clickfile()
		}
	}
	clickfile()

	}
	movefile()
</script>
