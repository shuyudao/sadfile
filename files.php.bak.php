<?php 
error_reporting(0); 
ini_set('date.timezone','Asia/Shanghai');
include 'config.php';
include 'function.php';
islogin(); #判断是否登录

$dir_arr = array(); #目录数组
$file_arr = array(); #文件数组
if ($_COOKIE['dir']=='') {
	setcookie("dir",urlencode('我的网盘'), time()+3600*24);  #无cookie目录，自动设为根目录
	setcookie('nav_path',urlencode('我的网盘'),time()+3600*24);
}
$cookie_dir = urldecode($_COOKIE['dir']);
$sql_arr_dirs = "SELECT * FROM dirs WHERE partent_dir = '$cookie_dir'"; #筛选出当前目录下的子目录
$sql_arr_files = "SELECT * FROM files WHERE partent_dir = '$cookie_dir'";  #筛选出当前目录下的子文件
$dir_list = mysql_query($sql_arr_dirs); #查询出的所有目录
$file_list = mysql_query($sql_arr_files);	#查询出的所有文件
echo mysql_error();
#先读目录，再读文件
while ($row = mysql_fetch_assoc($dir_list)) { 
	$dir_arr[] = $row;  #目录
}
while ($row = mysql_fetch_assoc($file_list)) { 
	$file_arr[] = $row;  #文件
}
// var_dump($file_arr);
// var_dump($dir_arr);


$new_path = $_GET['dir']; #当前目录
if ($new_path!='') {
	setcookie("dir",$new_path, time()+3600*24); //设置cookie，读取该目录下的文件或目录
	echo "<script>window.location.href = 'files.php'</script>";//刷新当前页，解决需要点两次的问题
}

$partent_dir = urldecode($_COOKIE['dir']);

//创建目录
$mkdir_name = $_GET['mkdir_name'];
if ($mkdir_name!='') {
	$sql_add_dir = "INSERT into dirs (dir_name,dir_time,partent_dir) VALUES ('$mkdir_name',CURDATE(),'$partent_dir');";
	mysql_query($sql_add_dir);
	echo "<script>window.location.href = 'files.php'</script>";//刷新当前页，解决需要点两次的问题
}

