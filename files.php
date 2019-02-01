<?php
error_reporting(0);
ini_set('date.timezone','Asia/Shanghai');
include 'config.php';
include 'function.php';
islogin(); #判断是否登录
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
	<link rel="stylesheet" type="text/css" href="static/css/amazeui.css">
	<link rel="stylesheet" href="static/alert/css/style.css">
	<!-- 主样式 -->
	<link rel="stylesheet" href="static/css/base.css">
	<link rel="stylesheet" href="static/css/files.css">
	<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript" src="static/js/amazeui.js"></script>
	<link rel="stylesheet" type="text/css" href="static/css/viewer.min.css">
	<script type="text/javascript" src="static/js/viewer.min.js"></script>
	<script type="text/javascript" src="static/alert/js/ui.js"></script>
</head>

<body>
	<div class="main">
		<div class="nav">
			<ul class="am-nav am-nav-pills">
			  <li><a href="main.php">主页</a></li>
			  <li class="am-active"><a href="#">文件管理</a></li>
			  <li><a href="share.php">分享管理</a></li>
			  <li><a href="user.php">账户资料</a></li>
				<li><a href="./imgshow/index.php" target="_blank">图廊</a></li>
			  <li><a href="setting.php">系统设置</a></li>
			  <li><a href="out.php">退出登录</a></li>
			</ul>
		</div>

		<div class="conti">
			<div id="tz" class="am-g">
				<div class="am-u-md-12">
					<div class="am-panel am-panel-default">
					  <div class="am-panel-hd">文件管理</div>
					  <div class="am-panel-bd">
					  	<!-- 头部 -->
					  	<div id="file_header">
					  		<ol id="bread" class="am-breadcrumb">
							  <!-- <li class="am-active" data = ""><a href="">我的网盘</a></li> -->
							</ol>

							<div id="upload" style="position:relative;z-index:12;">
								<div class="am-form-group am-form-file">

								  <button type="button" class="am-btn am-btn-danger am-btn-sm">
								    <i class="am-icon-cloud-upload"></i> 开始上传文件</button>
								  <input id="pickfiles" id="doc-form-file" type="file" multiple>
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
					  	<div id="ctrl" style="position:relative;z-index:10;">
					  		<div>
					  			<ul class="am-list am-list-static am-list-border">
						  			<li>
						  				<div  id="search" class="am-form-group">
									      <!-- <input type="search_key" class="" id="doc-ipt-email-1" placeholder=" 输入搜索文件名"> -->
									      <!-- <button id="search_button" type="button" class="am-btn am-btn-danger"><i class="am-icon-search am-icon-fw"></i>搜索</button> -->
									      <form style="display: inline-block;" action="files.php" method="GET">
										      <input type="search_key" name="mkdir_name" id="dir_name" placeholder="输入创建目录名">
										      <button id="search_button" class="am-btn am-btn-danger">创建目录</button>
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
							            <th class="size" style="width: 10%">文件大小</th>
							            <th class="date" style="width: 10%">上传时间</th>
							            <th>文件操作</th>
							        </tr>
							    </thead>
							    <tbody id="files">
											<script>
												mizhu.toast('加载中...', 5000);
											</script>
							    </tbody>
							</table>
					  	</div>

					  </div>
					</div>
				</div>
			</div>
		</div>

		<div class="am-popup" id="my-popup">
			<div class="am-popup-inner">
				<div class="am-popup-hd">
					<h4 class="am-popup-title">预览内容</h4>
					<span id="close_yulan" data-am-modal-close
								class="am-close">&times;</span>
				</div>
				<div class="am-popup-bd" style="height:100%;">

					<iframe scrolling="auto" id="yulan" src="" width="100%" height="100%"></iframe>

				</div>
			</div>
		</div>

		<div style="width:400px;margin-left:-200px;height:350px" class="am-popup" id="move-file">
			<div class="am-popup-inner">
				<div class="am-popup-hd">
					<h4 class="am-popup-title">移动到</h4>
					<span id="close_move" data-am-modal-close
								class="am-close">&times;</span>
				</div>
				<div id="move-file-in" class="am-popup-bd" style="min-height:90%">
						<div class="file_dir" dir-key="0"><i class="iconfont icon-wenjian"></i> <em style="font-style: inherit!important;">根目录</em><span class="am-badge in-turn am-badge-success am-radius" style="float:right;margin-top:6px;">打开</span></div>
				</div>
				<button id='my-move-target' class="am-btn am-btn-primary am-btn-xs" style="position:absolute;left:50%;margin-left:-37px;margin-top:5px;">开始移动</button>
			</div>
		</div>
		<div id="uploading">
			<h3>上传中 <i class="am-icon-spinner am-icon-spin"></i></h3>
			<a id="file_name"></a>
			<div id="pro_par" class="am-progress am-progress-striped am-active ">
			  <div id="pro" class="am-progress-bar am-progress-bar-secondary"  style="width: 0%">0%</div>
			</div>
			<a id="speed_con" class="am-badge am-badge-warning">NaN</a>
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
<script type="text/javascript" src="https://cdn.staticfile.org/plupload/2.1.9/moxie.js"></script>
<script type="text/javascript" src="https://cdn.staticfile.org/plupload/2.1.9/plupload.full.min.js"></script>
<script type="text/javascript" src="static/js/qiniu.js"></script>
<script>
(function(w, d, u) {
	var uploader;
	function uploaderReady(token,domain) {
			// 引入Plupload 、qiniu.js后
			uploader = Qiniu.uploader({
					runtimes : 'html5,flash,html4', // 上传模式,依次退化
					browse_button : 'pickfiles', // 上传选择的点选按钮，**必需**
					uptoken : token,  // 这里的token是AJAX得到的
					// uptoken_url: 'upload.php',  //获取Token
					save_key: false,  // save_key: true, // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK会忽略对key的处理
					domain : domain, // bucket 域名，下载资源时用到，这个可以到自己的七牛空间里找到具体url，**必需**
					get_new_uptoken : false, // 设置上传文件的时候是否每次都重新获取新的token
					container : 'upload', // 上传区域DOM ID，默认是browser_button的父元素，
					max_file_size : <?php echo '"'.$data[0]['max_upload'].'"'?>, // 最大文件体积限制
					flash_swf_url : 'Moxie.swf', // 引入flash,相对路径
					max_retries : 3, // 上传失败最大重试次数
					dragdrop : true, // 开启可拖曳上传
					drop_element : 'tz', // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
					chunk_size : '4mb', // 分块上传时，每片的体积
					auto_start : true, // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
					unique_names:false, // unique_names: true,
					init : {
							'FilesAdded' : function(up, files) {
									plupload.each(files, function(file) {
											// 文件添加进队列后,处理相关的事情
											console.log(file.name);
											document.getElementById('uploading').style.display='block';
											$("#file_name").html(file.name);
									});
							},
							'BeforeUpload' : function(up, file) {
									// 每个文件上传前,处理相关的事情
							},
							'UploadProgress' : function(up, file) {
									// 每个文件上传时,处理相关的事情
							},
							'FileUploaded' : function(up, file, info) {
								mizhu.toast('加载中...', 5000);
								//上传完成处理
									var file_name = file.name;
									var fsize = file.size;
									var qiniu_name = JSON.parse(info).key;
									$.ajax({
											type : "GET",
											url : "upload.php",  //返回信息给后台
											data : 'file_name='+file_name+"&fszie="+fsize+"&qiniu_name="+qiniu_name,
											success : function(data) {
													if (data=='success') {
														document.location.reload();
													}
											}
									});
							},
							'Error' : function(up, err, errTip) {
									// 上传出错时,处理相关的事情
									// alert()
									console.log("错误："+err);
							},
							'UploadComplete' : function() {
									// 队列文件处理完毕后,处理相关的事情
									document.getElementById('uploading').style.display='none';
							},
							'Key' : function(up, file) {
									// 若想在前端对每个文件的key进行个性化处理，可以配置该函数
									// 该配置必须要在 unique_names: false , save_key: false 时才生效
									//key就是上传的文件路径
										var key = "";
										//获取年月日时分秒
										var filename = file.name;
										var index1=filename.lastIndexOf(".");
										var index2=filename.length;
										var postf=filename.substring(index1,index2);//后缀名
										key += Math.random().toString(36).substr(7)+'-';
										key += Date.parse(new Date())+postf;
										return key;
							},
							UploadProgress: function(up, file) { //上传中，显示进度条
						  	var percent = file.percent;
						    // console.log(percent);
								$('#pro').css('width', percent+'%');
								$('#pro').html(percent+'%');
								$('#speed_con').html(trans_byte(file.speed)+"/s");
						  }
					}
			});
	}

	// domain 为七牛空间（bucket)对应的域名，选择某个空间后可以看到
	// uploader 为一个plupload对象，继承了所有plupload的方法，参考http://plupload.com/docs

	$(d).ready(function() {
					var upToken;
					$.ajax({
							type : "POST",
							url : "upload.php",  // php 后台，获取upToken
							data : 'null',
							success : function(data) {
									var obj = JSON.parse(data);
									upToken = obj.token;
									domain = obj.domain;
									uploaderReady(upToken,domain);
									// console.log('开始');
							},
							error : function(XMLHttpRequest, textStatus, errorThrown) {
									alert("textStatus="+textStatus+"   errorThrown="+errorThrown);
									alert("获取token失败");
							}
					});
	});

	document.getElementById('pickfiles').onclick = function() {
			uploader.start();
	};
})(window, document); // 这个脚本是随着页面加载开始运行的
function getToken(){
	$.ajax({
			type : "POST",
			url : "upload.php",  // php 后台，获取upToken
			data : 'null',
			success : function(data) {
					return data;
					// console.log('开始');
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
					alert("textStatus="+textStatus+"   errorThrown="+errorThrown);
					alert("获取token失败");
			}
	});
}
</script>
<script type="text/javascript" src="static/js/checkfiel.js"></script>
<script type="text/javascript">
//单位换算
function trans_byte($byte)

