var uploader_img = new plupload.Uploader({
        runtimes: 'html5,flash,silverlight,html4', //上传方式顺序优先级
        browse_button: 'pickfiles', //选择图片按钮id
        container: 'upload', //容器
        url: "", //服务器接口地址
        flash_swf_url: 'Moxie.swf',
        multi_selection: true, //false为单图上传，true为多图上传
        chunk_size: "4mb",//分片上传，每片的大小
        fmax_file_size : max_file_size, // 最大文件体积限制
        max_retries : 3, // 上传失败最大重试次数
        dragdrop : true, // 开启可拖曳上传
        drop_element : 'tz', // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
        auto_start : true, // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
        init: {
            //init事件发生后触发
            PostInit: function () {

            },
            FilesAdded: function (up, files) { //文件选择之后的触发的方法
                plupload.each(files, function(file) {
                    // 文件添加进队列后,处理相关的事情
                    console.log(file.name);
                    document.getElementById('uploading').style.display='block';
                    $("#file_name").html(file.name);
                });
                uploader_img.start();
            },
            'UploadComplete' : function() {
                // 队列文件处理完毕后,处理相关的事情
                document.getElementById('uploading').style.display='none';
                document.location.reload();
            },
            //进度条
            UploadProgress: function (up, file) { //上传过程中调用的方法
                var percent = file.percent;
                // console.log(percent);
                $('#pro').css('width', percent+'%');
                $('#pro').html(percent+'%');
                // $('#speed_con').html(trans_byte(file.speed)+"/s");
            },
            FileUploaded: function (up, file, res) { //文件上传完成后，up:plupload对象，file:上传的文件相关信息，res:服务器返回的信息
                var return_msg = $.parseJSON(res.response);
                console.log(return_msg);
                mizhu.toast('加载中...', 5000);
                //上传完成处理
                var file_name = return_msg.OriginalFileName;
                var fsize = return_msg.fielSize;
                var qiniu_name = return_msg.newFileName;
                $.ajax({
                    type : "GET",
                    url : "../function/upload.php",  //返回信息给后台
                    data : 'file_name='+file_name+"&fszie="+fsize+"&qiniu_name="+qiniu_name,
                    success : function(data) {
                        if (data=='success') {
                            // document.location.reload();
                        }
                    }
                });

            },
            Error: function (up, err) {
                console.log("发生错误，错误内容如下:");
                console.log(err);
            }
        }
    });
    

    $.ajax({
        url:"../function/upload.php",
        type: "GET",
        success:function(data){
            data = JSON.parse(data);
            uploader_img.setOption("multipart_params", {'token': data['token']});
            uploader_img.setOption("url",data['domain']);
            uploader_img.init();
        }
    })
    document.getElementById('pickfiles').onclick = function() {
        console.log("远程上传策略");
        
    };