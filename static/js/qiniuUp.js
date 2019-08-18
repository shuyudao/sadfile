var uploader;
function uploaderReady(token,domain) {
    // 引入Plupload 、qiniu.js后
    uploader = Qiniu.uploader({
        runtimes : 'html5,flash,html4', // 上传模式,依次退化
        browse_button : 'select_file_btn', // 上传选择的点选按钮，**必需**
        uptoken : token,  // 这里的token是AJAX得到的
        domain : domain,
        save_key: false,  // save_key: true, // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK会忽略对key的处理
        get_new_uptoken : false, // 设置上传文件的时候是否每次都重新获取新的token
        container : 'upload_queue', // 上传区域DOM ID，默认是browser_button的父元素
        flash_swf_url : 'Moxie.swf', // 引入flash,相对路径
        max_retries : 3, // 上传失败最大重试次数
        dragdrop : true, // 开启可拖曳上传
        drop_element : 'upload_queue', // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
        chunk_size : '4mb', // 分块上传时，每片的体积
        auto_start : true, // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
        unique_names:false, // unique_names: true,
        init : {
            'FilesAdded' : function(up, files) {
                plupload.each(files, function(file) {

                    var filesize = tranFileSize(file.size);
                    var parent_dir_key = $("#file-list").attr("partent_dir_key");
                    // 文件添加进队列后,处理相关的事情
                    $("#upload_queue ul").append(`<li id=`+file.id+` par_key=`+parent_dir_key+` class="mdui-list-item mdui-ripple">
								<div class="up_progress" style="width:0%"></div>
								<div class="mdui-list-item-content line-limit-length">
								  <div class="mdui-list-item-title line-limit-length">`+file.name+`</div>
								  <div class="mdui-list-item-text"><span class="size">`+filesize+` </span><span class="upload_pro_text">排队中</span></div>
								</div>
								<i file-el-id=`+file.id+` class="mdui-list-item-icon mdui-icon material-icons mdui-text-color-grey-400 delete_queue">delete</i>
							</li>`);
                    check_upload_queue_num();

                    //删除上传队列
                    $(".delete_queue").click(function(){
                        var fileelid=$(this).attr("file-el-id");
                        uploader.removeFile(fileelid);
                        $(this).parent().remove();
                        check_upload_queue_num();
                    });
                });
            },
            'FileUploaded' : function(up, file, info) {
                //上传完成处理
                var file_name = file.name;
                var fsize = file.size;
                var qiniu_name = JSON.parse(info).key;
                var parent_dir_key = $('#'+file.id).attr("par_key");
                $.ajax({
                    type : "GET",
                    url : "../function/upload.php",  //返回信息给后台
                    data : 'file_name='+file_name+"&fszie="+fsize+"&qiniu_name="+qiniu_name+"&parent_dir_key="+parent_dir_key,
                    success : function(data) {
                        if (data=='success') {

                        }
                    }
                });
                check_upload_queue_num();
            },
            'Error' : function(up, err, errTip) {
                console.log("错误："+err);
            },
            'UploadComplete' : function() {
                // 队列文件处理完毕后,处理相关的事情
                alert("文件上传完成");
                $("#upload_queue .mdui-list li").remove();
                check_upload_queue_num();
                getFileList();
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
			'UploadProgress': function(up, file) { //上传中，显示进度条
                var percent = file.percent;
                // console.log(percent);
                $('#'+file.id+" .up_progress").css('width', percent+'%');
                $('#'+file.id+" .upload_pro_text").html(percent+'%');
            }
        }
    });
}
// domain 为七牛空间（bucket)对应的域名，选择某个空间后可以看到
// uploader 为一个plupload对象，继承了所有plupload的方法，参考http://plupload.com/docs
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
        console.log("init...")
        uploader.start();
    },
    error : function(XMLHttpRequest, textStatus, errorThrown) {
        console.log("textStatus="+textStatus+"   errorThrown="+errorThrown);
        alert("获取上传token失败","error");
    }
});