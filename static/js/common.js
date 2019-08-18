  
  //删除数组中的第一个对应元素
  function delArrItem(arr , item){
    for(var i = 0 ; i < arr.length ; i++){
      if (arr[i]==item) {
        arr.splice(i,i);
        break;
      }
    }
    return arr;
  }

  //清空元素选择后的状态
  function clearElementAttr(){
    $(".file-item").css("background-color","");
    $(".file-item").removeAttr("checked");
  }

  //还原文件操作按钮状态
  function reSelectFileBtn(){
    $("#paste-file").hide();
    $("#del-file-arr").show();
    $("#cut-file").show();
    $("#share-file-arr").show();
  }


  //弹窗提示 二次封装
  function alert(msg,type){
    var typeclass = '';
    switch(type) {
      case 'success':
        typeclass = 'success_msg';
        break;
      case 'error':
        typeclass = 'error_msg';
        break;
    }

    mdui.snackbar({
        message: '未知消息',
        position: 'top',
        timeout: 2000
    });
    $(".mdui-snackbar-text").html('<i class="mdui-icon '+typeclass+' material-icons">info</i> '+msg);
    $(".error_msg").parent().parent().css("background","#F44336");
  }

  //显示上传队列中的数量
  function check_upload_queue_num(){
    var file_queue_num = $("#upload_queue li").length;
    if (file_queue_num>0) {
      $(".tag_num").show();
      $(".tag_num").text(file_queue_num);
    }else{
      $(".tag_num").hide();
    }
  }


  //字节转换
  function tranFileSize(fileByte) {
    var fileSizeByte = fileByte;
    var fileSizeMsg = "";
    if (fileSizeByte < 1048576) fileSizeMsg = (fileSizeByte / 1024).toFixed(2) + "KB";
    else if (fileSizeByte == 1048576) fileSizeMsg = "1MB";
    else if (fileSizeByte > 1048576 && fileSizeByte < 1073741824) fileSizeMsg = (fileSizeByte / (1024 * 1024)).toFixed(2) + "MB";
    else if (fileSizeByte > 1048576 && fileSizeByte == 1073741824) fileSizeMsg = "1GB";
    else if (fileSizeByte > 1073741824 && fileSizeByte < 1099511627776) fileSizeMsg = (fileSizeByte / (1024 * 1024 * 1024)).toFixed(2) + "GB";
    else fileSizeMsg = "文件超过1TB";
    return fileSizeMsg;
}

//获取文件后缀名
  function getSuffix(filename) {
      filename = filename.toLowerCase();
      var spl = filename.split(".");
      var suffix = spl[spl.length-1];
      return suffix;
  }

//复制文本
  function copyText() {
      var text = $(".mdui-dialog-content").text();
      var input = document.getElementById("input");
      input.value = text; // 修改文本框的内容
      input.select(); // 选中文本
      document.execCommand("copy"); // 执行浏览器复制命令
      alert("复制成功");
  }

  //获取窗口高度
function getClientHeight()
{
  var clientHeight=0;
  if(document.body.clientHeight&&document.documentElement.clientHeight)
  {
  var clientHeight = (document.body.clientHeight<document.documentElement.clientHeight)?document.body.clientHeight:document.documentElement.clientHeight;
  }
  else
  {
  var clientHeight = (document.body.clientHeight>document.documentElement.clientHeight)?document.body.clientHeight:document.documentElement.clientHeight;
  }
  return clientHeight;
}

//html代码转义
function HTMLEncode(html) {    var temp = document.createElement ("div");    (temp.textContent != null) ? (temp.textContent = html) : (temp.innerText = html);    var output = temp.innerHTML;    temp = null;    return output; }


//拖拽