{

    var KB = 1024;

    var MB = 1024 * KB;

    var GB = 1024 * MB;

    var TB = 1024 * GB;

    if ($byte < KB) {

        return $byte + "B";

    } else if ($byte < MB) {

        return ($byte / KB).toFixed(2) + "KB";

    } else if ($byte < GB) {

        return ($byte / MB).toFixed(2) + "MB";

    } else if ($byte < TB) {

        return ($byte / GB).toFixed(2) + "GB";

    } else {
        return ($byte / TB).toFixed(2) + "TB";

    }
}
//文件预先加载——默认
if (getCookie('dir_key')==''||getCookie('dir_key')==null) {
	getmulu_ajax(0);
}else{
	var key = getCookie('dir_key');
	if (key!=''&&key!=null) {
		getmulu_ajax(key);
	}

}


//图标
function checkIcon(){
var files = document.getElementById('files').getElementsByTagName('tr');
var filename = document.getElementById('files').getElementsByTagName('tr');
for(var i = 0 ; i < files.length ; i++){
	var files2 = files[i].getElementsByTagName('td')[0].getElementsByTagName('i')[0];
	var filename2 = filename[i].getElementsByTagName('td')[0].getElementsByTagName('em')[0];
	if (filename2.innerHTML.lastIndexOf('.zip')!=-1||filename2.innerHTML.lastIndexOf('.rar')!=-1) {
		files2.className = "iconfont icon-yasuobao";
	}else if(filename2.innerHTML.lastIndexOf('.mp4')!=-1){
		files2.className = "iconfont icon-shipin";
	}else if (filename2.innerHTML.lastIndexOf('.docx')!=-1||filename2.innerHTML.lastIndexOf('.doc')!=-1) {
		files2.className = "iconfont icon-wendang";
	}else if (filename2.innerHTML.lastIndexOf('.php')!=-1||filename2.innerHTML.lastIndexOf('.html')!=-1|filename2.innerHTML.lastIndexOf('.java')!=-1) {
		files2.className = "iconfont icon-html";
	}else if (filename2.innerHTML.lastIndexOf('.')==-1) {
		files2.className = "iconfont icon-wenjian";
	}else if (filename2.innerHTML.lastIndexOf('.jpeg')!=-1||filename2.innerHTML.lastIndexOf('.png')!=-1||filename2.innerHTML.lastIndexOf('.gif')!=-1||filename2.innerHTML.lastIndexOf('.jpg')!=-1) {
		files2.className = "iconfont icon-picture_icon";
		files2.parentNode.nextSibling.nextSibling.nextSibling.getElementsByTagName('a')[1].setAttribute('data-am-modal','');
	}else{
		files2.className = "iconfont icon-wenjian1";
	}
}
}
checkIcon();//根据文件名修改图标