//进入目录
if($_GET['path']!=''){
	setcookie("dir",$_GET['path'], time()+3600*24); #修改cookie为当前目录下的所有文件
	#同时需要修改导航路径
	setcookie('nav_path',urlencode(urldecode($_COOKIE['nav_path']).'^^'.$_GET['path']),time()+3600*24);
	echo "<script>window.location.href = 'files.php'</script>";//刷新当前页，解决需要点两次的问题
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title><?php echo $data[0]['site_name']?> - 文件管理</title>
	<!-- icon样式 -->
	<link rel="stylesheet" type="text/css" href="font/iconfont.css">
	<!-- 框架样式 -->
	<link rel="stylesheet" type="text/css" href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css">
	
	<!-- 主样式 -->
	<link rel="stylesheet" href="static/css/base.css">
	<link rel="stylesheet" href="static/css/files.css">
	<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript" src="http://cdn.amazeui.org/amazeui/2.7.2/js/amazeui.min.js"></script>
</head>

<body>
	<div class="main">
		<div class="nav">
			<ul class="am-nav am-nav-pills">
			  <li><a href="main.php">主页</a></li>
			  <li class="am-active"><a href="#">文件管理</a></li>
			  <li><a href="share.php">分享管理</a></li>
			  <li><a href="user.php">账户资料</a></li>
			  <li><a href="setting.php">系统设置</a></li>
			  <li><a href="out.php">退出登录</a></li>
			</ul>
		</div>

		<div class="conti">
			<div class="am-g">
				<div class="am-u-md-12">	
					<div class="am-panel am-panel-default">
					  <div class="am-panel-hd">文件管理</div>
					  <div class="am-panel-bd">
					  	<!-- 头部 -->
					  	<div id="file_header">
					  		<ol id="bread" class="am-breadcrumb">
							  <!-- <li class="am-active" data = ""><a href="">我的网盘</a></li> -->
							</ol>

							<div id="upload">
								<div class="am-form-group am-form-file">
								  <button type="button" class="am-btn am-btn-danger am-btn-sm">
								    <i class="am-icon-cloud-upload"></i> 开始上传文件</button>
								   <input name="token" type="hidden" value="<?php echo $UPtoken?>">
								  <input id="doc-form-file" type="file" multiple>
								</div>
								<div id="file-list"></div>
								<script>
								  $(function() {
								    $('#doc-form-file').on('change', function() {
								      var fileNames = '';
								      $.each(this.files, function() {
								        fileNames += '<span class="am-badge">' + this.name + '</span> ';
								      });
								      $('#file-list').html(fileNames);
								    });
								  });
								</script>
							</div>
					  	</div>
					  	<!-- 基本管理 -->
					  	<div id="ctrl">
					  		<div>
					  			<ul class="am-list am-list-static am-list-border">
						  			<li>
						  				<div  id="search" class="am-form-group">
									      <!-- <input type="search_key" class="" id="doc-ipt-email-1" placeholder=" 输入搜索文件名"> -->
									      <!-- <button id="search_button" type="button" class="am-btn am-btn-danger"><i class="am-icon-search am-icon-fw"></i>搜索</button> -->
									      <form style="display: inline-block;" action="files.php" method="GET">
										      <input type="search_key" name="mkdir_name" id="dir_name" placeholder="输入创建目录名">
										      <button id="search_button" type="submit" class="am-btn am-btn-danger">创建目录</button>
									      </form>
									    </div>
						  			</li>
								</ul>
					  		</div>
					  	</div>
					  	<!-- 文件列表 -->
					  	<div id="file_list">
					  		<table class="am-table am-table-hover am-table-striped">
							    <thead>
							        <tr>
							            <th style="width: 40%">文件名称</th>
							            <th style="width: 10%">文件大小</th>
							            <th style="width: 20%">上传时间</th>
							            <th>文件操作</th>
							        </tr>
							    </thead>
							    <tbody id="files">
							    	<!-- 如有目录遍历出所有目录 -->
							    	<?php 
							    		$i = 0;
							    		while ($i < count($dir_arr)) {
							    			$v = $dir_arr[$i];
							    	?>
							        <tr isdir='1'>
							            <td><i class="iconfont icon-yasuobao"></i><em style="font-style: inherit!important;"><?php echo $v['dir_name']?></em></td>
							            <td></td>
							            <td><?php echo  substr($v['dir_time'], 0,10);?></td>
							           	<td></td>
							        </tr>
							        <?php 
							        	$i++;
							        	};
							        ?>
							        <!-- 遍历出所有的文件 -->
							       <?php 
							    		$i = 0;
							    		while ($i < count($file_arr)) {
							    			$v = $file_arr[$i];
							    	?>
							        <tr>
							            <td><i class="iconfont icon-yasuobao"></i><em style="font-style: inherit!important;"><?php echo $v['file_name']?></em></td>
							            <td><?php echo $v['file_size'];?></td>
							            <td><?php echo substr($v['file_time'], 0,10);?></td>
							            <td>
							            	<button file_id = "<?php echo $v['id']?>" type="button" class="am-btn am-btn-secondary am-round share">分享</button>
							            	<button file_id="<?php echo $v['id']?>" type="button" class="am-btn am-btn-success am-round download">下载</button>
							            	<button file="<?php echo '.'.$_COOKIE['nowpath'].'/'.$file_arr[$i]?>" type="button" class="am-btn am-btn-danger am-round delete">删除</button>
											<button type="button" class="am-btn am-btn am-round">...</button>
							            </td>
							        </tr>
							        <?php 
							        	$i++;
							        	};
							        ?>
							    </tbody>
							</table>
					  	</div>
					  </div>
					</div>
				</div>
			</div>
		</div>
		
		<div id="uploading">
			<h3>上传中 <i class="am-icon-spinner am-icon-spin"></i></h3>
		</div>

		<div class="am-modal am-modal-no-btn" tabindex="-1" id="your-modal">
		  <div class="am-modal-dialog">
		    <div class="am-modal-hd">分享成功
		      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
		    </div>
		    <div class="am-modal-bd" style="line-height: 16px;">
		      <p style="text-align: left;">分享地址：<a href="http://127.0.0.1/share?key=addw7gag" id="share_link">http://127.0.0.1/share?key=addw7gag</a></p>
		      <p style="text-align: left;">提 取 码 ：<b id="share_pwd" style="color: #f44336">gA8z</b></p>
		    </div>
		  </div>
		</div>
		<div id="main_footer">
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
//分享按钮
for(var i = 0 ; i < $('.share').length ; i++){
	$('.share')[i].onclick = function(){
		var file_id = this.getAttribute("file_id");
		$.ajax({
               type: "POST",
               url: "postshare.php",
               data: "file_id="+file_id,
               success: function(data){
                	var $modal = $('#your-modal');
				    $modal.modal();
				    var arr = data.split('^^');
				    $('#share_link').html(arr[0]);
				    $('#share_link').attr("href",arr[0]);
				    $('#share_pwd').html(arr[1]);
                }
            });
	}
}


	var download = $('.download');
	var deletes = $('.delete');
	var wenjian = $('tr');
	//打开目录
	for(var i = 0 ; i < wenjian.length ; i++){
		if (wenjian[i].getAttribute('isdir')=='1') {
			wenjian[i].onclick = function(){
				window.location.href = '?path='+this.getElementsByTagName('em')[0].innerHTML;
			}
		}
	}

	//文件下载与删除
	for(var  i = 0 ; i < download.length ; i++){
		download[i].onclick = function(){
			var file_id = this.getAttribute('file_id');
			window.open("./download.php?file_id="+file_id);
		}
		deletes[i].onclick = function(){
			var file = this.getAttribute('file');
			var a = this.parentNode.parentNode;
			$.ajax({
               type: "GET",
               url: "delete.php",
               data: "file="+file,
               success: function(data){
                	a.innerHTML = '';
                  }
            });
		}
	}

	//cookie方法
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
	// alert(decodeURI(getCookie('dir')))
	//上传文件
	var upload = document.getElementById('doc-form-file');
	upload.onchange = function(){
			document.getElementById('uploading').style.display='block';
			var file = upload .files[0];//获取文件
			if (file!=null) {
   			var form = new FormData();//新建FormData对象
			form.append('file',file);//添加文件到FormData
			var dir = decodeURI(getCookie('dir')); //由于存储的COOKIE为URL的所以需要转

			var xhr = new XMLHttpRequest(); //新建XHR对象
			xhr.open('POST','upload.php',true);//请求
			xhr.send(form);//发送文件
			xhr.onreadystatechange = function(){ //监控响应
				if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 304)) {//请求成功
					//处理对象
        			if (xhr.responseText=='成功') {
        				document.getElementById('uploading').style.display='none';
        			}else{
        				document.getElementById('uploading').innerHTML = '<h3>上传失败</h3>';
        				setTimeout(function(){
        					document.getElementById('uploading').style.display='none';
        				},2000);
        			}
      			}

      		}
		}
	}

	//检测导航cookie
	if (getCookie('nav_path')==null) {
		setCookie('nav_path',decodeURI('我的网盘'));
	}

	//路径导航处理
	var nav = document.getElementById('bread');
	var nav_cookie = decodeURI(getCookie('nav_path'));
	var dir_arr = nav_cookie.split('^^');
	//显示
	var path = "";
	 var nav_li = document.getElementById('bread').getElementsByTagName('li');
	 for(var i = 0 ; i < dir_arr.length ; i++){
	 	if (path=='') {
	 		path += dir_arr[i];
	 	}else{
	 		path += '^^'+dir_arr[i];
	 	}
		nav.innerHTML+= "<li class='am-active' data="+path+"><a>"+dir_arr[i]+"</a></li>";
	 }
	//路径点击处理
	 for(var i =  0 ; i < nav_li.length ; i++){
	 	nav_li[i].onclick = function(){
	 		setCookie('nav_path',encodeURI(this.getAttribute('data')));
	 		setCookie('dir',encodeURI(this.getElementsByTagName('a')[0].innerHTML));
	 		window.location.href = 'files.php';
	 	}
	 }

	 //创建目录检测 {检测当前目录下是否含有同名目录，如果存在，不给予创建}
	 document.getElementById('search_button').onclick = function(){
	 	var files_ctr = document.getElementById('files');
		var tr_filename = files_ctr.getElementsByTagName('tr');
		var value = document.getElementById('dir_name').value;
		if (value=='') {
			alert("目录名不能为空")
			return false;
		}else{
			for(var i = 0 ; i < tr_filename.length ; i++){
			 	var file_name = tr_filename[i].getElementsByTagName('td')[0].getElementsByTagName('em')[0];
			 	if (value==file_name.innerHTML) {
			 		alert("此目录已存在");
			 		document.getElementById('dir_name').value = '';
			 		return false;
			 	}
			}
		}
	 }
	 
</script>
<script type="text/javascript" src="static/js/checkfiel.js"></script>