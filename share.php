<?php
error_reporting(0);
ini_set('date.timezone','Asia/Shanghai');
include 'config.php';
include 'function.php';
islogin();
#分享数据
$sql_select_share = "SELECT * FROM share;";
$sql_share = mysql_query($sql_select_share);
$data_share = array();
while ($row = mysql_fetch_assoc($sql_share)) {
	$file_id = $row['file_id']; #获取到文件ID，从而获得文件信息
	$sql_select_file = "SELECT * FROM files WHERE id = $file_id";
	$temp_file = mysql_fetch_assoc(mysql_query($sql_select_file));#获得文件信息
	//添加——> 数组
	$row['file_name'] = $temp_file['file_name'];
	$row['file_size'] = $temp_file['file_size'];
	$row['file_time'] = $temp_file['file_time'];
	$data_share[]  = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $data[0]['site_name']?> - 分享管理</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<!-- icon样式 -->
	<link rel="stylesheet" type="text/css" href="font/iconfont.css">
	<!-- 框架样式 -->
	<link rel="stylesheet" type="text/css" href="static/css/amazeui.css">
 	<!-- 主样式 -->
	<link rel="stylesheet" href="static/css/base.css">
	<link rel="stylesheet" href="static/css/files.css">

	<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript" src="static/js/amazeui.js"></script>
</head>
<body>
	<div class="main">
		<div class="nav">
			<ul class="am-nav am-nav-pills">
			  <li><a href="main.php">主页</a></li>
			  <li><a href="files.php">文件管理</a></li>
			  <li class="am-active"><a href="#">分享管理</a></li>
			  <li><a href="user.php">账户资料</a></li>
				<li><a href="./imgshow/index.php" target="_blank">图廊</a></li>
			  <li><a href="setting.php">系统设置</a></li>
			  <li><a href="out.php">退出登录</a></li>
			</ul>
		</div>
		<div class="conti">
			<div class="am-g">
				<div class="am-u-md-12">
					<div class="am-panel am-panel-default">
					  <div class="am-panel-hd">分享管理</div>
					  <div class="am-panel-bd" id="share_count">
					  	<table class="am-table am-table-hover am-table-striped">
							    <thead>
							        <tr>
							            <th id="share_name" style="width: 20%">文件名称</th>
							            <th id="share_link" style="width: 10%">分享链接</th>
							            <th class="share_" style="width: 10%">文件大小</th>
							            <th class="share_" style="width: 10%">上传时间</th>
							            <th class="share_" style="width: 10%">分享时间</th>
							            <th class="share_" style="width: 7%">下载次数</th>
							            <th id="share_pwd" style="width: 8%">提取密码</th>
							            <th id="share_ctr">分享管理</th>
							        </tr>
							    </thead>
							    <tbody id="files">
								<?php for($i = 0 ; $i < count($data_share) ; $i++){
										$v = $data_share[$i];
								 ?>
							        <tr>
							            <td><i class="iconfont icon-yasuobao"></i><em><?php echo $v['file_name']?></em></td>
							            <td><a target="_blank" href="<?php echo $data[0]['site_url'].'fx.php?key='.$v['share_key']?>"><span class="am-badge am-badge-primary am-icon-link"> 点击</span></a></td>
							            <td class="share_"><?php echo $v['file_size']?></td>
							            <td class="share_"><?php echo substr($v['file_time'], 0,10);?></td>
							            <td class="share_"><?php echo $v['share_time']?></td>
							            <td class="share_"><?php echo $v['download_cont']?></td>
							            <td><span class="am-badge am-badge-secondary"><?php echo $v['share_pwd']?></span></td>
							            <td>
							            	<button key="<?php echo $v['share_key']?>" type="button" class="am-btn am-btn-danger am-round delete_share">取消分享</button>
							            	<button key="<?php echo $v['share_key']?>" type="button" class="am-btn am-btn-success am-round change_pwd" id="doc-prompt-toggle">修改提取码</button>
							            </td>
							        </tr>
							    <?php };?>
							    </tbody>
							</table>
					  </div>
					</div>
				</div>
			</div>
		</div>
		<div class="am-modal am-modal-prompt" tabindex="-1" id="my-prompt">
		  <div class="am-modal-dialog">
		    <div class="am-modal-hd">修改提取码</div>
		    <div class="am-modal-bd">
		     固定四位长度 数字+字母
		      <input type="text" class="am-modal-prompt-input">
		    </div>
		    <div class="am-modal-footer">
		      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
		      <span class="am-modal-btn" data-am-modal-confirm>提交</span>
		    </div>
		  </div>
		</div>
		<div id="main_footer" style="margin-top: 270px;">
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
</body>
</html>
<script type="text/javascript">
	for(var i = 0 ; i < $(".delete_share").length ; i++){
		$(".delete_share")[i].onclick = function(){
			var a = this.parentNode.parentNode;
			$.ajax({
	           type: "POST",
	           url: "sharefun.php",
	           data: "key="+this.getAttribute("key"),
	           success: function(data){
	            	if (data=="取消成功") {
	            		a.innerHTML = "";
	            	}else{
	            		alert("取消失败")
	            	}
	            }
	        });
		}

		$(".change_pwd")[i].onclick = function(){
			var a = this.parentNode.parentNode.getElementsByTagName('td')[6].getElementsByTagName('span')[0];
			var key = this.getAttribute("key");
		    $('#my-prompt').modal({
		      relatedTarget: this,
		      onConfirm: function(e) {
		      	if (e.data!='') {
			        $.ajax({
			           type: "POST",
			           url: "sharefun.php",
			           data: "key="+key+"&newpwd="+e.data,
			           success: function(data){
			            	if (data=="修改成功") {
			            		a.innerHTML = e.data;
			            	}else{
			            		alert(data)
			            	}
			            }
			        });
		        }
		      },
		      onCancel: function(e) {

		      }
		    });
		}
	}

</script>
<script type="text/javascript" src="static/js/checkfiel.js"></script>