//文件移动主方法
function movefile(){
//开始移动_按钮_ajax
document.getElementById('my-move-target').onclick = function () {
		if (this.getAttribute('target')=='') {
			return false;
		}else{
			$.ajax({
							type: "GET",
							url: "sharefun.php",
							data: "myid="+this.getAttribute('mykey')+"&target="+this.getAttribute('target')+"&attr="+this.getAttribute('fileattr'),
						 success: function(data){
							 if (data=='success') {
									$("#onclick_file").remove();
									document.getElementById('close_move').click();
							 }else{
								 mizhu.toast('修改失败', 3000);
								 document.getElementById('close_move').click();
							 }
						}
			});
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
				this.style.background = "#ffc107";
				$("#my-move-target").attr('target', this.getAttribute('dir-key'));
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
						}
			});
		}
	}
}
clickfile()

var temp_move_in = document.getElementById('move-file-in').innerHTML; //临时文件
var temp_dis;//移动文件_原元素属性

//移动文件_按钮——弹出框
function setIsdir(){
	var moves = $('.move');
	for(var i = 0 ; i < moves.length ; i++){
		moves[i].onmouseover = function () {
			if (this.parentNode.parentNode.parentNode.getAttribute('isdir')!='2'&&this.parentNode.parentNode.parentNode.getAttribute('isdir')!='3') {
				$("#my-move-target").attr('fileattr', '1');
				this.parentNode.parentNode.parentNode.setAttribute('isdir','2'); //修改属性，防止进入目录
			}else{
				$("#my-move-target").attr('fileattr', '2');
			}
		}
		moves[i].onmouseout = function () {
			if (this.parentNode.parentNode.parentNode.getAttribute('isdir')!='3') {
				this.parentNode.parentNode.parentNode.setAttribute('isdir','1'); //修改属性，进入目录
			}
		}
		moves[i].onclick = function () {
			temp_dis = this.parentNode.parentNode.parentNode;
			$("#my-move-target").attr('mykey', this.getAttribute('file_id'));
			this.parentNode.parentNode.parentNode.setAttribute('id','onclick_file');
		}
	}
}
setIsdir();
document.getElementById('close_move').onclick = function () {
	temp_dis.setAttribute('id',''); //修改回来
	document.getElementById('move-file-in').innerHTML = temp_move_in;
	clickfile()
}

}


