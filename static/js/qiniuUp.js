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
				max_file_size : "10000mb", // 最大文件体积限制
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
					'BeforeUpload' : function(up, file, domain) {
						
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
							url : "../function/upload.php",  //返回信息给后台
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
			url : "../function/upload.php",  // php 后台，获取upToken
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
			url : "../function/upload.php",  // php 后台，获取upToken
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