jQuery.fn.moveDivByID= function (id) {
    $("#"+id).css("z-index","6000");

    $("#"+id).mousedown(function(e){
        $(this).css("cursor","move");
        var offset= $(this).offset();
        console.log("c");
        var x= e.pageX-offset.left;
        var y= e.pageY-offset.top;
 
 
        $(document).bind("mousemove",function(ev){
            $("#"+id).stop();
            var _x= ev.pageX-x;
            var _y= ev.pageY-y;
            $("#"+id).animate({left:_x+"px",top:_y+"px"},10);
        });
    });
 
    
    $(document).mouseup(function()
    {
        $("#"+id).css("cursor","default");
        $(this).unbind("mousemove");
    });
};

function controllerMenu(x,y,that){
           that.css("background-color","#dfdfdf");
           $("#file-item-menu").css({'top':y,'left':x,'visibility':'visible','opacity':"1",'transform':'scale(1)'});
           $("#cover_o").css("display","block");
           var file_type = that.attr("folder");
           var orgin_name = that.attr("qiniu_name");
           var file_id = that.attr("file-id");
           var file_name = that.find(".mdui-list-item-title").text();

          if(file_type=="folder"){
            $("#download_file").hide();
          }

           //预览文件
           $("#open_file").off("click").click(function(){
              hideMenu();
              if (file_type!="folder") {
                var suffix = getSuffix(orgin_name);
                //打开图片
                if (suffix=="jpg"||suffix=="png"||suffix=="gif"||suffix=="jpeg"||suffix=="bmp") {
                  $.ajax({
                    url:"./function/api.php",
                    data:{'preview':orgin_name},
                    success:function(data){
                      var data = JSON.parse(data);
                      if (data['status']==200) {
                        
                        $("#viewer img").attr("src",data['data']);
                        $("#viewer img").attr("data-original",data['data']);
                        $("#viewer img").attr("alt",file_name);

                        if (bool_viewer) {
                          viewer.destroy(); //销毁
                        }
                        viewer = new Viewer(document.getElementById('viewer'),{
                          url:"data-original",
                          navbar:false,
                          toolbar:false
                        });

                        $("#viewer img").click();

                        $(".viewer-close").click(function(){
                          bool_viewer = true;
                          $("#viewer img").attr("src","");
                          $("#viewer img").attr("data-original","");
                        })

                        $(".viewer-canvas").click(function(){
                          bool_viewer = true;
                          $("#viewer img").attr("src","");
                          $("#viewer img").attr("data-original","");
                        })

                      }else{
                        alert("打开失败","error")
                      }
                    }
                  })
                }else if(suffix=='mp4'||suffix=='flv'){ //视频预览支持的格式 与remote/FileManger的同步
                  $("#video").show();
                  $("#cover_o").css("display","block");
                  type = "auto";
                  if (suffix=='flv') {
                    type = 'flv';
                  }
                  $.ajax({
                    url:"./function/api.php",
                    data:{'preview':orgin_name},
                    success:function(data){
                      var url = JSON.parse(data)['data'];
                      dp = new DPlayer({
                          container: document.getElementById('dplayer'),
                          video: {
                              url: url,
                              type: type
                          },
                      });
                    }
                  })

                  
                }else if(suffix=='pdf'){
                  $.ajax({
                    url:"./function/api.php",
                    data:{'preview':orgin_name},
                    success:function(data){
                      window.open(JSON.parse(data)['data'],"_blank");
                    }
                  })
                }else if(suffix=='txt'||suffix=='php'||suffix=='java'||suffix=='sql'||suffix=='py'||suffix=='css'||suffix=='js'||suffix=='html'){
                  $("#view_txt").show();
                  $.ajax({
                    url:"./function/api.php",
                    data:{'preview':orgin_name},
                    success:function(data){
                      var url = JSON.parse(data)['data'];
                      var k = $.get(url,function(data,status){
                          $("#view_txt code").html(HTMLEncode(data));
                          $("#view_txt code").attr("class","language-"+suffix);
                          var oHead = document.getElementsByTagName('HEAD').item(0); 
                          var oScript= document.createElement("script"); 
                          oScript.type = "text/javascript"; 
                          oScript.src="./static/js/prism.js"; 
                          oHead.appendChild(oScript);

                      });
                    }
                  })
                }else if(suffix=="mp3"||suffix=="m4a"||suffix=="flac"||suffix=="aac"||suffix=="wav"){
                  $("#music").css("display","none");
                  $("#d-audio-content").remove();
                  $("#music").css("display","block");
                  $.ajax({
                    url:"./function/api.php",
                    data:{'preview':orgin_name},
                    success:function(data){

                      wxAudio = new Daudio({
                          ele: '#music',
                          name: file_name,
                          src: JSON.parse(data)['data'],
                          showprogress: true,
                          initstate: '',
                          loop: false
                      })

                      $("#d-audio-next").remove();
                      $(".left-config").append(`<i id="close_music" class="mdui-icon material-icons">close</i>`);
                      
                    }
                  })

                }else if (suffix=="doc"||suffix=="docx"||suffix=="xls"||suffix=="xlsx"||suffix=="ppt"||suffix=="pptx") {
                  $.ajax({
                    url:"./function/api.php",
                    data:{'preview':orgin_name},
                    success:function(data){
                      //微软官方接口
                      //地址必须是域名，不可以使用ip地址，且端口需要是80
                      window.open("https://view.officeapps.live.com/op/view.aspx?src="+encodeURIComponent(JSON.parse(data)['data']),"_blank");
                    }
                  })
                }else{
                  alert("此类文件暂不支持预览","error");
                }
              }else{
                $("#file-list").attr("partent_dir_key",file_id);
                $("#bread").append(`<span>/</span><span id=`+file_id+`>`+file_name+`</span>`)
                getFileList();
              }

           })
           //下载文件
           $("#download_file").off("click").click(function(){
              hideMenu();
              $("#download-file").attr("src","./function/download.php?file_id="+file_id);
           })

           //分享文件
           $("#share_file").off("click").click(function(){
              hideMenu();
              $.ajax({
                url:"./function/postshare.php",
                method:"post",
                data:{'file_id':[file_id]},
                success:function(data){
                  var data = JSON.parse(data);
                  var contents = data['data'].split("^^");
                  mdui.dialog({
                    title: '分享成功',
                    content: '链接：'+contents[0]+" 提取码："+contents[1],
                    buttons: [
                      {
                        text: '复制链接',
                        onClick:function(){
                          copyText();
                        }
                      }
                    ]
                  });
                  
                }
              })
           })

           //文件重命名
           $("#rename_file").off("click").click(function(){
              hideMenu();
              var dir = 0;
              if (file_type=="folder") {
                dir=1;
              }
              mdui.prompt('文件重命名为',
                function (value) {
                  if (value!="") {
                    $.ajax({
                      url:"./function/api.php",
                      data:{'id':file_id,'newname':value,"dir":dir},
                      success:function(data){
                        $(".file-item[file-id="+file_id+"]").find(".mdui-list-item-title").text(value);
                      }
                    })
                  }
                },
                function (value) {
                  
                },
                {
                  confirmText:"确认",
                  cancelText:"取消",
                  defaultValue:file_name
                }
              );

              
           })

           $("#delete_file").off("click").click(function(){
              hideMenu();
              if (file_type=="folder") {
                var dir_id_arr = [file_id];
              }else{
                var file_id_arr = [file_id];
              }
              alert("删除成功");
              $(".file-item[file-id="+file_id+"]").animate({
                left:"500px",
                opacity:0
              },300,function(){
                $(".file-item[file-id="+file_id+"]").remove();
              });
              $.ajax({
                url:"./function/delete.php",
                data:{'file':file_id_arr,"dir":dir_id_arr},
                success:function(data){
                  if (JSON.parse(data)['data']!=1) {
                    alert("删除失败","error");
                  }
                }
              })
           })
}