var flag = false;//销毁标记
var viewer;  //图片预览
//预览按钮
function yulan() {

	var eye_watch = $('.watch');
	for(var  i = 0 ; i < eye_watch.length ; i++){
		eye_watch[i].onclick = function () {
			var name = this.getAttribute('name');
			var that = this;
			mizhu.toast('加载中...', 5000);
			$.ajax({
				url: 'sharefun.php',
				type: 'GET',
				data: 'qiniu_name_qm='+name,
				success: function (data) {
					if (name.substr(-3,3)=='jpg'||name.substr(-3,3)=='png'||name.substr(-3,3)=='gif'||name.substr(-4,4)=='jpeg') {
						var a_img = document.getElementById('files').getElementsByTagName('img');
						for(var i = 0 ; i <a_img.length ; i++){
							a_img[i].setAttribute('datas',data);
						}
						if (flag) {
							//销毁处理，防止bug
							viewer.destroy();
							// console.log("销毁")
						}
						viewer = new Viewer(document.getElementById('files'), {
							url: 'datas'
						});
						viewer.show();
						flag = true;
					}else{
						$('#yulan').attr('src',data);
					}
					$('.toast-panel').hide();
				}
			});
		}
	}
	$('#close_yulan').click(function(event) {
		$('#yulan').attr('src','');
	});
}
yulan();

