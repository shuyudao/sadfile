<?php
// error_reporting(0);
ini_set('date.timezone','Asia/Shanghai');
include '../config.php';
include '../function/function.php';
islogin();
$sql_get_all_polciy = "SELECT * FROM policy WHERE status = 1";
$arr = array();

$res = mysqli_query($conn,$sql_get_all_polciy);

while ($row = mysqli_fetch_assoc($res)) {
		$arr[] = $row;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $data[0]['site_name']?> - 系统设置</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<!-- icon样式 -->
	<link rel="stylesheet" type="text/css" href="../static/css/iconfont.css">
	<!-- 框架样式 -->
	<link rel="stylesheet" type="text/css" href="../static/css/amazeui.css">
	<link rel="stylesheet" href="../static/alert/css/style.css">
	<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript" src="../static/js/amazeui.js"></script>
	<script type="text/javascript" src="../static/alert/js/ui.js"></script>
	<!-- 主样式 -->
	<link rel="stylesheet" href="../static/css/base.css">
	<link rel="stylesheet" href="../static/css/files.css">
	<style type="text/css">
		.in_w{
			width: 100%;
		}
		.conti{
			width: 100%;
		}
		#add_policy .am-u-sm-10,#edit_policy .am-u-sm-10{
			margin-left: 40px;
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
			<button style="margin-left: 24px;" id="changeme" type="button" class="am-btn am-btn-primary am-btn-xs">开启编辑</button>
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
									<button style="margin-left: 15px;" id="save_site" type="button" class="am-btn am-btn-success">保存</button>
								</form>
					  		</div>

							<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" >
							    <h2 class="am-titlebar-title ">
							        上传策略
							    </h2>
							</div>

							<div class="in_w">
					  			<form class="am-form am-form-horizontal">
					  				<br>
					  				<div class="am-form-group">
										<div class="am-u-sm-10">
											<b>当前策略：</b>
											<select data-am-selected id="select_policy">
											<?php 
												foreach($arr as $temp){
													if($data[0]['policy_id']==$temp['id']){
											 ?>
											  <option value="<?php echo $temp['id']; ?>" selected><?php echo $temp['name']; ?></option>
											 <?php 
											 	}else{
											?>
												<option value="<?php echo $temp['id']; ?>"><?php echo $temp['name']; ?></option>
											<?php 
											 	}
											  ?>
											 <?php 
											 }
											  ?>
											</select>
											<button
											  type="button"
											  class="am-btn am-btn-primary"
											  data-am-modal="{target: '#edit_policy', closeViaDimmer: 0, width: 400, height: 500}">
											  编辑当前策略
											</button>
										</div>

					  				</div>
					  				<div class="am-form-group">
									<div class="am-u-sm-10">
									<b>添加策略：</b>
										<button
										  type="button"
										  class="am-btn am-btn-primary"
										  data-am-modal="{target: '#add_policy', closeViaDimmer: 0, width: 400, height: 500}">
										  添加新的策略
										</button>
										
									</div>
								</div>
								</form>
								
								<div class="am-modal am-modal-no-btn" tabindex="-1" id="add_policy">
								  <div class="am-modal-dialog">
								    <div class="am-modal-hd">添加上传策略
								      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
								    </div>
								    <div class="am-modal-bd">
								     	<form class="am-form am-form-horizontal" role="form">
											<div class="am-form-group">
												<br>
												<div class="am-u-sm-10">
													
													策略类型：
													<select data-am-selected id="policy_type">
													  <option value="qiniu" selected>七牛云</option>
													  <option value="remote">远程/本地</option>
													</select>
												</div>
												<br>
							  				</div>

										  	<div class="am-form-group">
										  		<div class="am-u-sm-10">
										    		<input type="text" id="policy_name" class="am-form-field am-input-sm" placeholder="策略名（自定义）">
										    	</div>
										  	</div>
											
										  	<div class="am-form-group">
										    	<div class="am-u-sm-10">
										    		<input type="text" id="policy_ak" class="am-form-field am-input-sm" placeholder="ak">
										    	</div>
										  	</div>

										  	<div class="am-form-group">
										    	<div class="am-u-sm-10">
										    		<input type="text" id="policy_sk" class="am-form-field am-input-sm" placeholder="sk">
										    	</div>
										  	</div>

										  	<div class="am-form-group">
										    	<div class="am-u-sm-10">
										    		<input type="text" id="policy_bucket" class="am-form-field am-input-sm" placeholder="bucket">
										    	</div>
										  	</div>
										  	<div class="am-form-group">
										    	<div class="am-u-sm-10">
										    		<input type="text" id="policy_domain" class="am-form-field am-input-sm" placeholder="domain">
										    		<br>
										    	</div>

										    	<button type="button" id="add_policy_btn" class="am-btn am-btn-default">添加策略</button>
										  	</div>
											<p>当选择 <b>远程/本地 </b>策略，sk、bucket两项请留空</p>
										</form>
								    </div>
								  </div>
								</div>

								<div class="am-modal am-modal-no-btn" tabindex="-1" id="edit_policy">
								  <div class="am-modal-dialog">
								    <div class="am-modal-hd">编辑上传策略
								      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
								    </div>
								    <div class="am-modal-bd">
								     	<form class="am-form am-form-horizontal" role="form">
											<div class="am-form-group">
												<br>
												<div class="am-u-sm-10">
													
													策略类型：
													<select data-am-selected id="e_policy_type">
													<?php 
														if ($data[0]['type']=='qiniu') {
													 ?>
													  <option value="qiniu" selected>七牛云</option>
													  <option value="remote">远程/本地</option>
													<?php 
														}else if ($data[0]['type']=='remote') {
													?>
														<option value="qiniu">七牛云</option>
													    <option value="remote" selected>远程/本地</option>
													<?php
														}

													 ?>
													</select>
												</div>
												<br>
							  				</div>

										  	<div class="am-form-group">
										  		<input type="hidden" id="policy_id" value="<?php echo $data[0]['policy_id'] ?>">
										  		<div class="am-u-sm-10">
										    		<input type="text" id="e_policy_name" class="am-form-field am-input-sm" placeholder="策略名（自定义）" value="<?php echo $data[0]['name'] ?>">
										    	</div>
										  	</div>
											
										  	<div class="am-form-group">
										    	<div class="am-u-sm-10">
										    		<input type="text" id="e_policy_ak" value="<?php echo $data[0]['ak'] ?>" class="am-form-field am-input-sm" placeholder="ak">
										    	</div>
										  	</div>

										  	<div class="am-form-group">
										    	<div class="am-u-sm-10">
										    		<input type="text" id="e_policy_sk" value="<?php echo $data[0]['sk'] ?>" class="am-form-field am-input-sm" placeholder="sk">
										    	</div>
										  	</div>

										  	<div class="am-form-group">
										    	<div class="am-u-sm-10">
										    		<input type="text"  id="e_policy_bucket" value="<?php echo $data[0]['bucket'] ?>" class="am-form-field am-input-sm" placeholder="bucket">
										    	</div>
										  	</div>
										  	<div class="am-form-group">
										    	<div class="am-u-sm-10">
										    		<input type="text" id="e_policy_domain" value="<?php echo $data[0]['domain'] ?>" class="am-form-field am-input-sm" placeholder="domain">
										    		<br>
										    	</div>

										    	<button type="button" id="save_policy_edit" class="am-btn am-btn-default">保存编辑</button>
										    	<button type="button" id="del_policy" class="am-btn am-btn-danger">删除策略</button>
										  	</div>
											<p>当选择 <b>远程/本地 </b>策略，sk、bucket两项请留空</p>
										</form>
								    </div>
								  </div>
								</div>
								
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
										<br>
										<button type="button" id="save_get_pwd" class="am-btn am-btn-success">保存</button>
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
												$end = mysqli_query($conn,$SQL);
												$res = mysqli_fetch_assoc($end);
												echo $res['dir_name'];
												if ($res['dir_name']=='') {
													echo "根目录";
												}
											  ?></b>
											 <div id="xuanze" style="width:100px;height:32px;text-align:center;line-height:32px;color:#fff;cursor:pointer" data-am-modal="{target: '#move-file'}" title="tulang" class="am-badge-success am-text-sm move">选择目录</div>
										 </div>
								   </div>
								   <button type="button" id="save_other" class="am-btn am-btn-success">保存</button>
								</form>

						</div>

						</div>
					 </div>
				</div>
			</fieldset>
				<div style="position: relative;top: -20px;">
					<div style="margin-left: 26px;">
								
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
		document.getElementById('xuanze').onclick = function(){
			var fieldset = document.getElementsByTagName('fieldset')[0];
			fieldset.removeAttribute('disabled');
			changeme.disabled = 'disabled';
		}
		$("#save_site").click(function(){
			var site_name = document.getElementById('site_name').value;
			var site_url = document.getElementById('site_url').value;
			var site_des = document.getElementById('site_des').value;
			var spstr = site_url.split("");
			if (spstr[spstr.length-1]=='/') {
				spstr[spstr.length-1]='';
				site_url = spstr.join('');
			}

			if(site_url.indexOf("http")<0){
				site_url = "http://"+site_url;
			}

			$.ajax({
	            type: "POST",
	            url: "../function/setting.php",
	            data: {'site_name':site_name,'site_url':site_url,'site_des':site_des,'method':'site_setting'},
	            success: function(data){
	            	if (data=='success') {
	            		window.location.reload();
	            	}else{
	            		mizhu.toast('修改失败', 3000);
	            	}
	            }
		    });
		})

		$("#add_policy_btn").click(function(){
			var policy_name = $("#policy_name").val();
			var policy_ak = $("#policy_ak").val();
			var policy_sk = $("#policy_sk").val();
			var policy_bucket = $("#policy_bucket").val();
			var policy_domain = $("#policy_domain").val();
			var policy_type = $("#policy_type").val();

			var spstr = policy_domain.split("");
			
			if (spstr[spstr.length-1]=='/') {
				spstr[spstr.length-1]='';
				policy_domain = spstr.join('');
			}
			if(policy_domain.indexOf("http://")<0&&policy_domain.indexOf("https://")<0){
				policy_domain = "http://"+policy_domain;
			}

			$.ajax({
	            type: "POST",
	            url: "../function/setting.php",
	            data: {'policy_name':policy_name,'policy_ak':policy_ak,'policy_sk':policy_sk,'policy_bucket':policy_bucket,'policy_domain':policy_domain,'policy_type':policy_type,'method':'add_policy'},
	            success: function(data){
	            	if (data=='success') {
	            		window.location.reload();
	            	}else{
	            		mizhu.toast('添加失败', 3000);
	            	}
	            }
		    });
		})

		$("#save_policy_edit").click(function(){
			var policy_name = $("#e_policy_name").val();
			var policy_ak = $("#e_policy_ak").val();
			var policy_sk = $("#e_policy_sk").val();
			var policy_bucket = $("#e_policy_bucket").val();
			var policy_domain = $("#e_policy_domain").val();
			var policy_type = $("#e_policy_type").val();
			var policy_id = $("#policy_id").val();

			var spstr = policy_domain.split("");
			
			if (spstr[spstr.length-1]=='/') {
				spstr[spstr.length-1]='';
				policy_domain = spstr.join('');
			}
			if(policy_domain.indexOf("http://")<0&&policy_domain.indexOf("https://")<0){
				policy_domain = "http://"+policy_domain;
			}

			$.ajax({
	            type: "POST",
	            url: "../function/setting.php",
	            data: {'policy_name':policy_name,'policy_ak':policy_ak,'policy_sk':policy_sk,'policy_bucket':policy_bucket,'policy_domain':policy_domain,'policy_type':policy_type,'method':'edit_policy','policy_id':policy_id},
	            success: function(data){
	            	if (data=='success') {
	            		mizhu.toast('保存成功', 3000);
	            	}else{
	            		mizhu.toast('修改失败', 3000);
	            	}
	            }
		    });
		})

		$("#del_policy").click(function(){
			var policy_id = $("#policy_id").val();
			$.ajax({
	            type: "POST",
	            url: "../function/setting.php",
	            data: {'method':'del_policy','policy_id':policy_id},
	            success: function(data){
	            	if (data=='success') {
	            		window.location.reload();
	            	}else{
	            		mizhu.toast('删除失败', 3000);
	            	}
	            }
		    });
		})
		$("#select_policy").change(function(e){
			var id = e.target.value;
			console.log(id);
			$.ajax({
	            type: "POST",
	            url: "../function/setting.php",
	            data: {'method':'use_policy','policy_id':id},
	            success: function(data){
	            	if (data=='success') {
	            		window.location.reload();
	            	}else{
	            		mizhu.toast('删除失败', 3000);
	            	}
	            }
		    });
		})

		$("#save_get_pwd").click(function(){
			var pwd = $("#share_pwd").val();
			$.ajax({
	            type: "POST",
	            url: "../function/setting.php",
	            data: {'method':'edit_share_pwd','pwd':pwd},
	            success: function(data){
	            	if (data=='success') {
	            		window.location.reload();
	            	}else{
	            		mizhu.toast('修改失败', 3000);
	            	}
	            }
		    });
		})

		$("#save_other").click(function(){
			var tulang_state = $("#tulang_state").val();
			var tulang_open = $("#tulang_open").val();
			var tulang_dir = $("#tulang_dir").attr('key');
			var update_max_size = $('#update_max_size').val();
			$.ajax({
	            type: "POST",
	            url: "../function/setting.php",
	            data: {'method':'save_other','tulang_state':tulang_state,
	            'tulang_open':tulang_open,'tulang_dir':tulang_dir,'update_max_size':update_max_size},
	            success: function(data){
	            	if (data=='success') {
	            		window.location.reload();
	            	}else{
	            		mizhu.toast('修改失败', 3000);
	            	}
	            }
		    });
		})

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
		           url: "../function/sharefun.php",
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
								url: "../function/sharefun.php",
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