//分享按钮
function shareFun(){
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
}

	function getMulu() { //赋予获取子目录的权利
		//打开目录
		var wenjian = $('tr');
		for(var i = 0 ; i < wenjian.length ; i++){
				wenjian[i].onclick = function(){
					if (this.getAttribute('isdir')=='1') {
						var key = this.getElementsByTagName('td')[0].getElementsByTagName('em')[0].getAttribute('dir-key');
						var innername = getCookie('nav_path')+'^^'+encodeURI(this.getElementsByTagName('td')[0].getElementsByTagName('em')[0].innerHTML);
						var key_temp = getCookie('nav_path_key')+'^^'+key;
						setCookie('nav_path',innername);
						setCookie('nav_path_key',key_temp);
						mizhu.toast('加载中...', 5000);
						getmulu_ajax(key); //打开目录需要获取新的文件列表
						setCookie('dir_key',key);
					}
				}
		}
	}

	function getmulu_ajax(i) {  //获取父级key为i的文件
	$.ajax({
					type: "GET",
					url: "sharefun.php",
					data: "get_list="+i,
				 success: function(data){
					 var obj = JSON.parse(data);
					 var dirs = obj['dirs'];
					 var files = obj['files'];
					 document.getElementById('files').innerHTML = '';

					 if(files==null&&dirs==null){
						 alert("kongbe");
					 }else{
						 for(var  i = 0 ; i < dirs.length ; i++){
							 var key = dirs[i]['dir_key'];
							 var name = dirs[i]['dir_name'];
							 var time = dirs[i]['dir_time'].substring(0,11);
							 var id = dirs[i]['id'];
							 document.getElementById('files').innerHTML += `<tr isdir="1"><td><i class="iconfont icon-wenjian"></i><em class="name" style="font-style: inherit!important;" dir-key=${key}>${name}</em></td><td></td><td class="date">${time}</td><td><div class="method"><span title="重命名" file_id=${id} class="am-badge am-badge-warning am-text-sm rename"><span class="am-icon-pencil"></span></span><span data-am-modal="{target: '#move-file'}" title="移动" file_id=${id} class="am-badge am-badge-success am-text-sm move"><span class="am-icon-arrows"></span></span><span title="删除" isdir="1" file=${key} class="am-badge am-badge-danger am-text-sm delete"><span class="am-icon-trash"></span></span></div></td></tr>`;
						 }
						 getMulu();
						 if (files!=null) {
							 for(var i = 0 ; i < files.length ; i++){
								 var name = files[i]['file_name'];
								 var size = files[i]['file_size'];
								 var time = files[i]['file_time'].substring(0,11);
								 var id = files[i]['id'];
								 var qiniu_name = files[i]['qiniu_name'];
								 document.getElementById('files').innerHTML += `<tr isdir="3"><td><i class="iconfont icon-wenjian1"></i><em class="name" style="font-style: inherit!important;">${name}</em></td><td>${size}</td><td class="date">${time}</td><td><div class="method">
								 <span title="重命名" file_id="${id}" class="am-badge am-badge-warning am-text-sm rename"><span class="am-icon-pencil"></span></span>
								 <a data-am-modal="{target: '#move-file'}" title="移动" file_id="${id}" class="am-badge am-badge-success am-text-sm move"><span class="am-icon-arrows"></span></a>
								 <span title="分享" file_id="${id}" class="am-badge am-badge-secondary am-text-sm share"><span class="am-icon-share-alt"></span></span>
								 <span title="下载" file_id="${id}" class="am-badge am-badge-success am-text-sm download"><span class="am-icon-download"></span></span>
								 <span title="删除" file="${id}" class="delete am-badge am-badge-danger am-text-sm"><span class="am-icon-trash"></span></span>
								 <a title="预览" file_id="${id}" name="${qiniu_name}" qiniu_link="" class="am-badge am-badge-secondary am-text-sm watch" data-am-modal="{target: &quot;#my-popup&quot;}"><span class="am-icon-eye"><img style="position:absolute;z-index:-10;width:0px;" src="http://img.lanrentuku.com/img/allimg/1212/5-121204193Q9-50.gif"></span></a>
								 </div></td></tr>`;
							 }
						 }
						 $('.toast-panel').hide()
						 //以下方法，均需要获取完文件后执行
						 getMulu();
						 yulan();
						 checkIcon();
						 movefile();
						 shareFun();
						 deletedownloadFile();
						 rename();
						 showNav();
					 }
				}
	});
}
getMulu();

if(getCookie('dir_key')==''||getCookie('dir_key')==null){  //如果没有父级key 自动创建
	setCookie('dir_key','0');
}


//文件下载与删除
function deletedownloadFile(){
	var deletes = $('.delete');
	var download = $('.download');

	for(var  i = 0 ; i < download.length ; i++){
		download[i].onclick = function(){
			var file_id = this.getAttribute('file_id');
			window.open("./download.php?file_id="+file_id);
		}
	}
	for(var i = 0 ; i < deletes.length ; i++){
		deletes[i].onclick = function(){
			this.parentNode.parentNode.parentNode.setAttribute('isdir','2'); //修改属性，防止进入目录
			if (this.getAttribute('isdir')=="1") { //目录删除
				var file = this.getAttribute('file');
				var a = this.parentNode.parentNode.parentNode;
				$.ajax({
								 type: "GET",
								 url: "delete.php",
								 data: "dirkey="+file,
								 success: function(data){
										a.innerHTML = '';
										}
				});
			}else {   //单个文件删除
				var file = this.getAttribute('file');
				var a = this.parentNode.parentNode.parentNode;
				$.ajax({
								 type: "GET",
								 url: "delete.php",
								 data: "id="+file,
								 success: function(data){
										a.innerHTML = '';
										}
				});
			}

		}
	}
}

//重命名
function rename(){
var rename_files = $('.rename');
	for(var  i = 0 ; i < rename_files.length ; i++){
		rename_files[i].onclick = function(){
			var id = this.getAttribute('file_id');
			var name = this.parentNode.parentNode.previousElementSibling.previousElementSibling.previousElementSibling;
			var iconfont_html_index = name.innerHTML.indexOf('</i>');
			var iconfont_html = name.innerHTML.substring(0,iconfont_html_index+4);
			var em = name.innerHTML.substring(iconfont_html_index+4,name.length);
			var temp_dir_key = this.parentNode.parentNode.parentNode.getElementsByTagName('td')[0].getElementsByTagName('em')[0].getAttribute('dir-key');
			name.innerHTML = iconfont_html+"<input autofocus='autofocus' style='font-style: inherit!important;' type='text' placeholder='输入新的文件名'/>";
			name.getElementsByTagName('input')[0].focus()
			//失去焦点触发时间
			name.getElementsByTagName('input')[0].onblur = function(){
				var new_name = this.value;
				if (new_name == '') {
					name.innerHTML = iconfont_html+em;
					return false;
				}else{
					var isdir = 1;
					if (em.indexOf('.')==-1) {
						var isdir = 0;
					}

					if (isdir==1) {
						if(new_name.indexOf('.')==-1){
							mizhu.toast('不要忘记后缀名哦！', 3000);
							return false;
						}
					}
					$.ajax({
	               	type: "GET",
	               	url: "sharefun.php",
	               	data: "id="+id+"&newname="+new_name+"&dir="+isdir,
	               	success: function(data){
	               		if (data=='success') {
	               			name.innerHTML = iconfont_html+"<em style='font-style: inherit!important;' dir-key='"+temp_dir_key+"'>"+new_name+"</em>"
	               		}else{
	               			name.innerHTML = iconfont_html+em;
	               			mizhu.toast('更新失败', 3000);
	               		}
	                  }
	            	});
				}
			}
		}
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


	function showNav(){
		//检测导航cookie
		if (getCookie('nav_path')==null) {
			setCookie('nav_path',encodeURI('我的网盘'));
			setCookie('nav_path_key','0');
		}
		//路径导航处理
		var nav = document.getElementById('bread');
		var dir_name = getCookie('nav_path').split('^^');
		var dir_key = getCookie('nav_path_key').split('^^');
		nav.innerHTML = '';
		var temp_nav = '';
		var temp_key = '';
		for(var i = 0 ; i < dir_name.length; i++){
			if (temp_nav!='') {
				temp_nav += '^^'+dir_name[i]
				temp_key += '^^'+dir_key[i]
			}else{
				temp_nav += dir_name[i]
				temp_key += dir_key[i]
			}
			nav.innerHTML+= "<li class='am-active my-nav' data_key="+temp_key+" data_nav="+temp_nav+"><a dir-key="+dir_key[i]+">"+decodeURI(dir_name[i])+"</a></li>";
		}
		//点击
		var click_nav = $('.my-nav');
		for(var i = 0 ; i < click_nav.length ; i++){
			click_nav[i].onclick = function(){
				var key_temp = this.getElementsByTagName('a')[0].getAttribute('dir-key');
				setCookie('nav_path',this.getAttribute('data_nav'));
				setCookie('nav_path_key',this.getAttribute('data_key'));
				setCookie('dir_key',key_temp);
				mizhu.toast('加载中...', 5000);
				getmulu_ajax(key_temp);
			}
		}
	}


	 //创建目录检测 {检测当前目录下是否含有同名目录，如果存在，不给予创建}
	 document.getElementById('search_button').onclick = function(){
	 	var files_ctr = document.getElementById('files');
		var tr_filename = files_ctr.getElementsByTagName('tr');
		var value = document.getElementById('dir_name').value;
		if (value=='') {
			mizhu.toast('目录名不能为空', 3000);
			return false;
		}else{
			for(var i = 0 ; i < tr_filename.length ; i++){
			 	var file_name = tr_filename[i].getElementsByTagName('td')[0].getElementsByTagName('em')[0];
			 	if (value==file_name.innerHTML) {
			 		mizhu.toast('此目录已存在', 3000);
			 		document.getElementById('dir_name').value = '';
			 		return false;
			 	}
			}
		}
	 }

	 //创建目录
	 document.getElementById('search_button').onclick = function(){
		  var mkdir_name = document.getElementById('dir_name').value;
			if (mkdir_name.indexOf('^')!=-1){
				mizhu.toast('不允许出现“^"符号', 3000);
				return false;
			}
		 $.ajax({
						 type: "GET",
						 url: "sharefun.php",
						 data: "mkdir_name="+mkdir_name,
						 success: function(data){
							 if (data=='success') {
								 window.onload();
							 }else{
								 mizhu.toast('创建失败', 3000);
							 }
							 }
			});
	 }
</script>
